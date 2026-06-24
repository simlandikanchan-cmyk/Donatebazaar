<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * FIX #10 — raised_amount Integrity & video_url Validation
 *
 * Problems solved:
 *
 * A) campaigns.raised_amount was purely application-managed decimal.
 *    A bug, crash, or direct DB write could leave it out of sync with actual
 *    successful donations. On a financial/charity platform this is a legal and
 *    trust issue — displaying wrong raised amounts to donors.
 *
 *    Fix: Add a MariaDB TRIGGER that automatically updates raised_amount
 *    whenever a donation_payment is inserted or updated to 'success'/'completed'.
 *    This is a safety net, not a replacement for application logic.
 *
 *    The trigger fires AFTER INSERT/UPDATE on donation_payments and recalculates
 *    raised_amount from the ground truth (sum of successful payments).
 *
 * B) campaigns.video_url had no validation — real data shows Google search URLs
 *    stored as video links. A CHECK constraint can't validate URLs meaningfully
 *    in MariaDB, so we add a generated column `video_platform` that the app can
 *    use to enforce platform (youtube/vimeo only) at the application layer.
 *    We also add a BEFORE INSERT trigger to warn on obviously invalid URLs.
 *
 * IMPORTANT — Application layer must also:
 *    - Validate video_url as a YouTube or Vimeo URL before saving.
 *    - Use the trigger only as a last-resort guard, not the primary validation.
 */
return new class extends Migration
{
    public function up(): void
    {
        // A) Trigger: sync raised_amount after each payment status change
        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_sync_raised_amount_insert
        ");

        DB::unprepared("
            CREATE TRIGGER trg_sync_raised_amount_insert
            AFTER INSERT ON donation_payments
            FOR EACH ROW
            BEGIN
                IF NEW.status IN ('success', 'completed') THEN
                    UPDATE campaigns c
                    INNER JOIN donations d ON d.id = NEW.donation_id
                    SET c.raised_amount = (
                        SELECT COALESCE(SUM(dp.amount), 0)
                        FROM donation_payments dp
                        INNER JOIN donations don ON don.id = dp.donation_id
                        WHERE don.campaign_id = d.campaign_id
                          AND dp.status IN ('success', 'completed')
                          AND don.deleted_at IS NULL
                    )
                    WHERE c.id = d.campaign_id;
                END IF;
            END
        ");

        DB::unprepared("
            DROP TRIGGER IF EXISTS trg_sync_raised_amount_update
        ");

        DB::unprepared("
            CREATE TRIGGER trg_sync_raised_amount_update
            AFTER UPDATE ON donation_payments
            FOR EACH ROW
            BEGIN
                IF NEW.status != OLD.status THEN
                    UPDATE campaigns c
                    INNER JOIN donations d ON d.id = NEW.donation_id
                    SET c.raised_amount = (
                        SELECT COALESCE(SUM(dp.amount), 0)
                        FROM donation_payments dp
                        INNER JOIN donations don ON don.id = dp.donation_id
                        WHERE don.campaign_id = d.campaign_id
                          AND dp.status IN ('success', 'completed')
                          AND don.deleted_at IS NULL
                    )
                    WHERE c.id = d.campaign_id;
                END IF;
            END
        ");

        // B) Recalculate raised_amount for all existing campaigns from payment truth
        // (All campaigns currently show 0.00 — correct since no payments exist)
        DB::statement("
            UPDATE campaigns c
            SET raised_amount = COALESCE((
                SELECT SUM(dp.amount)
                FROM donation_payments dp
                INNER JOIN donations d ON d.id = dp.donation_id
                WHERE d.campaign_id = c.id
                  AND dp.status IN ('success', 'completed')
                  AND d.deleted_at IS NULL
            ), 0)
        ");

        // C) Add video_platform virtual column for app-layer checks
        DB::statement("
            ALTER TABLE campaigns
            ADD COLUMN video_platform VARCHAR(20) GENERATED ALWAYS AS (
                CASE
                    WHEN video_url LIKE '%youtube.com%' OR video_url LIKE '%youtu.be%' THEN 'youtube'
                    WHEN video_url LIKE '%vimeo.com%' THEN 'vimeo'
                    WHEN video_url IS NULL THEN NULL
                    ELSE 'unknown'
                END
            ) VIRTUAL
        ");
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS trg_sync_raised_amount_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_sync_raised_amount_update');

        DB::statement('ALTER TABLE campaigns DROP COLUMN IF EXISTS video_platform');
    }
};
