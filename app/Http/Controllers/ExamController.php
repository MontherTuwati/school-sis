<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Exam;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Classroom;
use Carbon\Carbon;

class ExamController extends Controller
{
    /**
     * Display a listing of exams
     */
    public function index()
    {
        $exams = Exam::with(['course', 'subject', 'classroom', 'creator'])
            ->orderBy('exam_date', 'desc')
            ->paginate(15);

        $stats = [
            'total' => Exam::count(),
            'upcoming' => Exam::upcoming()->count(),
            'completed' => Exam::past()->count(),
            'today' => Exam::whereDate('exam_date', Carbon::today())->count(),
        ];

        return view('exam.index', compact('exams', 'stats'));
    }

    /**
     * Show the form for creating a new exam
     */
    public function create()
    {
        $courses = Course::all();
        $subjects = Subject::all();
        $classrooms = Classroom::all();

        return view('exam.create', compact('courses', 'subjects', 'classrooms'));
    }

    /**
     * Store a newly created exam
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_type' => 'required|in:midterm,final,quiz,assignment,practical',
            'exam_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'duration' => 'required|integer|min:1',
            'total_marks' => 'required|numeric|min:0',
            'passing_marks' => 'required|numeric|min:0|lte:total_marks',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'semester' => 'nullable|string|max:50',
            'academic_year' => 'nullable|string|max:20',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $exam = new Exam();
            $exam->title = $request->title;
            $exam->course_id = $request->course_id;
            $exam->subject_id = $request->subject_id;
            $exam->exam_type = $request->exam_type;
            $exam->exam_date = $request->exam_date;
            $exam->start_time = $request->start_time;
            $exam->end_time = $request->end_time;
            $exam->duration = $request->duration;
            $exam->total_marks = $request->total_marks;
            $exam->passing_marks = $request->passing_marks;
            $exam->classroom_id = $request->classroom_id;
            $exam->semester = $request->semester;
            $exam->academic_year = $request->academic_year;
            $exam->instructions = $request->instructions;
            $exam->is_active = $request->boolean('is_active', true);
            $exam->created_by = auth()->id();
            $exam->save();

            DB::commit();

            return redirect()->route('exam.index')->with('success', 'Exam created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to create exam: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create exam. Please try again.');
        }
    }

    /**
     * Display the specified exam
     */
    public function show($id)
    {
        $exam = Exam::with(['course', 'subject', 'classroom', 'creator'])->findOrFail($id);
        return view('exam.show', compact('exam'));
    }

    /**
     * Show the form for editing the specified exam
     */
    public function edit($id)
    {
        $exam = Exam::with(['course', 'subject', 'classroom'])->findOrFail($id);
        $courses = Course::all();
        $subjects = Subject::all();
        $classrooms = Classroom::all();

        return view('exam.edit', compact('exam', 'courses', 'subjects', 'classrooms'));
    }

    /**
     * Update the specified exam
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'exam_type' => 'required|in:midterm,final,quiz,assignment,practical',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'duration' => 'required|integer|min:1',
            'total_marks' => 'required|numeric|min:0',
            'passing_marks' => 'required|numeric|min:0|lte:total_marks',
            'classroom_id' => 'nullable|exists:classrooms,id',
            'semester' => 'nullable|string|max:50',
            'academic_year' => 'nullable|string|max:20',
            'instructions' => 'nullable|string',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $exam = Exam::findOrFail($id);
            $exam->title = $request->title;
            $exam->course_id = $request->course_id;
            $exam->subject_id = $request->subject_id;
            $exam->exam_type = $request->exam_type;
            $exam->exam_date = $request->exam_date;
            $exam->start_time = $request->start_time;
            $exam->end_time = $request->end_time;
            $exam->duration = $request->duration;
            $exam->total_marks = $request->total_marks;
            $exam->passing_marks = $request->passing_marks;
            $exam->classroom_id = $request->classroom_id;
            $exam->semester = $request->semester;
            $exam->academic_year = $request->academic_year;
            $exam->instructions = $request->instructions;
            $exam->is_active = $request->boolean('is_active', true);
            $exam->save();

            DB::commit();

            return redirect()->route('exam.index')->with('success', 'Exam updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to update exam: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update exam. Please try again.');
        }
    }

    /**
     * Remove the specified exam
     */
    public function destroy($id)
    {
        try {
            $exam = Exam::findOrFail($id);
            $exam->delete();

            return redirect()->route('exam.index')->with('success', 'Exam deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to delete exam: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete exam. Please try again.');
        }
    }

    /**
     * Toggle exam status
     */
    public function toggleStatus($id)
    {
        try {
            $exam = Exam::findOrFail($id);
            $exam->is_active = !$exam->is_active;
            $exam->save();

            $status = $exam->is_active ? 'activated' : 'deactivated';
            return redirect()->route('exam.index')->with('success', "Exam {$status} successfully!");
        } catch (\Exception $e) {
            \Log::error('Failed to toggle exam status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to toggle exam status. Please try again.');
        }
    }

    /**
     * Get exams by course
     */
    public function getByCourse($courseId)
    {
        $exams = Exam::with(['course', 'subject', 'classroom'])
            ->where('course_id', $courseId)
            ->orderBy('exam_date', 'desc')
            ->get();

        return response()->json($exams);
    }

    /**
     * Get upcoming exams
     */
    public function upcoming()
    {
        $exams = Exam::with(['course', 'subject', 'classroom'])
            ->upcoming()
            ->orderBy('exam_date')
            ->get();

        return response()->json($exams);
    }

    /**
     * Export exams to CSV
     */
    public function export()
    {
        $exams = Exam::with(['course', 'subject', 'classroom', 'creator'])->get();

        $filename = 'exams_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($exams) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Title', 'Course', 'Subject', 'Type', 'Date', 'Start Time', 
                'End Time', 'Duration', 'Total Marks', 'Passing Marks', 
                'Classroom', 'Semester', 'Academic Year', 'Status', 'Created By'
            ]);
            
            foreach ($exams as $exam) {
                fputcsv($file, [
                    $exam->title,
                    $exam->course ? $exam->course->name : 'N/A',
                    $exam->subject ? $exam->subject->name : 'N/A',
                    $exam->getExamTypeLabel(),
                    $exam->formatted_date,
                    $exam->formatted_start_time,
                    $exam->formatted_end_time,
                    $exam->duration_formatted,
                    $exam->total_marks,
                    $exam->passing_marks,
                    $exam->classroom ? $exam->classroom->name : 'N/A',
                    $exam->semester ?? 'N/A',
                    $exam->academic_year ?? 'N/A',
                    $exam->status,
                    $exam->creator ? $exam->creator->name : 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
