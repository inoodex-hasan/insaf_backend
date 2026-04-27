<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Commission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MyCommissionController extends Controller
{
    /**
     * Display my commissions dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get statistics
        $stats = [
            'draft' => Commission::forUser($user->id)->draft()->count(),
            'claimed' => Commission::forUser($user->id)->claimed()->count(),
            'under_review' => Commission::forUser($user->id)->underReview()->count(),
            'approved' => Commission::forUser($user->id)->approved()->count(),
            'rejected' => Commission::forUser($user->id)->rejected()->count(),
            'paid' => Commission::forUser($user->id)->paid()->count(),
            'total_claimed_amount' => Commission::forUser($user->id)
                ->whereIn('workflow_status', [Commission::STATUS_CLAIMED, Commission::STATUS_UNDER_REVIEW])
                ->sum('proposed_amount'),
            'total_approved_amount' => Commission::forUser($user->id)
                ->whereIn('workflow_status', [Commission::STATUS_APPROVED, Commission::STATUS_PAID])
                ->sum('amount'),
            'total_paid_amount' => Commission::forUser($user->id)->paid()->sum('amount'),
        ];

        // Build query for commissions list
        $query = Commission::with(['application.student', 'application.university', 'application.course'])
            ->forUser($user->id);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('workflow_status', $request->status);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('application', fn($aq) => $aq->where('application_id', 'like', "%{$search}%"))
                    ->orWhereHas('application.student', fn($sq) => $sq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%"));
            });
        }

        $commissions = $query->latest()->paginate(15)->withQueryString();

        return view('admin.commissions.my-commissions', compact('commissions', 'stats'));
    }

    /**
     * Show claimable applications (applications I can claim commission on)
     */
    public function claimableApplications(Request $request)
    {
        $user = Auth::user();

        // Get applications based on user's role involvement
        $query = Application::with(['student', 'university', 'course', 'intake'])
            ->where(function ($q) use ($user) {
                // Marketing: if they enrolled the student
                $q->whereHas('student', fn($sq) => $sq->where('assigned_marketing_id', $user->id));
            })
            ->orWhere(function ($q) use ($user) {
                // Consultant: if they're assigned to the student
                $q->whereHas('student', fn($sq) => $sq->where('assigned_consultant_id', $user->id));
            })
            ->orWhere(function ($q) use ($user) {
                // Application staff: if they're assigned to the student
                $q->whereHas('student', fn($sq) => $sq->where('assigned_application_id', $user->id));
            })
            ->orWhere('created_by', $user->id);

        // Exclude applications where user already has a non-draft/rejected commission
        $claimedApplicationIds = Commission::forUser($user->id)
            ->whereNotIn('workflow_status', [Commission::STATUS_DRAFT, Commission::STATUS_REJECTED])
            ->pluck('application_id')
            ->toArray();

        if (!empty($claimedApplicationIds)) {
            $query->whereNotIn('id', $claimedApplicationIds);
        }

        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('application_id', 'like', "%{$search}%")
                    ->orWhereHas('student', fn($sq) => $sq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%"));
            });
        }

        // Status filter
        if ($request->filled('application_status')) {
            $query->where('status', $request->application_status);
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        return view('admin.commissions.claimable', compact('applications'));
    }

    /**
     * Show form to create a claim
     */
    public function createClaim(Application $application)
    {
        $user = Auth::user();

        // Check if user can claim on this application
        if (!$this->canClaimOnApplication($user, $application)) {
            return redirect()->route('my-commissions.claimable')
                ->with('error', 'You are not authorized to claim commission on this application.');
        }

        // Check if already has a non-draft/rejected commission
        $existingClaim = Commission::forUser($user->id)
            ->forApplication($application->id)
            ->whereNotIn('workflow_status', [Commission::STATUS_DRAFT, Commission::STATUS_REJECTED])
            ->first();

        if ($existingClaim) {
            return redirect()->route('my-commissions.claimable')
                ->with('error', 'You already have an active claim on this application.');
        }

        // Check for rejected claim that can be re-claimed
        $rejectedClaim = Commission::forUser($user->id)
            ->forApplication($application->id)
            ->rejected()
            ->first();

        return view('admin.commissions.create-claim', compact('application', 'rejectedClaim'));
    }

    /**
     * Store a new commission claim
     */
    public function storeClaim(Request $request, Application $application)
    {
        $user = Auth::user();

        // Verify authorization
        if (!$this->canClaimOnApplication($user, $application)) {
            return redirect()->route('my-commissions.claimable')
                ->with('error', 'You are not authorized to claim commission on this application.');
        }

        // Validate
        $validated = $request->validate([
            'percentage' => 'nullable|numeric|min:0|max:100',
            'proposed_amount' => 'required|numeric|min:0',
            'claim_notes' => 'nullable|string|max:2000',
        ]);

        // Check if already has a non-draft/rejected commission
        $existingClaim = Commission::forUser($user->id)
            ->forApplication($application->id)
            ->whereNotIn('workflow_status', [Commission::STATUS_DRAFT, Commission::STATUS_REJECTED])
            ->first();

        if ($existingClaim) {
            return redirect()->route('my-commissions.claimable')
                ->with('error', 'You already have an active claim on this application.');
        }

        // Check for rejected claim - update it
        $rejectedClaim = Commission::forUser($user->id)
            ->forApplication($application->id)
            ->rejected()
            ->first();

        if ($rejectedClaim) {
            $rejectedClaim->update([
                'percentage' => $validated['percentage'] ?? null,
                'proposed_amount' => $validated['proposed_amount'],
                'amount' => 0, // Will be set by accountant
                'claim_notes' => $validated['claim_notes'] ?? null,
                'workflow_status' => Commission::STATUS_CLAIMED,
                'claimed_at' => now(),
                'reviewed_by' => null,
                'reviewed_at' => null,
                'review_notes' => null,
            ]);

            $message = 'Your commission claim has been resubmitted for review.';
        } else {
            // Create new claim
            Commission::create([
                'application_id' => $application->id,
                'user_id' => $user->id,
                'percentage' => $validated['percentage'] ?? null,
                'proposed_amount' => $validated['proposed_amount'],
                'amount' => 0, // Will be set by accountant on approval
                'notes' => $validated['claim_notes'] ?? null,
                'claim_notes' => $validated['claim_notes'] ?? null,
                'workflow_status' => Commission::STATUS_CLAIMED,
                'claimed_at' => now(),
            ]);

            $message = 'Commission claim submitted successfully. Awaiting accountant review.';
        }

        return redirect()->route('my-commissions.index')
            ->with('success', $message);
    }

    /**
     * Cancel a pending claim
     */
    public function cancelClaim(Commission $commission)
    {
        $user = Auth::user();

        // Verify ownership
        if ($commission->user_id !== $user->id) {
            return redirect()->route('my-commissions.index')
                ->with('error', 'Unauthorized action.');
        }

        // Check if can be cancelled
        if (!$commission->canBeCancelled()) {
            return redirect()->route('my-commissions.index')
                ->with('error', 'This claim cannot be cancelled at this stage.');
        }

        $commission->update([
            'workflow_status' => Commission::STATUS_DRAFT,
            'claimed_at' => null,
            'claim_notes' => null,
        ]);

        return redirect()->route('my-commissions.index')
            ->with('success', 'Commission claim cancelled successfully.');
    }

    /**
     * View claim details
     */
    public function show(Commission $commission)
    {
        $user = Auth::user();

        // Verify ownership
        if ($commission->user_id !== $user->id) {
            return redirect()->route('my-commissions.index')
                ->with('error', 'Unauthorized action.');
        }

        $commission->load(['application.student', 'application.university', 'application.course', 'reviewer']);

        return view('admin.commissions.show-my-claim', compact('commission'));
    }

    /**
     * Check if user can claim on an application
     */
    private function canClaimOnApplication($user, Application $application): bool
    {
        // Marketing assigned to student
        if ($application->student->assigned_marketing_id === $user->id) {
            return true;
        }

        // Consultant assigned to student
        if ($application->student->assigned_consultant_id === $user->id) {
            return true;
        }

        // Application staff assigned to student
        if ($application->student->assigned_application_id === $user->id) {
            return true;
        }

        // Created the application
        if ($application->created_by === $user->id) {
            return true;
        }

        return false;
    }
}
