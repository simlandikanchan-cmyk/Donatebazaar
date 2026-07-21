<?php

namespace Tests\Unit\Risk;

use App\Models\RiskRule;
use App\Services\Risk\Context\RiskContext;
use App\Services\Risk\RiskRuleRegistry;
use App\Services\Risk\RiskRuleResult;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RiskRuleRegistryTest extends TestCase
{
    use RefreshDatabase;

    private function registry(): RiskRuleRegistry
    {
        return new RiskRuleRegistry(app());
    }

    public function test_register_stores_rule_by_name(): void
    {
        $registry = $this->registry();
        $registry->register('TEST_RULE', FakeRule::class);

        $this->assertTrue($registry->has('TEST_RULE'));
        $this->assertInstanceOf(FakeRule::class, $registry->get('TEST_RULE'));
    }

    public function test_register_throws_on_duplicate(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $registry = $this->registry();
        $registry->register('TEST_RULE', FakeRule::class);
        $registry->register('TEST_RULE', FakeRule::class);
    }

    public function test_get_returns_null_for_unknown_rule(): void
    {
        $registry = $this->registry();

        $this->assertNull($registry->get('UNKNOWN_RULE'));
    }

    public function test_has_returns_false_for_unknown_rule(): void
    {
        $registry = $this->registry();

        $this->assertFalse($registry->has('UNKNOWN_RULE'));
    }

    public function test_all_returns_all_registered_rules(): void
    {
        $registry = $this->registry();
        $registry->register('RULE_A', FakeRule::class);
        $registry->register('RULE_B', AnotherFakeRule::class);

        $all = $registry->all();

        $this->assertCount(2, $all);
        $this->assertArrayHasKey('RULE_A', $all);
        $this->assertArrayHasKey('RULE_B', $all);
    }

    public function test_register_allows_same_class_different_name(): void
    {
        $registry = $this->registry();
        $registry->register('RULE_A', FakeRule::class);
        $registry->register('RULE_B', FakeRule::class);

        $this->assertCount(2, $registry->all());
    }

    public function test_load_enabled_returns_ordered_rule_models(): void
    {
        RiskRule::create([
            'name' => 'LOW_PRIORITY',
            'category' => 'KYC',
            'weight' => 10,
            'priority' => 10,
            'enabled' => true,
        ]);
        RiskRule::create([
            'name' => 'HIGH_PRIORITY',
            'category' => 'COMPLIANCE',
            'weight' => 20,
            'priority' => 5,
            'enabled' => true,
        ]);
        RiskRule::create([
            'name' => 'DISABLED_RULE',
            'category' => 'FRAUD',
            'weight' => 30,
            'priority' => 1,
            'enabled' => false,
        ]);

        $registry = $this->registry();
        $enabled = $registry->loadEnabled();

        $this->assertCount(2, $enabled);
        $this->assertSame('HIGH_PRIORITY', $enabled[0]->name);
        $this->assertSame('LOW_PRIORITY', $enabled[1]->name);
    }

    public function test_load_enabled_excludes_disabled_rules(): void
    {
        RiskRule::create([
            'name' => 'ENABLED_RULE',
            'category' => 'KYC',
            'weight' => 10,
            'priority' => 1,
            'enabled' => true,
        ]);
        RiskRule::create([
            'name' => 'DISABLED_RULE',
            'category' => 'KYC',
            'weight' => 10,
            'priority' => 2,
            'enabled' => false,
        ]);

        $registry = $this->registry();
        $enabled = $registry->loadEnabled();

        $this->assertCount(1, $enabled);
        $this->assertSame('ENABLED_RULE', $enabled[0]->name);
    }
}

final class FakeRule implements \App\Services\Risk\RiskRule
{
    public function identifier(): string
    {
        return 'FAKE_RULE';
    }

    public function evaluate(RiskContext $context): RiskRuleResult
    {
        return RiskRuleResult::notTriggered();
    }

    public function name(): string
    {
        return $this->identifier();
    }
}

final class AnotherFakeRule implements \App\Services\Risk\RiskRule
{
    public function identifier(): string
    {
        return 'ANOTHER_FAKE_RULE';
    }

    public function evaluate(RiskContext $context): RiskRuleResult
    {
        return RiskRuleResult::notTriggered();
    }

    public function name(): string
    {
        return $this->identifier();
    }
}
