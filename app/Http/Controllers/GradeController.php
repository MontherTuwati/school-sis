<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Grade;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Course;

class GradeController extends Controller
{
    /** grade list */
    public function index()
    {
        $grades = Grade::with(['student', 'subject', 'course'])->get();
        return view('grade.index', compact('grades'));
    }

    /** grade add */
    public function create()
    {
        $students = Student::all();
        $subjects = Subject::all();
        $courses = Course::all();
        return view('grade.create', compact('students', 'subjects', 'courses'));
    }

    /** save record */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'course_id' => 'required|exists:courses,id',
            'semester' => 'required|string|max:20',
            'academic_year' => 'required|string|max:10',
            'assignment_score' => 'required|numeric|min:0|max:100',
            'midterm_score' => 'required|numeric|min:0|max:100',
            'final_score' => 'required|numeric|min:0|max:100',
            'attendance_score' => 'required|numeric|min:0|max:100',
            'remarks' => 'nullable|string|max:500',
        ]);
        
        DB::beginTransaction();
        try {
            // Calculate total score and letter grade
            $total_score = ($request->assignment_score * 0.2) + 
                          ($request->midterm_score * 0.3) + 
                          ($request->final_score * 0.4) + 
                          ($request->attendance_score * 0.1);
            
            $letter_grade = $this->calculateLetterGrade($total_score);
            $gpa = $this->calculateGPA($letter_grade);

            $grade = new Grade;
            $grade->student_id = $request->student_id;
            $grade->subject_id = $request->subject_id;
            $grade->course_id = $request->course_id;
            $grade->semester = $request->semester;
            $grade->academic_year = $request->academic_year;
            $grade->assignment_score = $request->assignment_score;
            $grade->midterm_score = $request->midterm_score;
            $grade->final_score = $request->final_score;
            $grade->attendance_score = $request->attendance_score;
            $grade->total_score = round($total_score, 2);
            $grade->letter_grade = $letter_grade;
            $grade->gpa = $gpa;
            $grade->remarks = $request->remarks;
            $grade->save();

            DB::commit();
            return redirect()->route('grades.index')->with('success', 'Grade added successfully!');
            
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to add grade: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add grade. Please try again.');
        }
    }

    /** grade edit view */
    public function edit($id)
    {
        $grade = Grade::findOrFail($id);
        $students = Student::all();
        $subjects = Subject::all();
        $courses = Course::all();
        return view('grade.edit', compact('grade', 'students', 'subjects', 'courses'));
    }

    /** update record */
    public function update(Request $request, $id)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id',
            'subject_id' => 'required|exists:subjects,id',
            'course_id' => 'required|exists:courses,id',
            'semester' => 'required|string|max:20',
            'academic_year' => 'required|string|max:10',
            'assignment_score' => 'required|numeric|min:0|max:100',
            'midterm_score' => 'required|numeric|min:0|max:100',
            'final_score' => 'required|numeric|min:0|max:100',
            'attendance_score' => 'required|numeric|min:0|max:100',
            'remarks' => 'nullable|string|max:500',
        ]);

        DB::beginTransaction();
        try {
            $grade = Grade::findOrFail($id);
            
            // Calculate total score and letter grade
            $total_score = ($request->assignment_score * 0.2) + 
                          ($request->midterm_score * 0.3) + 
                          ($request->final_score * 0.4) + 
                          ($request->attendance_score * 0.1);
            
            $letter_grade = $this->calculateLetterGrade($total_score);
            $gpa = $this->calculateGPA($letter_grade);

            $grade->student_id = $request->student_id;
            $grade->subject_id = $request->subject_id;
            $grade->course_id = $request->course_id;
            $grade->semester = $request->semester;
            $grade->academic_year = $request->academic_year;
            $grade->assignment_score = $request->assignment_score;
            $grade->midterm_score = $request->midterm_score;
            $grade->final_score = $request->final_score;
            $grade->attendance_score = $request->attendance_score;
            $grade->total_score = round($total_score, 2);
            $grade->letter_grade = $letter_grade;
            $grade->gpa = $gpa;
            $grade->remarks = $request->remarks;
            $grade->save();

            DB::commit();
            return redirect()->route('grades.index')->with('success', 'Grade updated successfully!');
           
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to update grade: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update grade. Please try again.');
        }
    }

    /** delete record */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $grade = Grade::findOrFail($id);
            $grade->delete();
            
            DB::commit();
            return redirect()->route('grades.index')->with('success', 'Grade deleted successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to delete grade: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete grade. Please try again.');
        }
    }

    /** view grade details */
    public function show($id)
    {
        $grade = Grade::with(['student', 'subject', 'course'])->findOrFail($id);
        return view('grade.show', compact('grade'));
    }

    /** student transcript */
    public function transcript($student_id)
    {
        $student = Student::with(['grades.subject', 'grades.course'])->findOrFail($student_id);
        $grades = $student->grades()->orderBy('academic_year', 'desc')
                                   ->orderBy('semester', 'desc')
                                   ->get();
        
        // Calculate cumulative GPA
        $totalGPA = $grades->sum('gpa');
        $totalCredits = $grades->count();
        $cumulativeGPA = $totalCredits > 0 ? round($totalGPA / $totalCredits, 2) : 0;
        
        return view('grade.transcript', compact('student', 'grades', 'cumulativeGPA'));
    }

    /** calculate letter grade */
    private function calculateLetterGrade($score)
    {
        if ($score >= 90) return 'A+';
        if ($score >= 85) return 'A';
        if ($score >= 80) return 'A-';
        if ($score >= 75) return 'B+';
        if ($score >= 70) return 'B';
        if ($score >= 65) return 'B-';
        if ($score >= 60) return 'C+';
        if ($score >= 55) return 'C';
        if ($score >= 50) return 'C-';
        if ($score >= 45) return 'D+';
        if ($score >= 40) return 'D';
        return 'F';
    }

    /** calculate GPA */
    private function calculateGPA($letter_grade)
    {
        $gpa_map = [
            'A+' => 4.0, 'A' => 4.0, 'A-' => 3.7,
            'B+' => 3.3, 'B' => 3.0, 'B-' => 2.7,
            'C+' => 2.3, 'C' => 2.0, 'C-' => 1.7,
            'D+' => 1.3, 'D' => 1.0, 'F' => 0.0
        ];
        
        return $gpa_map[$letter_grade] ?? 0.0;
    }
}
