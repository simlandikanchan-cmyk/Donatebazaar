<?php

namespace App\Providers;

use App\Services\Risk\RiskRuleRegistry;
use App\Services\Risk\Rules\AmlScreenRule;
use App\Services\Risk\Rules\KycVerifiedRule;
use App\Services\Risk\Rules\LargePayoutAmountRule;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class RiskServiceProvider extends ServiceProvider
{
    /**
     * Map risk_rules.name -> concrete RiskRule class.
     * Adding a rule = add one line here + one risk_rules row. No other
     * engine code changes.
     */
    private const RULE_MAP = [
        'KYC_VERIFIED' => KycVerifiedRule::class,
        'LARGE_PAYOUT_AMOUNT' => LargePayoutAmountRule::class,
        'AML_SCREEN' => AmlScreenRule::class,
    ];

    public function register(): void
    {
        $this->app->singleton(RiskRuleRegistry::class, function (Application $app) {
            $registry = new RiskRuleRegistry($app);

            foreach (self::RULE_MAP as $name => $class) {
                $registry->register($name, $class);
            }

            return $registry;
        });
    }
}
