<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Country;
use App\Models\Course;
use App\Models\University;
use App\Models\User;
use App\Notifications\NewLeadSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class LeadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Lead::with('creator');

        // Filter by source
        if ($request->has('source') && $request->source != '') {
            $query->where('source', $request->source);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Search by name or phone
        if ($request->has('search') && $request->search != '') {
            $query->where(function ($q) use ($request) {
                $q->where('student_name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        $leads = $query->latest()->paginate(15);

        return view('admin.marketing.leads.index', compact('leads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $countries = Country::where('status', '1')->get();
        $courses = Course::where('status', '1')->get();
        return view('admin.marketing.leads.create', compact('countries', 'courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'current_education' => 'nullable|string|max:255',
            'preferred_country' => 'nullable|exists:countries,id',
            'preferred_course' => 'nullable|exists:courses,id',
            'source' => 'nullable|string|max:255',
            'next_follow_up_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $lead = Lead::create($this->prepareLeadPayload($validated));

        // Notify Consultants
        $consultants = User::whereHas('roles', function ($q) {
            $q->where('name', 'like', '%consult%');
        })->get();

        if ($consultants->count() > 0) {
            Notification::send($consultants, new NewLeadSubmitted($lead));
        }

        return redirect()->route('admin.marketing.leads.index')->with('success', 'Lead collected successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Lead $lead)
    {
        $lead->load(['creator', 'consultant', 'country', 'course']);
        return view('admin.marketing.leads.show', compact('lead'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Lead $lead)
    {
        $countries = Country::where('status', '1')->get();
        // pre-fetch universities if country is selected
        $universities = collect();
        if ($lead->preferred_country) {
            $universities = University::where('country_id', $lead->preferred_country)->where('status', '1')->get();
        }

        // pre-fetch courses if university is selected (or we can derive it from course)
        $courses = collect();
        // Ideally we should have university_id on lead, but we don't.
        // We can try to get it from the course if preferred_course is set.
        $selectedUniversityId = null;
        if ($lead->preferred_course) {
            $course = Course::find($lead->preferred_course);
            if ($course) {
                $selectedUniversityId = $course->university_id;
                $courses = Course::where('university_id', $selectedUniversityId)->where('status', '1')->get();
            }
        }

        return view('admin.marketing.leads.edit', compact('lead', 'countries', 'universities', 'courses', 'selectedUniversityId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Lead $lead)
    {
        $validated = $request->validate([
            'student_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'current_education' => 'nullable|string|max:255',
            'preferred_country' => 'nullable|string|exists:countries,id',
            'preferred_course' => 'nullable|string|exists:courses,id',
            'source' => 'nullable|string|max:255',
            'status' => 'nullable|string',
            'next_follow_up_at' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $lead->update($this->prepareLeadPayload($validated, $lead));

        return redirect()->route('admin.marketing.leads.index')->with('success', 'Lead updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('admin.marketing.leads.index')->with('success', 'Lead deleted successfully.');
    }

    public function getUniversities(Request $request)
    {
        $request->validate(['country_id' => 'required|exists:countries,id']);
        $universities = University::where('country_id', $request->country_id)
            ->where('status', '1')
            ->get(['id', 'name']);
        return response()->json($universities);
    }

    public function getCourses(Request $request)
    {
        $request->validate(['university_id' => 'required|exists:universities,id']);
        $courses = Course::where('university_id', $request->university_id)
            ->where('status', '1')
            ->get(['id', 'name']);
        return response()->json($courses);
    }

    private function buildFollowUpHistory(?Lead $lead, ?string $nextFollowUpAt, ?string $notes): ?array
    {
        $history = collect($lead?->follow_up_history ?? [])
            ->map(fn ($entry) => $this->normalizeFollowUpHistoryEntry($entry))
            ->filter(fn ($entry) => $entry !== null)
            ->values();

        $existingEntry = $this->normalizeFollowUpHistoryEntry([
            'date' => $lead?->next_follow_up_at,
            'notes' => $lead?->notes,
        ]);

        if ($existingEntry !== null && ! $this->followUpEntriesMatch($history->last(), $existingEntry)) {
            $history->push($existingEntry);
        }

        if (filled($nextFollowUpAt)) {
            $nextEntry = $this->normalizeFollowUpHistoryEntry([
                'date' => $nextFollowUpAt,
                'notes' => $notes,
            ]);

            if ($nextEntry !== null && ! $this->followUpEntriesMatch($history->last(), $nextEntry)) {
                $history->push($nextEntry);
            }
        }

        $history = $history
            ->filter(fn ($entry) => $entry !== null)
            ->values()
            ->all();

        return empty($history) ? null : $history;
    }

    private function prepareLeadPayload(array $validated, ?Lead $lead = null): array
    {
        if ($lead === null) {
            $validated['created_by'] = Auth::id();
            $validated['status'] = $validated['status'] ?? 'pending';
        } else {
            $validated['status'] = $validated['status'] ?? $lead->status;
        }

        if (Schema::hasColumn('leads', 'follow_up_history')) {
            $validated['follow_up_history'] = $this->buildFollowUpHistory(
                $lead,
                $validated['next_follow_up_at'] ?? null,
                $validated['notes'] ?? null,
            );
        }

        return $validated;
    }

    private function normalizeFollowUpHistoryEntry(mixed $entry): ?array
    {
        if (blank($entry)) {
            return null;
        }

        if (! is_array($entry)) {
            return [
                'date' => Carbon::parse($entry)->toDateString(),
                'notes' => null,
            ];
        }

        $date = $entry['date'] ?? $entry['follow_up_date'] ?? $entry['next_follow_up_at'] ?? null;

        if (blank($date)) {
            return null;
        }

        return [
            'date' => Carbon::parse($date)->toDateString(),
            'notes' => $entry['notes'] ?? null,
        ];
    }

    private function followUpEntriesMatch(?array $left, ?array $right): bool
    {
        if ($left === null || $right === null) {
            return false;
        }

        return $left['date'] === $right['date']
            && (string) ($left['notes'] ?? '') === (string) ($right['notes'] ?? '');
    }
}
