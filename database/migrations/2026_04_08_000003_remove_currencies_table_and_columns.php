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
        // Step 1: Remove currency_id foreign keys and columns from tables
        
        // Journal entries
        if (Schema::hasColumn('journal_entries', 'currency_id')) {
            Schema::table('journal_entries', function (Blueprint $table) {
                $table->dropForeign(['currency_id']);
                $table->dropColumn('currency_id');
            });
        }

        // Journal entry items
        if (Schema::hasColumn('journal_entry_items', 'currency_id')) {
            Schema::table('journal_entry_items', function (Blueprint $table) {
                $table->dropForeign(['currency_id']);
                $table->dropColumn(['currency_id', 'exchange_rate_at_posting', 'base_currency_amount']);
            });
        }

        // Invoices
        if (Schema::hasColumn('invoices', 'currency_id')) {
            Schema::table('invoices', function (Blueprint $table) {
                $table->dropForeign(['currency_id']);
                $table->dropColumn('currency_id');
            });
        }

        // Bank reconciliations
        if (Schema::hasColumn('bank_reconciliations', 'currency_id')) {
            Schema::table('bank_reconciliations', function (Blueprint $table) {
                $table->dropForeign(['currency_id']);
                $table->dropColumn('currency_id');
            });
        }

        // Courses - remove currency column
        if (Schema::hasColumn('courses', 'currency')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->dropColumn('currency');
            });
        }

        // Applications - remove currency column
        if (Schema::hasColumn('applications', 'currency')) {
            Schema::table('applications', function (Blueprint $table) {
                $table->dropColumn('currency');
            });
        }

        // Step 2: Drop the currencies table
        Schema::dropIfExists('currencies');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate currencies table
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('symbol', 10)->nullable();
            $table->decimal('exchange_rate', 15, 6)->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamp('last_updated_at')->nullable();
            $table->timestamps();
        });

        // Note: Adding back columns to existing tables would require manual data restoration
    }
};
