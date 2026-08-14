<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            if (! Schema::hasColumn('organizations', 'slug')) {
                $table->string('slug', 255)->unique()->nullable()->after('name');
            }
            if (! Schema::hasColumn('organizations', 'description')) {
                $table->text('description')->nullable()->after('slug');
            }
            if (! Schema::hasColumn('organizations', 'logo')) {
                $table->string('logo', 255)->nullable()->after('description');
            }
            if (! Schema::hasColumn('organizations', 'contact_email')) {
                $table->string('contact_email', 255)->nullable()->after('logo');
            }
            if (! Schema::hasColumn('organizations', 'contact_phone')) {
                $table->string('contact_phone', 255)->nullable()->after('contact_email');
            }
            if (! Schema::hasColumn('organizations', 'registration_number')) {
                $table->string('registration_number', 255)->nullable()->after('contact_phone');
            }
            if (! Schema::hasColumn('organizations', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('registration_number');
            }
            if (! Schema::hasColumn('organizations', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('is_active');
            }
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropUnique('organizations_slug_unique');
            $table->dropColumn([
                'slug',
                'description',
                'logo',
                'contact_email',
                'contact_phone',
                'registration_number',
                'is_active',
                'verified_at',
            ]);
        });
    }
};
