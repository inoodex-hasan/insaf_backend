<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            // Workflow status replaces simple status
            $table->string('workflow_status')->default('draft')->after('status'); // draft, claimed, under_review, approved, rejected, paid
            
            // Claim details
            $table->timestamp('claimed_at')->nullable()->after('workflow_status');
            $table->text('claim_notes')->nullable()->after('claimed_at');
            
            // Review details
            $table->foreignId('reviewed_by')->nullable()->after('claim_notes')->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable()->after('reviewed_by');
            $table->text('review_notes')->nullable()->after('reviewed_at');
            
            // Original proposed amount (for audit)
            $table->decimal('proposed_amount', 15, 2)->nullable()->after('amount');
            
            // Keep old status for backward compatibility - will be removed later
            // $table->dropColumn('status');
        });
    }

    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->dropColumn([
                'workflow_status',
                'claimed_at',
                'claim_notes',
                'reviewed_by',
                'reviewed_at',
                'review_notes',
                'proposed_amount',
            ]);
        });
    }
};
