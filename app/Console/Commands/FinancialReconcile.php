<?php

namespace App\Console\Commands;

use App\Services\Financial\FinancialReconciliationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FinancialReconcile extends Command
{
    protected $signature = 'financial:reconcile
        {--from= : Start date (Y-m-d) for donations to include}
        {--to= : End date (Y-m-d) for donations to include}';

    protected $description = 'Validate donation fee & revenue accounting against the financial ledger. Read-only.';

    public function handle(FinancialReconciliationService $service): int
    {
        $from = $this->option('from') ? Carbon::parse($this->option('from'))->startOfDay() : null;
        $to = $this->option('to') ? Carbon::parse($this->option('to'))->endOfDay() : null;

        $report = $service->reconcile($from, $to);

        $this->line(sprintf('Range: %s → %s', $report['from'] ?? 'all', $report['to'] ?? 'all'));
        $this->line(sprintf('Completed donations: %d', $report['count']));
        $this->newLine();

        $c = $report['counts'];
        $this->line(sprintf('  Captured (retained):            %d', $c['captured']));
        $this->line(sprintf('  Refunded:                        %d', $c['refunded']));
        $this->newLine();

        $s = $report['summaries'];
        $this->line('Donation position (Captured - Refunded = Net retained):');
        $this->line(sprintf('  Captured                        %s', $s['captured_total']));
        $this->line(sprintf('  - Refunded                      %s', '('.number_format((float) $s['refunded_total'], 2).')'));
        $this->line(sprintf('  = Net retained                  %s', $s['net_retained_amount']));

        $this->newLine();
        $this->line('Fees & revenue (retained donations):');
        $this->line(sprintf('  Platform fee total              %s', $s['platform_fee_total']));
        $this->line(sprintf('  Gateway fee total               %s', $s['gateway_fee_total']));
        $this->line(sprintf('  Gateway tax total               %s', $s['gateway_tax_total']));
        $this->line(sprintf('  Actual platform revenue         %s', $s['actual_platform_revenue']));

        $this->newLine();
        $this->line('Payouts:');
        $this->line(sprintf('  Successful payouts              %s', $s['successful_payouts']));
        $this->line(sprintf('  Pending payout liability        %s', $s['pending_payout_liability']));

        $fd = $report['fee_data'];
        $this->newLine();
        $this->line(sprintf('Fee data: %d with KNOWN ACTUAL fees, %d with fee data UNAVAILABLE',
            $fd['known_actual_fees'], $fd['missing_gateway_fee_captures']));

        $this->newLine();
        $this->line('Checks:');

        foreach ($report['checks'] as $label => $value) {
            if (is_bool($value)) {
                $this->{$value ? 'info' : 'error'}(sprintf('  [%s] %s', $value ? 'OK' : 'FAIL', $label));
            } else {
                $this->line(sprintf('  %-40s %s', $label.':', $value));
            }
        }

        $this->newLine();

        $warnings = $report['warnings'];

        if (count($warnings) === 0) {
            $this->info('No discrepancies found.');
        } else {
            $this->error(sprintf('%d warning(s) found:', count($warnings)));

            foreach ($warnings as $warning) {
                $this->warn('  - '.$warning);
            }
        }

        $failedChecks = collect($report['checks'])->filter(fn ($v) => $v === false)->count();

        return $failedChecks > 0 || count($warnings) > 0 ? self::FAILURE : self::SUCCESS;
    }
}
