<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * FIX #3 — Merge Duplicate Contact Tables
 *
 * Problems solved:
 *   - Two near-identical tables existed:
 *       contacts         — had 6 rows of real data, no is_read column
 *       contact_messages — empty, had is_read column
 *   - No FK or link between them. App code inconsistently wrote to one or the other.
 *
 * Strategy:
 *   - Add missing columns (is_read, admin_notes) to `contacts`.
 *   - Migrate any data from contact_messages → contacts (it was empty, but safe to run).
 *   - Drop contact_messages.
 *   - Rename contacts → contact_submissions for clarity (optional but recommended).
 *
 * App changes needed after this migration:
 *   - Update all ContactMessage model references → ContactSubmission (or Contact).
 *   - Update controllers: ContactMessageController → ContactController.
 *   - Update routes accordingly.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Add missing columns to contacts
        Schema::table('contacts', function (Blueprint $table) {
            $table->boolean('is_read')->default(false)->after('message');
            $table->text('admin_notes')->nullable()->after('is_read');
        });

        // Step 2: Migrate any rows from contact_messages → contacts
        // (safe even if table is empty)
        if (Schema::hasTable('contact_messages')) {
            $messages = DB::table('contact_messages')->get();
            foreach ($messages as $msg) {
                DB::table('contacts')->insertOrIgnore([
                    'name'        => $msg->name,
                    'email'       => $msg->email,
                    'subject'     => $msg->subject ?? 'Contact',
                    'message'     => $msg->message,
                    'is_read'     => $msg->is_read ?? false,
                    'created_at'  => $msg->created_at,
                    'updated_at'  => $msg->updated_at,
                ]);
            }

            // Step 3: Drop the duplicate table
            Schema::dropIfExists('contact_messages');
        }
    }

    public function down(): void
    {
        // Re-create contact_messages
        Schema::create('contact_messages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('subject')->nullable();
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn(['is_read', 'admin_notes']);
        });
    }
};
