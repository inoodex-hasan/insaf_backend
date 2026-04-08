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
        // Applications table
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['university_id']);
            $table->dropForeign(['course_intake_id']);
            
            $table->foreign('course_id')
                ->references('id')
                ->on('courses')
                ->restrictOnDelete();
                
            $table->foreign('university_id')
                ->references('id')
                ->on('universities')
                ->restrictOnDelete();
                
            $table->foreign('course_intake_id')
                ->references('id')
                ->on('course_intakes')
                ->restrictOnDelete();
        });

        // Journal entries - block deletion if user created entries
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
        });

        // Leads - keep reference when user deleted
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['consultant_id']);
            
            $table->foreign('created_by')
                ->references('id')
                ->on('users')
                ->restrictOnDelete();
                
            $table->foreign('consultant_id')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });

        // Invoices - keep invoice history when student/university deleted
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['university_id']);
            
            $table->foreign('student_id')
                ->references('id')
                ->on('students')
                ->restrictOnDelete();
                
            $table->foreign('university_id')
                ->references('id')
                ->on('universities')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Applications
        Schema::table('applications', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['university_id']);
            $table->dropForeign(['course_intake_id']);
            
            $table->foreign('course_id')->references('id')->on('courses')->cascadeOnDelete();
            $table->foreign('university_id')->references('id')->on('universities')->cascadeOnDelete();
            $table->foreign('course_intake_id')->references('id')->on('course_intakes')->cascadeOnDelete();
        });

        // Journal entries
        Schema::table('journal_entries', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
        });

        // Leads
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropForeign(['consultant_id']);
            
            $table->foreign('created_by')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('consultant_id')->references('id')->on('users')->cascadeOnDelete();
        });

        // Invoices
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['student_id']);
            $table->dropForeign(['university_id']);
            
            $table->foreign('student_id')->references('id')->on('students')->cascadeOnDelete();
            $table->foreign('university_id')->references('id')->on('universities')->cascadeOnDelete();
        });
    }
};
