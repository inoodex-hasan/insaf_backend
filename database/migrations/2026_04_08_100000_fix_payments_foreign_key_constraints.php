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
        // Fix dangerous CASCADE delete on payments.collected_by
        // If a user is deleted, their collected payments should remain with NULL collector
        Schema::table('payments', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['collected_by']);
            
            // Recreate with SET NULL instead of CASCADE
            $table->foreignId('collected_by')->nullable()->change();
            $table->foreign('collected_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
        
        // Also fix student_id cascade - student records should not be deleted if they have payments
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Revert to cascade delete
            $table->dropForeign(['collected_by']);
            $table->foreign('collected_by')
                ->references('id')
                ->on('users')
                ->cascadeOnDelete();
            
            $table->dropForeign(['student_id']);
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->cascadeOnDelete();
        });
    }
};
