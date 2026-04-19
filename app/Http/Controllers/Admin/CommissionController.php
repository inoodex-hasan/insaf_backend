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

        $query = Commission::with(['application.student', 'user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', fn($uq) => $uq->where('name', 'like', "%{$search}%"))
                    ->orWhereHas('application', fn($aq) => $aq->where('application_id', 'like', "%{$search}%"))
                    ->orWhereHas('application.student', fn($sq) => $sq->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $commissions = $query->latest()->paginate(15)->withQueryString();

        return view('admin.accounts.commissions.index', compact('commissions'));
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

    public function updateStatus(Request $request, Commission $commission)
    {
        $this->authorize('*accountant');

        $validated = $request->validate([
            'status' => 'required|in:pending,paid',
        ]);

        $commission->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'status' => $commission->status,
            'message' => 'Commission status updated successfully.',
        ]);
    }

    public function destroy(Commission $commission)
    {
        $this->authorize('*accountant');

        $commission->delete();

        return redirect()->back()->with('success', 'Commission removed.');
    }
}
