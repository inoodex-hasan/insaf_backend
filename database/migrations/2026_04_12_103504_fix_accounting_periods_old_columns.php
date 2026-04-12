<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accounting_periods', function (Blueprint $table) {
            // Make old columns nullable or drop them
            if (Schema::hasColumn('accounting_periods', 'year')) {
                $table->year('year')->nullable()->change();
            }
            if (Schema::hasColumn('accounting_periods', 'month')) {
                $table->unsignedTinyInteger('month')->nullable()->change();
            }
            if (Schema::hasColumn('accounting_periods', 'is_closed')) {
                $table->boolean('is_closed')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_periods', function (Blueprint $table) {
            if (Schema::hasColumn('accounting_periods', 'year')) {
                $table->year('year')->nullable(false)->change();
            }
            if (Schema::hasColumn('accounting_periods', 'month')) {
                $table->unsignedTinyInteger('month')->nullable(false)->change();
            }
            if (Schema::hasColumn('accounting_periods', 'is_closed')) {
                $table->boolean('is_closed')->default(false)->change();
            }
        });
    }
};
