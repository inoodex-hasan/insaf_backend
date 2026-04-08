<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accounting_periods', function (Blueprint $table) {
            // Drop the existing unique constraint on (year, month)
            $table->dropUnique(['year', 'month']);
            
            // Add a new unique constraint on (year, month, type)
            // This allows monthly and quarterly periods to coexist for the same year/month
            $table->unique(['year', 'month', 'type'], 'accounting_periods_year_month_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_periods', function (Blueprint $table) {
            // Revert to the original constraint
            $table->dropUnique('accounting_periods_year_month_type_unique');
            $table->unique(['year', 'month']);
        });
    }
};
