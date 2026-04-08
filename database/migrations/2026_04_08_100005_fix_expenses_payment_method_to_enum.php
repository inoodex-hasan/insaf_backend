<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Normalize existing data first
        DB::statement("UPDATE expenses SET payment_method = 'bank_transfer' WHERE LOWER(payment_method) IN ('bank transfer', 'banktransfer', 'bank')");
        DB::statement("UPDATE expenses SET payment_method = 'mobile_banking' WHERE LOWER(payment_method) IN ('mobile banking', 'mobilebanking', 'mobile', 'bkash', 'nagad')");
        DB::statement("UPDATE expenses SET payment_method = 'cheque' WHERE LOWER(payment_method) IN ('check', 'cheque')");
        DB::statement("UPDATE expenses SET payment_method = 'cash' WHERE LOWER(payment_method) IN ('cash', 'money')");

        // Change column type to enum
        DB::statement("ALTER TABLE expenses MODIFY COLUMN payment_method ENUM('cash', 'bank_transfer', 'mobile_banking', 'cheque') NULL DEFAULT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to varchar
        DB::statement("ALTER TABLE expenses MODIFY COLUMN payment_method VARCHAR(255) NULL DEFAULT NULL");
    }
};
