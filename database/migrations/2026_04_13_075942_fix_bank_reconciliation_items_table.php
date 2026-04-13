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
        Schema::table('bank_reconciliation_items', function (Blueprint $table) {
            // Drop old foreign key and column
            $table->dropForeign(['journal_entry_id']);
            $table->dropColumn('journal_entry_id');
            
            // Add new column linking to specific item
            $table->foreignId('journal_entry_item_id')->nullable()->constrained('journal_entry_items')->after('reconciliation_id');
        });
    }

    public function down(): void
    {
        Schema::table('bank_reconciliation_items', function (Blueprint $table) {
            $table->dropForeign(['journal_entry_item_id']);
            $table->dropColumn('journal_entry_item_id');
            $table->foreignId('journal_entry_id')->nullable()->constrained('journal_entries')->after('reconciliation_id');
        });
    }
};
