<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\MarketingDocument;
use Illuminate\Http\Request;

class MarketingDocumentController extends Controller
{
    public function index(Request $request)
    {
        // If application_id is provided, show edit form for that application
        if ($request->filled('application_id')) {
            $applications = Application::with('student')
                ->orderBy('created_at', 'desc')
                ->get(['id', 'application_id', 'student_id']);

            $selectedApplication = Application::with('student')->find($request->application_id);
            $documents = [];

            // Get or create document records for each type
            $docTypes = ['sop', 'cv', 'cl'];
            foreach ($docTypes as $type) {
                $doc = MarketingDocument::firstOrCreate(
                    [
                        'application_id' => $request->application_id,
                        'document_type' => $type,
                    ],
                    [
                        'document_name' => strtoupper($type),
                        'status' => 'not_received',
                        'created_by' => auth()->id(),
                    ]
                );
                $documents[$type] = $doc;
            }

            return view('admin.marketing.documents.index', compact('applications', 'selectedApplication', 'documents'));
        }

        // Otherwise, show list of all applications with their document statuses
        $query = Application::with(['student', 'documents']);

        // Search by application ID or student name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('application_id', 'like', '%' . $search . '%')
                  ->orWhereHas('student', function($sq) use ($search) {
                      $sq->where('first_name', 'like', '%' . $search . '%')
                         ->orWhere('last_name', 'like', '%' . $search . '%')
                         ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ['%' . $search . '%']);
                  });
            });
        }

        // Filter by document status
        if ($request->filled('sop_status')) {
            $query->whereHas('documents', function($q) use ($request) {
                $q->where('document_type', 'sop')->where('status', $request->sop_status);
            });
        }
        if ($request->filled('cv_status')) {
            $query->whereHas('documents', function($q) use ($request) {
                $q->where('document_type', 'cv')->where('status', $request->cv_status);
            });
        }
        if ($request->filled('cl_status')) {
            $query->whereHas('documents', function($q) use ($request) {
                $q->where('document_type', 'cl')->where('status', $request->cl_status);
            });
        }

        $applications = $query->orderBy('created_at', 'desc')
            ->paginate(20)
            ->withQueryString();

        return view('admin.marketing.documents.list', compact('applications'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:applications,id',
            'documents' => 'required|array',
            'documents.*.status' => 'required|in:pending,received,not_received,ready,submitted',
        ]);

        foreach ($validated['documents'] as $type => $data) {
            MarketingDocument::updateOrCreate(
                [
                    'application_id' => $validated['application_id'],
                    'document_type' => $type,
                ],
                [
                    'document_name' => strtoupper($type),
                    'status' => $data['status'],
                    'created_by' => auth()->id(),
                ]
            );
        }

        return redirect()->route('admin.marketing.documents.index')
            ->with('success', 'Document statuses updated successfully.');
    }
}
