<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old triggers that watched the wrong table (donation_payments)
        DB::unprepared('DROP TRIGGER IF EXISTS trg_sync_raised_amount_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_sync_raised_amount_update');

        // New trigger: AFTER INSERT on donations
        DB::unprepared("
            CREATE TRIGGER trg_donation_raised_amount_insert
            AFTER INSERT ON donations
            FOR EACH ROW
            BEGIN
                IF NEW.payment_status = 'completed' THEN
                    UPDATE campaigns c
                    SET c.raised_amount = (
                        SELECT COALESCE(SUM(d.total_amount), 0)
                        FROM donations d
                        WHERE d.campaign_id = c.id
                          AND d.payment_status = 'completed'
                    )
                    WHERE c.id = NEW.campaign_id;
                END IF;
            END
        ");

        // New trigger: AFTER UPDATE on donations (when payment_status changes)
        DB::unprepared("
            CREATE TRIGGER trg_donation_raised_amount_update
            AFTER UPDATE ON donations
            FOR EACH ROW
            BEGIN
                IF NEW.payment_status != OLD.payment_status AND NEW.payment_status = 'completed' THEN
                    UPDATE campaigns c
                    SET c.raised_amount = (
                        SELECT COALESCE(SUM(d.total_amount), 0)
                        FROM donations d
                        WHERE d.campaign_id = c.id
                          AND d.payment_status = 'completed'
                    )
                    WHERE c.id = NEW.campaign_id;
                END IF;
            END
        ");

        // Recalculate raised_amount for all campaigns from donations truth
        DB::statement("
            UPDATE campaigns c
            SET c.raised_amount = COALESCE((
                SELECT SUM(d.total_amount)
                FROM donations d
                WHERE d.campaign_id = c.id
                  AND d.payment_status = 'completed'
            ), 0)
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_donation_raised_amount_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_donation_raised_amount_update');
    }
};
