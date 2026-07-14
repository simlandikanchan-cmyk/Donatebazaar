<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Consolidate the 4 live `donations` triggers into 3 clean, correct ones.
 *
 * Pre-migration live state (4 triggers):
 *   - trg_donation_raised_amount_insert   (AFTER INSERT, 2026-07-02 migration)
 *   - trg_donation_raised_amount_update   (AFTER UPDATE, 2026-07-02 migration)
 *   - trg_raised_amount_on_donations      (AFTER UPDATE, orphaned 2026-06-04, NOT in any migration)
 *   - trg_raised_amount_on_delete         (AFTER DELETE, orphaned 2026-06-04, NOT in any migration)
 *
 * Bug being fixed:
 *   All four old triggers recompute raised_amount ONLY when a row transitions
 *   TO 'completed'. When a completed donation later moves to 'refunded' or
 *   'failed' (i.e. AWAY from 'completed'), the `NEW.payment_status = 'completed'`
 *   guard is false, so nothing runs and the amount is never decremented.
 *
 *   The 07-02 pair also omitted `deleted_at IS NULL`, so a soft-deleted
 *   donation would be incorrectly summed into raised_amount.
 *
 * New behaviour (3 triggers):
 *   - INSERT: recompute NEW.campaign_id when NEW.payment_status = 'completed'.
 *   - UPDATE: recompute whenever OLD.payment_status != NEW.payment_status
 *             (covers completed->refunded/failed AND failed/pending->completed).
 *             If campaign_id also changes, recompute BOTH old and new campaign.
 *   - DELETE: recompute OLD.campaign_id.
 *   All three filter `deleted_at IS NULL` and use full-recompute (immune to
 *   double counting, equivalent to explicit +/- but safer).
 *
 * down() drops the 3 new triggers and restores the 2 orphaned 06-04 triggers
 * exactly as they were, so a rollback returns the DB to its pre-migration
 * trigger set for those (the 07-02 pair is owned by the 2026-07_02 migration).
 */
return new class extends Migration
{
    public function up(): void
    {
        // 1) Drop all 4 pre-existing triggers (the 07-02 pair + the 2 orphans).
        DB::unprepared('DROP TRIGGER IF EXISTS trg_donation_raised_amount_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_donation_raised_amount_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_raised_amount_on_donations');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_raised_amount_on_delete');

        // 2) AFTER INSERT trigger
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
                          AND d.deleted_at IS NULL
                    )
                    WHERE c.id = NEW.campaign_id;
                END IF;
            END
        ");

        // 3) AFTER UPDATE trigger (handles both directions + campaign_id change)
        DB::unprepared("
            CREATE TRIGGER trg_donation_raised_amount_update
            AFTER UPDATE ON donations
            FOR EACH ROW
            BEGIN
                IF OLD.payment_status != NEW.payment_status THEN
                    UPDATE campaigns c
                    SET c.raised_amount = (
                        SELECT COALESCE(SUM(d.total_amount), 0)
                        FROM donations d
                        WHERE d.campaign_id = c.id
                          AND d.payment_status = 'completed'
                          AND d.deleted_at IS NULL
                    )
                    WHERE c.id = NEW.campaign_id;

                    IF OLD.campaign_id != NEW.campaign_id THEN
                        UPDATE campaigns c
                        SET c.raised_amount = (
                            SELECT COALESCE(SUM(d.total_amount), 0)
                            FROM donations d
                            WHERE d.campaign_id = c.id
                              AND d.payment_status = 'completed'
                              AND d.deleted_at IS NULL
                        )
                        WHERE c.id = OLD.campaign_id;
                    END IF;
                END IF;
            END
        ");

        // 4) AFTER DELETE trigger
        DB::unprepared("
            CREATE TRIGGER trg_donation_raised_amount_delete
            AFTER DELETE ON donations
            FOR EACH ROW
            BEGIN
                UPDATE campaigns c
                SET c.raised_amount = (
                    SELECT COALESCE(SUM(d.total_amount), 0)
                    FROM donations d
                    WHERE d.campaign_id = c.id
                      AND d.payment_status = 'completed'
                      AND d.deleted_at IS NULL
                )
                WHERE c.id = OLD.campaign_id;
            END
        ");
    }

    public function down(): void
    {
        // Drop the 3 new triggers.
        DB::unprepared('DROP TRIGGER IF EXISTS trg_donation_raised_amount_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_donation_raised_amount_update');
        DB::unprepared('DROP TRIGGER IF EXISTS trg_donation_raised_amount_delete');

        // Restore the 2 orphaned 06-04 triggers EXACTLY as they were.
        DB::unprepared("
            CREATE TRIGGER trg_raised_amount_on_donations
            AFTER UPDATE ON donations
            FOR EACH ROW
            BEGIN
                IF NEW.payment_status = 'completed' AND OLD.payment_status != 'completed' THEN
                    UPDATE campaigns
                    SET raised_amount = (
                        SELECT COALESCE(SUM(total_amount), 0)
                        FROM donations
                        WHERE campaign_id = NEW.campaign_id
                          AND payment_status = 'completed'
                          AND deleted_at IS NULL
                    )
                    WHERE id = NEW.campaign_id;
                END IF;
            END
        ");

        DB::unprepared("
            CREATE TRIGGER trg_raised_amount_on_delete
            AFTER DELETE ON donations
            FOR EACH ROW
            BEGIN
                UPDATE campaigns
                SET raised_amount = (
                    SELECT COALESCE(SUM(total_amount), 0)
                    FROM donations
                    WHERE campaign_id = OLD.campaign_id
                      AND payment_status = 'completed'
                      AND deleted_at IS NULL
                )
                WHERE id = OLD.campaign_id;
            END
        ");
    }
};
