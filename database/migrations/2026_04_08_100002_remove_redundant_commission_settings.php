<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Remove redundant commission percentage settings
        // Single source of truth is now users.commission_percentage
        DB::table('settings')->whereIn('key', [
            'commission_marketing_percent',
            'commission_consultant_percent',
            'commission_application_percent',
            'commission_accountant_percent',
        ])->delete();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Data cannot be automatically restored
        // Settings would need to be manually recreated if needed
    }
};
