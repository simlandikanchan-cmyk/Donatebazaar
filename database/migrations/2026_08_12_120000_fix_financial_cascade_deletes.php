<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Financial integrity: change unsafe CASCADE deletes to RESTRICT
 * on financial history tables.
 *
 * CRITICAL: campaign_settlements must not disappear when a campaign
 * or organization is deleted — these records represent settled funds
 * and must be retained for audit, reconciliation, and dispute purposes.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Note: DDL statements (ALTER TABLE DROP/ADD FK) cannot run inside
        // a DB transaction on MySQL/MariaDB, so we execute them individually.

        $this->changeFkToRestrict(
            'campaign_settlements',
            'campaign_settlements_campaign_id_foreign',
            'campaign_id',
            'campaigns'
        );

        $this->changeFkToRestrict(
            'campaign_settlements',
            'campaign_settlements_organization_id_foreign',
            'organization_id',
            'organizations'
        );

        $this->changeFkToRestrict(
            'settlement_items',
            'settlement_items_campaign_settlement_id_foreign',
            'campaign_settlement_id',
            'campaign_settlements'
        );

        $this->changeFkToRestrict(
            'settlement_items',
            'settlement_items_donation_id_foreign',
            'donation_id',
            'donations'
        );

        $this->changeFkToRestrict(
            'refunds',
            'refunds_donation_id_foreign',
            'donation_id',
            'donations'
        );

        $this->changeFkToRestrict(
            'refunds',
            'refunds_donation_payment_id_foreign',
            'donation_payment_id',
            'donation_payments'
        );

        $this->changeFkToRestrict(
            'payout_attempts',
            'payout_attempts_settlement_id_foreign',
            'settlement_id',
            'campaign_settlements'
        );

        $this->changeFkToRestrict(
            'donation_payments',
            'donation_payments_donation_id_foreign',
            'donation_id',
            'donations'
        );

        $this->changeFkToRestrict(
            'payout_accounts',
            'payout_accounts_organization_id_foreign',
            'organization_id',
            'organizations'
        );

        /*
         * wallets → users
         *
         * Kept as CASCADE: a wallet is a mutable balance ledger tied 1:1
         * to a user, NOT an individual audit trail. wallet_transactions
         * is the immutable audit record. When a user is hard-deleted,
         * their wallet is recreated on-demand via getOrCreateWallet().
         */
    }

    /**
     * Drop a CASCADE/dependent FK and re-add it as RESTRICT.
     */
    private function changeFkToRestrict(
        string $table,
        string $fkName,
        string $column,
        string $refTable
    ): void {
        // Drop existing FK
        DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `$fkName`");

        // Re-add as RESTRICT
        DB::statement("ALTER TABLE `$table` ADD CONSTRAINT `$fkName` FOREIGN KEY (`$column`) REFERENCES `$refTable` (`id`) ON DELETE RESTRICT");
    }

    public function down(): void
    {
        $this->restoreCascade('campaign_settlements', 'campaign_settlements_campaign_id_foreign', 'campaign_id', 'campaigns');
        $this->restoreCascade('campaign_settlements', 'campaign_settlements_organization_id_foreign', 'organization_id', 'organizations');
        $this->restoreCascade('settlement_items', 'settlement_items_campaign_settlement_id_foreign', 'campaign_settlement_id', 'campaign_settlements');
        $this->restoreCascade('settlement_items', 'settlement_items_donation_id_foreign', 'donation_id', 'donations');
        $this->restoreCascade('refunds', 'refunds_donation_id_foreign', 'donation_id', 'donations');
        $this->restoreCascade('refunds', 'refunds_donation_payment_id_foreign', 'donation_payment_id', 'donation_payments');
        $this->restoreCascade('payout_attempts', 'payout_attempts_settlement_id_foreign', 'settlement_id', 'campaign_settlements');
        $this->restoreCascade('donation_payments', 'donation_payments_donation_id_foreign', 'donation_id', 'donations');
        $this->restoreCascade('payout_accounts', 'payout_accounts_organization_id_foreign', 'organization_id', 'organizations');
        // wallets → users intentionally left as CASCADE (not changed)
    }

    private function restoreCascade(string $table, string $fkName, string $column, string $refTable): void
    {
        DB::statement("ALTER TABLE `$table` DROP FOREIGN KEY `$fkName`");
        DB::statement("ALTER TABLE `$table` ADD CONSTRAINT `$fkName` FOREIGN KEY (`$column`) REFERENCES `$refTable` (`id`) ON DELETE CASCADE");
    }
};
