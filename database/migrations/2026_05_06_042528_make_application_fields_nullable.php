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
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('university_id')->nullable()->change();
            $table->foreignId('course_id')->nullable()->change();
            $table->foreignId('course_intake_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->foreignId('university_id')->nullable(false)->change();
            $table->foreignId('course_id')->nullable(false)->change();
            $table->foreignId('course_intake_id')->nullable(false)->change();
        });
    }
};
