<?php

namespace Tests\Unit\Settlement;

use App\Exceptions\InvalidSettlementTransitionException;
use App\Models\CampaignSettlement;
use App\Models\SettlementStateLog;
use App\Services\Settlement\SettlementStateMachine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SettlementStateMachineTest extends TestCase
{
    use RefreshDatabase;

    private function machine(): SettlementStateMachine
    {
        return new SettlementStateMachine('corr-123', 'trace-456');
    }

    /**
     * Every valid edge from the frozen contract must be allowed.
     */
    public function test_valid_transitions_are_allowed(): void
    {
        $valid = [
            ['requested', 'risk_evaluation'],
            ['requested', 'cancelled'],
            ['risk_evaluation', 'auto_approved'],
            ['risk_evaluation', 'manual_review'],
            ['risk_evaluation', 'rejected'],
            ['auto_approved', 'processing'],
            ['manual_review', 'approved'],
            ['manual_review', 'rejected'],
            ['manual_review', 'cancelled'],
            ['approved', 'processing'],
            ['processing', 'paid'],
            ['processing', 'failed'],
            ['processing', 'retry_pending'],
            ['failed', 'retry_pending'],
            ['failed', 'rejected'],
            ['retry_pending', 'processing'],
        ];

        foreach ($valid as [$from, $to]) {
            $this->assertTrue(
                $this->machine()->canTransition($from, $to),
                "Expected {$from} -> {$to} to be allowed"
            );
        }
    }

    public function test_invalid_transitions_are_rejected_by_can_transition(): void
    {
        $invalid = [
            ['requested', 'paid'],
            ['requested', 'processing'],
            ['risk_evaluation', 'requested'],
            ['auto_approved', 'manual_review'],
            ['manual_review', 'processing'],      // must go through approved
            ['auto_approved', 'approved'],
            ['processing', 'auto_approved'],
            ['paid', 'failed'],
            ['paid', 'processing'],
            ['failed', 'paid'],                   // must re-enter via retry_pending
            ['rejected', 'processing'],
            ['cancelled', 'requested'],
            ['retry_pending', 'paid'],
        ];

        foreach ($invalid as [$from, $to]) {
            $this->assertFalse(
                $this->machine()->canTransition($from, $to),
                "Expected {$from} -> {$to} to be rejected"
            );
        }
    }

    public function test_validate_throws_domain_exception_on_invalid_edge(): void
    {
        $this->expectException(InvalidSettlementTransitionException::class);

        $this->machine()->validate('paid', 'failed');
    }

    public function test_validate_does_not_throw_on_valid_edge(): void
    {
        $this->expectNotToPerformAssertions();

        $this->machine()->validate('requested', 'risk_evaluation');
    }

    public function test_terminal_states_have_no_outgoing_edges(): void
    {
        foreach (['paid', 'rejected', 'cancelled'] as $terminal) {
            $this->assertEmpty(
                $this->machine()->allowedNextStates($terminal),
                "{$terminal} should be terminal"
            );
            $this->assertTrue($this->machine()->isTerminal($terminal));
        }
    }

    public function test_non_terminal_state_is_not_terminal(): void
    {
        $this->assertFalse($this->machine()->isTerminal('processing'));
    }

    public function test_transition_updates_status_and_writes_history(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'status' => 'requested',
            'correlation_id' => 'corr-xyz',
            'trace_id' => 'trace-xyz',
        ]);

        $machine = new SettlementStateMachine('corr-xyz', 'trace-xyz');
        $result = $machine->transition($settlement, 'risk_evaluation', [
            'actor_type' => 'system',
            'reason' => 'risk eval started',
        ]);

        $this->assertSame('risk_evaluation', $result->fresh()->status);

        $log = SettlementStateLog::where('settlement_id', $settlement->id)->first();
        $this->assertNotNull($log);
        $this->assertSame('requested', $log->from_state);
        $this->assertSame('risk_evaluation', $log->to_state);
        $this->assertSame('system', $log->actor_type);
        $this->assertSame('corr-xyz', $log->correlation_id);
        $this->assertSame('trace-xyz', $log->trace_id);
        $this->assertSame('risk eval started', $log->reason);
    }

    public function test_transition_throws_and_does_not_write_history_on_invalid_edge(): void
    {
        $settlement = CampaignSettlement::factory()->create([
            'status' => 'paid',
        ]);

        $before = SettlementStateLog::count();

        try {
            $this->machine()->transition($settlement, 'failed');
            $this->fail('Expected InvalidSettlementTransitionException');
        } catch (InvalidSettlementTransitionException $e) {
            $this->assertSame('paid', $settlement->fresh()->status);
            $this->assertSame($before, SettlementStateLog::count());
        }
    }

    public function test_case_insensitive_states(): void
    {
        $this->assertTrue($this->machine()->canTransition('REQUESTED', 'RISK_EVALUATION'));
    }
}
