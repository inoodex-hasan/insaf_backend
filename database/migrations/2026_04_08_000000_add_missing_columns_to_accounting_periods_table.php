<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('accounting_periods', function (Blueprint $table) {
            $table->string('name', 100)->nullable()->after('id');
            $table->date('start_date')->nullable()->after('month');
            $table->date('end_date')->nullable()->after('start_date');
            $table->enum('type', ['fiscal_year', 'monthly', 'quarterly'])->default('monthly')->after('end_date');
            $table->enum('status', ['open', 'closed'])->default('open')->after('type');
            $table->text('remarks')->nullable()->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounting_periods', function (Blueprint $table) {
            $table->dropColumn(['name', 'start_date', 'end_date', 'type', 'status', 'remarks']);
        });
    }
};
