<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{Course, University};

class CourseController extends Controller
{

    public function index(Request $request)
    {

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
        $universities = University::where('status', 1)->orderBy('name')->get();

        return view('admin.courses.create', compact('universities'));
    }

    public function store(Request $request)
    {

        $validated = $this->validateCourse($request);

        Course::create($validated);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course created successfully.');
    }

    public function show(Course $course)
    {
        $course->load('university');
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {

        $universities = University::where('status', 1)->orderBy('name')->get();

        return view('admin.courses.edit', compact('course', 'universities'));
    }

    public function update(Request $request, Course $course)
    {

        $validated = $this->validateCourse($request);

        $course->update($validated);

        return redirect()
            ->route('admin.courses.index')
            ->with('success', 'Course updated successfully.');
    }

    public function destroy(Course $course)
    {
        return $this->safeDelete($course, 'admin.courses.index', [], 'Course deleted successfully.');
    }

    private function validateCourse(Request $request): array
    {
        return $request->validate([
            'university_id' => ['required', 'exists:universities,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'degree_level' => ['nullable', 'string', 'max:100'],
            'duration' => ['nullable', 'string', 'max:100'],
            'tuition_fee' => ['nullable', 'numeric'],
            'status' => ['required', 'boolean'],
        ]);
    }
}
