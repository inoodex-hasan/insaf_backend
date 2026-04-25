<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Commission;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CommissionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('*accountant');

        $query = Commission::with(['application.student', 'user', 'reviewer']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('application', fn($aq) => $aq->where('application_id', 'like', "%{$search}%"))
                    ->orWhereHas('application.student', fn($sq) => $sq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('workflow_status')) {
            $query->where('workflow_status', $request->workflow_status);
        }

        $commissions = $query->latest()->paginate(15)->withQueryString();

        // Get statistics for dashboard
        $stats = [
            'pending_review' => Commission::pendingReview()->count(),
            'claimed' => Commission::claimed()->count(),
            'under_review' => Commission::underReview()->count(),
            'approved' => Commission::approved()->count(),
            'rejected' => Commission::rejected()->count(),
            'paid' => Commission::paid()->count(),
        ];

        return view('admin.accounts.commissions.index', compact('commissions', 'stats'));
    }

    /**
     * Show pending claims for review
     */
    public function pendingClaims(Request $request)
    {
        $this->authorize('*accountant');

        $query = Commission::with(['application.student', 'user'])
            ->pendingReview()
            ->latest();

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('application', fn($aq) => $aq->where('application_id', 'like', "%{$search}%"))
                    ->orWhereHas('application.student', fn($sq) => $sq->where('full_name', 'like', "%{$search}%"));
            });
        }

        // Role filter
        if ($request->filled('role')) {
            $role = $request->role;
            $query->whereHas('application.student', function ($q) use ($role) {
                match ($role) {
                    'marketing' => $q->whereColumn('assigned_marketing_id', 'commissions.user_id'),
                    'consultant' => $q->whereColumn('assigned_consultant_id', 'commissions.user_id'),
                    'application' => $q->whereColumn('assigned_application_id', 'commissions.user_id'),
                    'creator' => $q->whereColumn('created_by', 'commissions.user_id'),
                    default => $q
                };
            });
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('claimed_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('claimed_at', '<=', $request->date_to);
        }

        // Amount range filter
        if ($request->filled('amount_min')) {
            $query->where('proposed_amount', '>=', $request->amount_min);
        }
        if ($request->filled('amount_max')) {
            $query->where('proposed_amount', '<=', $request->amount_max);
        }

        // Status filter (claimed vs under_review)
        if ($request->filled('workflow_status')) {
            $query->where('workflow_status', $request->workflow_status);
        }

        $commissions = $query->paginate(15)->withQueryString();

        return view('admin.accounts.commissions.pending', compact('commissions'));
    }

    public function create()
    {
        $this->authorize('*accountant');
        return view('admin.accounts.commissions.create');
    }

    public function storeStandalone(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid',
            'notes' => 'nullable|string|max:1000',
        ]);

        Commission::create($validated);

        return redirect()->route('admin.commissions.index')->with('success', 'Commission created successfully.');
    }

    public function store(Request $request, Application $application)
    {
        $this->authorize('*accountant');

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'notes' => 'nullable|string|max:1000',
        ]);

        $validated['application_id'] = $application->id;
        $validated['status'] = 'pending';

        Commission::updateOrCreate(
            ['application_id' => $application->id, 'user_id' => $validated['user_id']],
            $validated
        );

        return redirect()->back()->with('success', 'Commission saved successfully.');
    }

    /**
     * Show claim review form
     */
    public function showForReview(Commission $commission)
    {
        $this->authorize('*accountant');

        if (!$commission->canBeReviewed()) {
            return redirect()->route('admin.commissions.index')
                ->with('error', 'This claim is not available for review.');
        }

        $commission->load(['application.student', 'application.university', 'application.course', 'user']);

        // Get other claims on same application for reference
        $otherClaims = Commission::forApplication($commission->application_id)
            ->where('id', '!=', $commission->id)
            ->with(['user', 'reviewer'])
            ->get();

        return view('admin.accounts.commissions.review', compact('commission', 'otherClaims'));
    }

    /**
     * Approve a commission claim
     */
    public function approve(Request $request, Commission $commission)
    {
        $this->authorize('*accountant');

        if (!$commission->canBeReviewed()) {
            return redirect()->route('admin.commissions.pending')
                ->with('error', 'This claim is not available for review.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'percentage' => 'nullable|numeric|min:0|max:100',
            'review_notes' => 'nullable|string|max:2000',
        ]);

        $commission->update([
            'amount' => $validated['amount'],
            'percentage' => $validated['percentage'],
            'workflow_status' => Commission::STATUS_APPROVED,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $validated['review_notes'] ?? null,
        ]);

        // TODO: Notify employee about approval

        return redirect()->route('admin.commissions.pending')
            ->with('success', 'Commission claim approved successfully. Ready for payment.');
    }

    /**
     * Reject a commission claim
     */
    public function reject(Request $request, Commission $commission)
    {
        $this->authorize('*accountant');

        if (!$commission->canBeReviewed()) {
            return redirect()->route('admin.commissions.pending')
                ->with('error', 'This claim is not available for review.');
        }

        $validated = $request->validate([
            'review_notes' => 'required|string|max:2000',
        ]);

        $commission->update([
            'workflow_status' => Commission::STATUS_REJECTED,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $validated['review_notes'],
        ]);

        // TODO: Notify employee about rejection

        return redirect()->route('admin.commissions.pending')
            ->with('success', 'Commission claim rejected. Employee can resubmit with corrections.');
    }

    /**
     * Mark approved commission as paid
     */
    public function markAsPaid(Commission $commission)
    {
        $this->authorize('*accountant');

        if (!$commission->canBePaid()) {
            return redirect()->route('admin.commissions.index')
                ->with('error', 'Only approved commissions can be marked as paid.');
        }

        $commission->update([
            'workflow_status' => Commission::STATUS_PAID,
        ]);

        // TODO: Create journal entry for commission payment

        return redirect()->route('admin.commissions.index')
            ->with('success', 'Commission marked as paid successfully.');
    }

    /**
     * Bulk approve claims
     */
    public function bulkApprove(Request $request)
    {
        $this->authorize('*accountant');

        $validated = $request->validate([
            'commissions' => 'required|array',
            'commissions.*' => 'exists:commissions,id',
        ]);

        $commissions = Commission::whereIn('id', $validated['commissions'])
            ->whereIn('workflow_status', [Commission::STATUS_CLAIMED, Commission::STATUS_UNDER_REVIEW])
            ->get();

        $approvedCount = 0;
        foreach ($commissions as $commission) {
            // If no amount set, use proposed amount
            $amount = $commission->amount > 0 ? $commission->amount : $commission->proposed_amount;

            $commission->update([
                'amount' => $amount,
                'workflow_status' => Commission::STATUS_APPROVED,
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);
            $approvedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => "{$approvedCount} commission(s) approved successfully.",
        ]);
    }

    /**
     * Legacy status update (for backward compatibility)
     */
    public function updateStatus(Request $request, Commission $commission)
    {
        $this->authorize('*accountant');

        $validated = $request->validate([
            'status' => 'required|in:pending,paid',
        ]);

        // Map old status to new workflow
        $workflowStatus = $validated['status'] === 'paid' ? Commission::STATUS_PAID : Commission::STATUS_APPROVED;

        $commission->update([
            'status' => $validated['status'],
            'workflow_status' => $workflowStatus,
        ]);

        return response()->json([
            'success' => true,
            'status' => $commission->status,
            'message' => 'Commission status updated successfully.',
        ]);
    }

    public function destroy(Commission $commission)
    {
        $this->authorize('*accountant');

        return $this->safeDelete($commission, 'admin.commissions.index', [], 'Commission removed.');
    }
}
