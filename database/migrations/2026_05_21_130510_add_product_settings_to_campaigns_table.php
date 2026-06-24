<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migrations.
     */
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {

            if (! Schema::hasColumn('campaigns', 'enable_products')) {

                $table->boolean('enable_products')
                    ->default(true)
                    ->after('is_urgent');
            }

            if (! Schema::hasColumn('campaigns', 'allow_custom_products')) {

                $table->boolean('allow_custom_products')
                    ->default(true)
                    ->after('enable_products');
            }

        });
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {

            if (Schema::hasColumn('campaigns', 'enable_products')) {

                $table->dropColumn('enable_products');
            }

            if (Schema::hasColumn('campaigns', 'allow_custom_products')) {

                $table->dropColumn('allow_custom_products');
            }

        });
    }
};