<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Http\Controllers\Controller;
use App\Models\{Application, Country, Course, CourseIntake, Student, University, User};
use App\Notifications\NewApplicationNotification;
use Barryvdh\DomPDF\Facade\Pdf;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = Application::with(['student', 'university', 'course', 'intake', 'creator']);

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('application_id', 'like', "%{$search}%")
                    ->orWhereHas(
                        'student',
                        function ($sq) use ($search) {
                            $sq->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        }
                    );
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $applications = $query->latest()->paginate(15)->withQueryString();

        return view('admin.applications.index', compact('applications'));
    }

    //   public function index2(Request $request)
    // {
    //     $query = Application::with(['student', 'university', 'course', 'intake', 'creator']);

    //     if ($search = $request->get('search')) {
    //         $query->where(function ($q) use ($search) {
    //             $q->where('application_id', 'like', "%{$search}%")
    //                 ->orWhereHas(
    //                     'student',
    //                     function ($sq) use ($search) {
    //                         $sq->where('first_name', 'like', "%{$search}%")
    //                             ->orWhere('last_name', 'like', "%{$search}%");
    //                     }
    //                 );
    //         });
    //     }

    //     $applications = $query->latest()->paginate(15)->withQueryString();

    //     return view('admin.applications.index2', compact('applications'));
    // }

    public function create(Request $request)
    {
        $students = Student::orderBy('first_name')->get(['id', 'first_name', 'last_name', 'email', 'phone']);
        $countries = Country::where('status', '1')->orderBy('name')->get(['id', 'name']);
        $universities = University::where('status', '1')->orderBy('name')->get(['id', 'name']);

        $selected_student = $request->get('student_id');

        return view('admin.applications.create', compact('students', 'universities', 'selected_student', 'countries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:students,id',
            'university_id' => 'required|exists:universities,id',
            'course_id' => 'required|exists:courses,id',
            'course_intake_id' => 'required|exists:course_intakes,id',
            'tuition_fee' => 'required|numeric|min:0',
            'total_fee' => 'required|numeric|min:0',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();

        $application = Application::create($validated);

        // Notify Application Team
        $applicationTeam = User::whereHas('roles', function ($q) {
            $q->where('name', 'like', '%application%');
        })->get();

        if ($applicationTeam->count() > 0) {
            Notification::send($applicationTeam, new NewApplicationNotification($application));
        }

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application created successfully.');
    }

    public function edit(Application $application)
    {
        $application->load('payments.collector');
        $students = Student::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        $countries = Country::where('status', '1')->orderBy('name')->get(['id', 'name']);
        $universities = University::where('status', '1')->orderBy('name')->get(['id', 'name']);

        $universityId = old('university_id', $application->university_id);
        $courseId = old('course_id', $application->course_id);

        $courses = [];
        if ($universityId) {
            $courses = Course::where('university_id', $universityId)
                ->leftJoin('currencies', 'courses.currency', '=', 'currencies.code')
                ->get(['courses.id', 'courses.name', 'courses.tuition_fee', 'courses.currency', 'currencies.exchange_rate']);
        }

        $intakes = [];
        if ($courseId) {
            $intakes = CourseIntake::where('course_id', $courseId)->get(['id', 'intake_name']);
        }

        return view('admin.applications.edit', compact('application', 'students', 'universities', 'courses', 'intakes', 'countries'));
    }

    //   public function edit2(Application $application)
    // {
    //     $application->load('payments.collector');
    //     $students = Student::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
    //     $countries = Country::where('status', '1')->orderBy('name')->get(['id', 'name']);
    //     $universities = University::where('status', '1')->orderBy('name')->get(['id', 'name']);

    //     $universityId = old('university_id', $application->university_id);
    //     $courseId = old('course_id', $application->course_id);

    //     $courses = [];
    //     if ($universityId) {
    //         $courses = Course::where('university_id', $universityId)
    //             ->leftJoin('currencies', 'courses.currency', '=', 'currencies.code')
    //             ->get(['courses.id', 'courses.name', 'courses.tuition_fee', 'courses.currency', 'currencies.exchange_rate']);
    //     }

    //     $intakes = [];
    //     if ($courseId) {
    //         $intakes = CourseIntake::where('course_id', $courseId)->get(['id', 'intake_name']);
    //     }

    //     return view('admin.applications.edit2', compact('application', 'students', 'universities', 'courses', 'intakes', 'countries'));
    // }

    public function update(Request $request, Application $application)
    {
        $validated = $request->validate([
            'university_id' => 'required|exists:universities,id',
            'course_id' => 'required|exists:courses,id',
            'course_intake_id' => 'required|exists:course_intakes,id',
            'tuition_fee' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'total_fee' => 'required|numeric|min:0',
            'status' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $application->update($validated);

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application updated successfully.');
    }


    //   public function update2(Request $request, Application $application)
    // {
    //     $validated = $request->validate([
    //         'university_id' => 'required|exists:universities,id',
    //         'course_id' => 'required|exists:courses,id',
    //         'course_intake_id' => 'required|exists:course_intakes,id',
    //         'tuition_fee' => 'required|numeric|min:0',
    //         'currency' => 'required|string|max:10',
    //         'total_fee' => 'required|numeric|min:0',
    //         'status' => 'required|string',
    //         'notes' => 'nullable|string',
    //     ]);

    //     $application->update($validated);

    //     return redirect()
    //         ->route('admin.applications.index2')
    //         ->with('success', 'Application updated successfully.');
    // }

    public function destroy(Application $application)
    {
        $application->delete();

        return redirect()
            ->route('admin.applications.index')
            ->with('success', 'Application deleted successfully.');
    }

    public function downloadPdf(Application $application)
    {
        $application->load(['student', 'university', 'university.country', 'course', 'intake']);

        $pdf = Pdf::loadView('admin.applications.pdf', compact('application'));

        return $pdf->download('Application_' . $application->application_id . '.pdf');
    }

    public function getUniversities(Request $request)
    {
        $query = University::where('status', '1');
        if ($request->has('country_id')) {
            $query->where('country_id', $request->country_id);
        }
        $universities = $query->orderBy('name')->get(['id', 'name']);
        return response()->json($universities);
    }

    public function getCourses(Request $request)
    {
        $courses = Course::where('university_id', $request->university_id)
            ->leftJoin('currencies', 'courses.currency', '=', 'currencies.code')
            ->orderBy('courses.name')
            ->get(['courses.id', 'courses.name', 'courses.tuition_fee', 'courses.currency', 'currencies.exchange_rate']);
        return response()->json($courses);
    }

    public function getIntakes(Request $request)
    {
        $intakes = CourseIntake::where('course_id', $request->course_id)->orderBy('intake_name')->get(['id', 'intake_name']);
        return response()->json($intakes);
    }

    public function getStudentDetails(Request $request)
    {
        $request->validate(['student_id' => 'required|exists:students,id']);
        $student = Student::with(['university', 'course', 'intake'])->findOrFail($request->student_id);

        return response()->json([
            'country_id' => $student->country_id,
            'university_id' => $student->university_id,
            'course_id' => $student->course_id,
            'course_intake_id' => $student->course_intake_id,
        ]);
    }
}
