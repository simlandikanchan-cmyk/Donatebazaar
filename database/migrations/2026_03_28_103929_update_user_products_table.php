<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('user_products', function (Blueprint $table) {
        $table->text('description')->nullable()->after('name');
        $table->string('image')->nullable();

        $table->text('rejection_reason')->nullable();
        $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
