<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * FIX #4 — Normalize Location Storage on Campaigns
 *
 * Problems solved:
 *   - campaigns had BOTH:
 *       location     varchar(255)  — free text e.g. "kolkata,India", "Kolkata india" (inconsistent casing)
 *       location_id  FK → locations — normalized table (but locations table was empty!)
 *   - Free text is unsearchable, un-filterable, and inconsistent.
 *   - The locations table must be populated for location_id to be useful.
 *
 * Strategy (two-phase):
 *   Phase A (this migration):
 *     1. Parse the free-text location column into city/state/country parts.
 *     2. Insert normalized rows into the locations table.
 *     3. Set location_id on each campaign.
 *     4. Rename location → location_text and mark it as deprecated (keep data for now).
 *
 *   Phase B (next release, after app is updated):
 *     Run: Schema::table('campaigns', fn($t) => $t->dropColumn('location_text'));
 *
 * App changes needed:
 *   - Remove all references to campaigns.location in queries/models.
 *   - Use campaigns.location_id with an eager-loaded location relationship.
 *   - Add Location model with city, state, country, latitude, longitude.
 */
return new class extends Migration
{
    public function up(): void
    {
        // Step 1: Rename the free-text column to mark it deprecated
        Schema::table('campaigns', function (Blueprint $table) {
            $table->renameColumn('location', 'location_text');
        });

        // Step 2: Parse existing free-text locations and seed the locations table
        $campaigns = DB::table('campaigns')
            ->whereNotNull('location_text')
            ->whereNull('location_id')
            ->get(['id', 'location_text']);

        // Build a deduplicated map of "city,country" → location_id
        $locationCache = [];

        foreach ($campaigns as $campaign) {
            $raw = trim($campaign->location_text);
            $cacheKey = strtolower($raw);

            if (isset($locationCache[$cacheKey])) {
                DB::table('campaigns')
                    ->where('id', $campaign->id)
                    ->update(['location_id' => $locationCache[$cacheKey]]);
                continue;
            }

            // Parse "city,State Country" patterns
            [$city, $countryPart] = array_pad(array_map('trim', explode(',', $raw, 2)), 2, null);

            // Normalise common variations
            $city    = $this->normaliseCity($city);
            $country = $this->normaliseCountry($countryPart ?? 'India');

            $locationId = DB::table('locations')->insertGetId([
                'city'       => $city,
                'state'      => null,        // extend later with a geocoding job
                'country'    => $country,
                'latitude'   => null,
                'longitude'  => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $locationCache[$cacheKey] = $locationId;

            DB::table('campaigns')
                ->where('id', $campaign->id)
                ->update(['location_id' => $locationId]);
        }
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->renameColumn('location_text', 'location');
        });
    }

    // ── helpers ──────────────────────────────────────────────────────────────

    private function normaliseCity(string $city): string
    {
        return ucwords(strtolower(trim($city)));
    }

    private function normaliseCountry(?string $raw): string
    {
        if (!$raw) return 'India';
        $raw = strtolower(trim($raw));
        // Strip leading city word if accidentally included
        $raw = preg_replace('/^[a-z]+\s+/', '', $raw);
        $map = [
            'india'  => 'India',
            'in'     => 'India',
        ];
        return $map[$raw] ?? ucwords($raw);
    }
};
