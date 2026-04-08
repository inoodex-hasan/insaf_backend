<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use Illuminate\Http\Request;

class CommissionController extends Controller
{
    public function index(Request $request)
    {
        $query = Commission::with(['payment.application.student', 'user'])->latest();

        if ($request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('role', 'like', "%{$search}%")
                    ->orWhereHas(
                        'user',
                        function ($uq) use ($search) {
                            $uq->where('name', 'like', "%{$search}%");
                        }
                    )
                    ->orWhereHas(
                        'payment.application.student',
                        function ($sq) use ($search) {
                            $sq->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        }
                    );
            });
        }

        $commissions = $query->paginate(15);

        return view('admin.commissions.index', compact('commissions'));
    }

    public function updateStatus(Request $request, Commission $commission)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid',
        ]);

        $commission->update(['status' => $validated['status']]);

        return response()->json([
            'success' => true,
            'status' => $commission->status,
            'message' => 'Commission status updated successfully.'
        ]);
    }
}
