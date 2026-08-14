<?php

namespace App\Console\Commands;

use App\Models\PayoutAccount;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Crypto;

class EncryptPayoutAccountSensitiveData extends Command
{
    protected $signature = 'payout-accounts:encrypt-sensitive
        {--dry-run : Show what would be changed without making changes}
        {--batch=100 : Number of records to process per batch}';

    protected $description = 'Encrypt plaintext sensitive fields in payout_accounts table';

    protected const SENSITIVE_FIELDS = [
        'account_holder_name',
        'bank_name',
        'account_number',
        'ifsc_code',
        'upi_id',
    ];

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $batchSize = (int) $this->option('batch');

        $totalRecords = PayoutAccount::count();
        $this->info("Total payout accounts found: {$totalRecords}");

        if ($totalRecords === 0) {
            $this->info('No records to process.');

            return self::SUCCESS;
        }

        $recordsToMigrate = 0;
        $migrated = 0;
        $skipped = 0;
        $alreadyEncrypted = 0;

        $bar = $this->output->createProgressBar($totalRecords);
        $bar->start();

        PayoutAccount::query()->orderBy('id')->chunk($batchSize, function ($accounts) use (
            &$recordsToMigrate,
            &$migrated,
            &$skipped,
            &$alreadyEncrypted,
            $dryRun,
            $bar
        ) {
            foreach ($accounts as $account) {
                $needsMigration = false;
                $updates = [];

                foreach (self::SENSITIVE_FIELDS as $field) {
                    $value = $account->{$field};

                    if ($value === null || $value === '') {
                        continue;
                    }

                    if ($this->isEncrypted($value)) {
                        $alreadyEncrypted++;

                        continue;
                    }

                    $needsMigration = true;
                    $updates[$field] = $this->encryptValue($value);
                }

                if ($needsMigration && ! $dryRun) {
                    $account->withoutEvents(function () use ($account, $updates) {
                        $account->forceFill($updates)->save();
                    });
                    $migrated++;
                } elseif ($needsMigration && $dryRun) {
                    $migrated++;
                } else {
                    $skipped++;
                }

                $bar->advance();
            }
        });

        $bar->finish();
        $this->newLine();

        if ($dryRun) {
            $this->warn('DRY RUN — no changes were made to the database.');
        }

        $this->info('');
        $this->info('Summary:');
        $this->info("  Records needing migration: {$migrated}");
        $this->info("  Already encrypted:         {$alreadyEncrypted}");
        $this->info("  No sensitive data (skipped): {$skipped}");

        if ($migrated > 0 && $dryRun) {
            $this->warn('Run without --dry-run to perform the migration.');
        }

        return self::SUCCESS;
    }

    /**
     * Detect if a value is already encrypted by Laravel's Crypt.
     *
     * Laravel encrypted values have the format:
     *   base64:JSON_OBJECT
     * where JSON_OBJECT contains an 'iv' key.
     */
    protected function isEncrypted(string $value): bool
    {
        if (! str_starts_with($value, 'base64:')) {
            return false;
        }

        $decoded = base64_decode(substr($value, 7), true);
        if ($decoded === false) {
            return false;
        }

        $json = json_decode($decoded, true);

        return is_array($json) && isset($json['iv'], $json['value']);
    }

    /**
     * Encrypt a plaintext value using Laravel's encryption.
     */
    protected function encryptValue(string $value): string
    {
        return Crypto::encryptString($value);
    }
}
