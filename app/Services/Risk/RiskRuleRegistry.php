<?php

namespace App\Services\Risk;

use App\Models\RiskRule as RiskRuleModel;
use Illuminate\Contracts\Container\Container;

/**
 * Maps a risk_rules.name to a concrete RiskRule instance.
 * New rules are registered here (or auto-discovered); the engine and
 * existing rules are never modified when adding a rule.
 */
final class RiskRuleRegistry
{
    /** @var array<string, RiskRule> */
    private array $rules = [];

    public function __construct(private readonly Container $container) {}

    public function register(string $name, string $class): void
    {
        if (isset($this->rules[$name])) {
            throw new \InvalidArgumentException("Rule '{$name}' is already registered.");
        }

        $this->rules[$name] = $this->container->make($class);
    }

    public function get(string $name): ?RiskRule
    {
        return $this->rules[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return isset($this->rules[$name]);
    }

    /** @return array<string, RiskRule> */
    public function all(): array
    {
        return $this->rules;
    }

    /**
     * Discover enabled rules from the database, ordered by priority.
     *
     * @return RiskRuleModel[]
     */
    public function loadEnabled(): array
    {
        return RiskRuleModel::where('enabled', true)
            ->orderBy('priority')
            ->get()
            ->all();
    }
}
