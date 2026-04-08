<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Course, University};

class CourseController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:*editor');
    }

    public function index(Request $request)
    {
        $this->authorize('*editor');

        $query = Course::with('university');

        if ($search = $request->get('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($universityId = $request->get('university_id')) {
            $query->where('university_id', $universityId);
        }

        $courses = $query->latest()->paginate(15)->withQueryString();
        $universities = University::where('status', 1)->orderBy('name')->get();

        return view('admin.courses.index', compact('courses', 'universities'));
    }

    public function create()
    {
        $this->authorize('*editor');

        $universities = University::where('status', 1)->orderBy('name')->get();

        return view('admin.courses.create', compact('universities'));
    }

    public function store(Request $request)
    {
        $this->authorize('*editor');

        $validated = $this->validateCourse($request);

        Course::create($validated);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function edit(Course $course)
    {
        $this->authorize('*editor');

        $universities = University::where('status', 1)->orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'universities'));
    }

    public function update(Request $request, Course $course)
    {
        $this->authorize('*editor');

        $validated = $this->validateCourse($request);

        $course->update($validated);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        $this->authorize('*editor');

        $course->delete();

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course deleted successfully.');
    }

    private function validateCourse(Request $request): array
    {
        return $request->validate([
            'university_id' => ['required', 'exists:universities,id'],
            'name' => ['required', 'string', 'max:255'],
            'degree_level' => ['nullable', 'string', 'max:100'],
            'duration' => ['nullable', 'string', 'max:100'],
            'tuition_fee' => ['nullable', 'numeric'],
            'status' => ['required', 'boolean'],
        ]);
    }
}
