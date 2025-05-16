<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Examination;
use App\Models\ExamSchedule;
use App\Models\ExamResult;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ClassRoom;
use Carbon\Carbon;

class ExaminationController extends Controller
{
    /** examination dashboard */
    public function index()
    {
        $stats = $this->getExaminationStats();
        $upcomingExams = Examination::with(['course', 'subject'])
                                  ->where('exam_date', '>=', Carbon::today())
                                  ->orderBy('exam_date')
                                  ->limit(10)
                                  ->get();
        
        return view('examination.index', compact('stats', 'upcomingExams'));
    }

    /** examinations list */
    public function examinations()
    {
        $examinations = Examination::with(['course', 'subject'])->orderBy('exam_date', 'desc')->get();
        $courses = Course::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $classrooms = ClassRoom::where('is_active', true)->get();
        
        return view('examination.examinations', compact('examinations', 'courses', 'subjects', 'classrooms'));
    }

    /** create examination */
    public function create()
    {
        $courses = Course::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $classrooms = ClassRoom::where('is_active', true)->get();
        
        return view('examination.create', compact('courses', 'subjects', 'classrooms'));
    }

    /** store examination */
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
            'total_marks' => 'required|numeric|min:1',
            'passing_marks' => 'required|numeric|min:1|lte:total_marks',
            'classroom_id' => 'required|exists:class_rooms,id',
            'semester' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
            'instructions' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        try {
            $examination = new Examination();
            $examination->title = $request->title;
            $examination->course_id = $request->course_id;
            $examination->subject_id = $request->subject_id;
            $examination->exam_type = $request->exam_type;
            $examination->exam_date = $request->exam_date;
            $examination->start_time = $request->start_time;
            $examination->end_time = $request->end_time;
            $examination->duration = $request->duration;
            $examination->total_marks = $request->total_marks;
            $examination->passing_marks = $request->passing_marks;
            $examination->classroom_id = $request->classroom_id;
            $examination->semester = $request->semester;
            $examination->academic_year = $request->academic_year;
            $examination->instructions = $request->instructions;
            $examination->is_active = $request->boolean('is_active');
            $examination->created_by = auth()->id();
            $examination->save();
            
            return redirect()->route('examination.examinations')->with('success', 'Examination created successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to create examination: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create examination. Please try again.');
        }
    }

    /** edit examination */
    public function edit($id)
    {
        $examination = Examination::with(['course', 'subject', 'classroom'])->findOrFail($id);
        $courses = Course::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $classrooms = ClassRoom::where('is_active', true)->get();
        
        return view('examination.edit', compact('examination', 'courses', 'subjects', 'classrooms'));
    }

    /** update examination */
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
            'total_marks' => 'required|numeric|min:1',
            'passing_marks' => 'required|numeric|min:1|lte:total_marks',
            'classroom_id' => 'required|exists:class_rooms,id',
            'semester' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
            'instructions' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        try {
            $examination = Examination::findOrFail($id);
            $examination->title = $request->title;
            $examination->course_id = $request->course_id;
            $examination->subject_id = $request->subject_id;
            $examination->exam_type = $request->exam_type;
            $examination->exam_date = $request->exam_date;
            $examination->start_time = $request->start_time;
            $examination->end_time = $request->end_time;
            $examination->duration = $request->duration;
            $examination->total_marks = $request->total_marks;
            $examination->passing_marks = $request->passing_marks;
            $examination->classroom_id = $request->classroom_id;
            $examination->semester = $request->semester;
            $examination->academic_year = $request->academic_year;
            $examination->instructions = $request->instructions;
            $examination->is_active = $request->boolean('is_active');
            $examination->save();
            
            return redirect()->route('examination.examinations')->with('success', 'Examination updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to update examination: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update examination. Please try again.');
        }
    }

    /** delete examination */
    public function destroy($id)
    {
        try {
            $examination = Examination::findOrFail($id);
            
            // Check if results exist
            $hasResults = ExamResult::where('examination_id', $id)->exists();
            
            if ($hasResults) {
                return redirect()->back()->with('error', 'Cannot delete examination with existing results.');
            }
            
            $examination->delete();
            
            return redirect()->route('examination.examinations')->with('success', 'Examination deleted successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to delete examination: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete examination. Please try again.');
        }
    }

    /** view examination */
    public function view($id)
    {
        $examination = Examination::with(['course', 'subject', 'classroom'])->findOrFail($id);
        $results = ExamResult::with('student')->where('examination_id', $id)->get();
        
        return view('examination.view', compact('examination', 'results'));
    }

    /** exam schedules */
    public function schedules()
    {
        $schedules = ExamSchedule::with(['examination', 'student'])->orderBy('exam_date')->get();
        $examinations = Examination::where('is_active', true)->get();
        $students = Student::where('is_active', true)->get();
        
        return view('examination.schedules', compact('schedules', 'examinations', 'students'));
    }

    /** create schedule */
    public function createSchedule()
    {
        $examinations = Examination::where('is_active', true)->get();
        $students = Student::where('is_active', true)->get();
        
        return view('examination.create-schedule', compact('examinations', 'students'));
    }

    /** store schedule */
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'student_ids' => 'required|array|min:1',
            'student_ids.*' => 'exists:students,id',
            'exam_date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room_number' => 'nullable|string|max:50',
            'seat_number' => 'nullable|string|max:20',
            'instructions' => 'nullable|string|max:500',
        ]);

        try {
            foreach ($request->student_ids as $studentId) {
                $schedule = new ExamSchedule();
                $schedule->examination_id = $request->examination_id;
                $schedule->student_id = $studentId;
                $schedule->exam_date = $request->exam_date;
                $schedule->start_time = $request->start_time;
                $schedule->end_time = $request->end_time;
                $schedule->room_number = $request->room_number;
                $schedule->seat_number = $request->seat_number;
                $schedule->instructions = $request->instructions;
                $schedule->created_by = auth()->id();
                $schedule->save();
            }
            
            return redirect()->route('examination.schedules')->with('success', 'Exam schedules created successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to create exam schedule: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create exam schedule. Please try again.');
        }
    }

    /** exam results */
    public function results()
    {
        $results = ExamResult::with(['examination', 'student'])->orderBy('created_at', 'desc')->get();
        $examinations = Examination::where('is_active', true)->get();
        $students = Student::where('is_active', true)->get();
        
        return view('examination.results', compact('results', 'examinations', 'students'));
    }

    /** create result */
    public function createResult()
    {
        $examinations = Examination::where('is_active', true)->get();
        $students = Student::where('is_active', true)->get();
        
        return view('examination.create-result', compact('examinations', 'students'));
    }

    /** store result */
    public function storeResult(Request $request)
    {
        $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'student_id' => 'required|exists:students,id',
            'marks_obtained' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
            'is_absent' => 'boolean',
        ]);

        try {
            $examination = Examination::findOrFail($request->examination_id);
            
            // Check if result already exists
            $existingResult = ExamResult::where('examination_id', $request->examination_id)
                                      ->where('student_id', $request->student_id)
                                      ->exists();
            
            if ($existingResult) {
                return redirect()->back()->with('error', 'Result already exists for this student and examination.');
            }
            
            $result = new ExamResult();
            $result->examination_id = $request->examination_id;
            $result->student_id = $request->student_id;
            $result->marks_obtained = $request->marks_obtained;
            $result->total_marks = $examination->total_marks;
            $result->percentage = ($request->marks_obtained / $examination->total_marks) * 100;
            $result->grade = $this->calculateGrade($result->percentage);
            $result->is_pass = $request->marks_obtained >= $examination->passing_marks;
            $result->remarks = $request->remarks;
            $result->is_absent = $request->boolean('is_absent');
            $result->created_by = auth()->id();
            $result->save();
            
            return redirect()->route('examination.results')->with('success', 'Exam result created successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to create exam result: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create exam result. Please try again.');
        }
    }

    /** edit result */
    public function editResult($id)
    {
        $result = ExamResult::with(['examination', 'student'])->findOrFail($id);
        $examinations = Examination::where('is_active', true)->get();
        $students = Student::where('is_active', true)->get();
        
        return view('examination.edit-result', compact('result', 'examinations', 'students'));
    }

    /** update result */
    public function updateResult(Request $request, $id)
    {
        $request->validate([
            'examination_id' => 'required|exists:examinations,id',
            'student_id' => 'required|exists:students,id',
            'marks_obtained' => 'required|numeric|min:0',
            'remarks' => 'nullable|string|max:500',
            'is_absent' => 'boolean',
        ]);

        try {
            $result = ExamResult::findOrFail($id);
            $examination = Examination::findOrFail($request->examination_id);
            
            $result->examination_id = $request->examination_id;
            $result->student_id = $request->student_id;
            $result->marks_obtained = $request->marks_obtained;
            $result->total_marks = $examination->total_marks;
            $result->percentage = ($request->marks_obtained / $examination->total_marks) * 100;
            $result->grade = $this->calculateGrade($result->percentage);
            $result->is_pass = $request->marks_obtained >= $examination->passing_marks;
            $result->remarks = $request->remarks;
            $result->is_absent = $request->boolean('is_absent');
            $result->save();
            
            return redirect()->route('examination.results')->with('success', 'Exam result updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to update exam result: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update exam result. Please try again.');
        }
    }

    /** delete result */
    public function deleteResult($id)
    {
        try {
            $result = ExamResult::findOrFail($id);
            $result->delete();
            
            return redirect()->route('examination.results')->with('success', 'Exam result deleted successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to delete exam result: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete exam result. Please try again.');
        }
    }

    /** student results */
    public function studentResults($studentId = null)
    {
        $studentId = $studentId ?? request('student_id');
        
        if (!$studentId) {
            return redirect()->back()->with('error', 'Student ID is required.');
        }
        
        $student = Student::findOrFail($studentId);
        $results = ExamResult::with(['examination'])
                           ->where('student_id', $studentId)
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        return view('examination.student-results', compact('student', 'results'));
    }

    /** examination reports */
    public function reports()
    {
        $stats = $this->getExaminationReportStats();
        
        return view('examination.reports', compact('stats'));
    }

    /** export examination data */
    public function export(Request $request)
    {
        $type = $request->input('type', 'examinations');
        $format = $request->input('format', 'csv');
        
        switch ($type) {
            case 'examinations':
                $data = Examination::with(['course', 'subject', 'classroom'])->get();
                break;
            case 'results':
                $data = ExamResult::with(['examination', 'student'])->get();
                break;
            case 'schedules':
                $data = ExamSchedule::with(['examination', 'student'])->get();
                break;
            default:
                $data = [];
        }
        
        if ($format === 'csv') {
            return $this->exportToCSV($data, $type);
        } else {
            return $this->exportToPDF($data, $type);
        }
    }

    /** get examination statistics */
    private function getExaminationStats()
    {
        $totalExaminations = Examination::count();
        $activeExaminations = Examination::where('is_active', true)->count();
        
        $totalResults = ExamResult::count();
        $passedResults = ExamResult::where('is_pass', true)->count();
        $failedResults = ExamResult::where('is_pass', false)->count();
        
        $totalSchedules = ExamSchedule::count();
        $upcomingExams = Examination::where('exam_date', '>=', Carbon::today())->count();
        
        // Recent activity
        $recentExaminations = Examination::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $recentResults = ExamResult::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        
        return [
            'total_examinations' => $totalExaminations,
            'active_examinations' => $activeExaminations,
            'total_results' => $totalResults,
            'passed_results' => $passedResults,
            'failed_results' => $failedResults,
            'total_schedules' => $totalSchedules,
            'upcoming_exams' => $upcomingExams,
            'recent_examinations' => $recentExaminations,
            'recent_results' => $recentResults,
        ];
    }

    /** get examination report statistics */
    private function getExaminationReportStats()
    {
        // Examination distribution by type
        $examTypeDistribution = Examination::selectRaw('exam_type, COUNT(*) as count')
                                        ->groupBy('exam_type')
                                        ->get();
        
        // Results distribution by grade
        $gradeDistribution = ExamResult::selectRaw('grade, COUNT(*) as count')
                                    ->groupBy('grade')
                                    ->get();
        
        // Pass/Fail statistics
        $passFailStats = [
            'pass' => ExamResult::where('is_pass', true)->count(),
            'fail' => ExamResult::where('is_pass', false)->count(),
        ];
        
        // Average scores by examination
        $averageScores = Examination::withAvg('results', 'percentage')
                                 ->withCount('results')
                                 ->get();
        
        return [
            'exam_type_distribution' => $examTypeDistribution,
            'grade_distribution' => $gradeDistribution,
            'pass_fail_stats' => $passFailStats,
            'average_scores' => $averageScores,
        ];
    }

    /** calculate grade */
    private function calculateGrade($percentage)
    {
        if ($percentage >= 90) return 'A+';
        if ($percentage >= 80) return 'A';
        if ($percentage >= 70) return 'B+';
        if ($percentage >= 60) return 'B';
        if ($percentage >= 50) return 'C+';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 30) return 'D';
        return 'F';
    }

    /** export to CSV */
    private function exportToCSV($data, $type)
    {
        $filename = $type . '_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($data, $type) {
            $file = fopen('php://output', 'w');
            
            switch ($type) {
                case 'examinations':
                    fputcsv($file, ['Title', 'Course', 'Subject', 'Type', 'Date', 'Start Time', 'End Time', 'Total Marks', 'Passing Marks', 'Status']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->title,
                            $item->course ? $item->course->name : 'N/A',
                            $item->subject ? $item->subject->name : 'N/A',
                            ucfirst($item->exam_type),
                            $item->exam_date,
                            $item->start_time,
                            $item->end_time,
                            $item->total_marks,
                            $item->passing_marks,
                            $item->is_active ? 'Active' : 'Inactive'
                        ]);
                    }
                    break;
                case 'results':
                    fputcsv($file, ['Student', 'Examination', 'Marks Obtained', 'Total Marks', 'Percentage', 'Grade', 'Pass/Fail', 'Remarks']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->student ? $item->student->first_name . ' ' . $item->student->last_name : 'N/A',
                            $item->examination ? $item->examination->title : 'N/A',
                            $item->marks_obtained,
                            $item->total_marks,
                            round($item->percentage, 2) . '%',
                            $item->grade,
                            $item->is_pass ? 'Pass' : 'Fail',
                            $item->remarks ?? 'N/A'
                        ]);
                    }
                    break;
                case 'schedules':
                    fputcsv($file, ['Student', 'Examination', 'Date', 'Start Time', 'End Time', 'Room', 'Seat', 'Instructions']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->student ? $item->student->first_name . ' ' . $item->student->last_name : 'N/A',
                            $item->examination ? $item->examination->title : 'N/A',
                            $item->exam_date,
                            $item->start_time,
                            $item->end_time,
                            $item->room_number ?? 'N/A',
                            $item->seat_number ?? 'N/A',
                            $item->instructions ?? 'N/A'
                        ]);
                    }
                    break;
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /** export to PDF */
    private function exportToPDF($data, $type)
    {
        // Placeholder for PDF export - would use a library like DomPDF
        return response()->json(['message' => 'PDF export not implemented yet']);
    }
}
