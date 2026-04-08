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
        Schema::table('leads', function (Blueprint $table) {
            // Drop existing varchar columns
            $table->dropColumn(['preferred_country', 'preferred_course']);
        });

        Schema::table('leads', function (Blueprint $table) {
            // Add proper foreign key columns
            $table->foreignId('preferred_country')->nullable()->after('current_education')->constrained('countries')->nullOnDelete();
            $table->foreignId('preferred_course')->nullable()->after('preferred_country')->constrained('courses')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            // Drop foreign keys first
            $table->dropForeign(['preferred_country']);
            $table->dropForeign(['preferred_course']);
            
            // Drop the columns
            $table->dropColumn(['preferred_country', 'preferred_course']);
        });

        Schema::table('leads', function (Blueprint $table) {
            // Revert to varchar
            $table->string('preferred_country')->nullable()->after('current_education');
            $table->string('preferred_course')->nullable()->after('preferred_country');
        });
    }
};
