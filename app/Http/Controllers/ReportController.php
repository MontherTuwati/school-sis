<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\Department;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Graduate;
use App\Models\Event;
use Carbon\Carbon;

class ReportController extends Controller
{
    /** reports dashboard */
    public function index()
    {
        $stats = $this->getDashboardStats();
        return view('report.index', compact('stats'));
    }

    /** student reports */
    public function students()
    {
        $students = Student::with(['department', 'grades'])->get();
        $departments = Department::all();
        $stats = $this->getStudentStats();
        
        return view('report.students', compact('students', 'departments', 'stats'));
    }

    /** teacher reports */
    public function teachers()
    {
        $teachers = Teacher::with(['department', 'courses'])->get();
        $departments = Department::all();
        $stats = $this->getTeacherStats();
        
        return view('report.teachers', compact('teachers', 'departments', 'stats'));
    }

    /** academic reports */
    public function academic()
    {
        $grades = Grade::with(['student', 'course'])->get();
        $courses = Course::with(['teacher', 'department'])->get();
        $stats = $this->getAcademicStats();
        
        return view('report.academic', compact('grades', 'courses', 'stats'));
    }

    /** financial reports */
    public function financial()
    {
        // Placeholder for financial data - would integrate with actual financial system
        $stats = $this->getFinancialStats();
        
        return view('report.financial', compact('stats'));
    }

    /** attendance reports */
    public function attendance()
    {
        // Placeholder for attendance data - would integrate with actual attendance system
        $stats = $this->getAttendanceStats();
        
        return view('report.attendance', compact('stats'));
    }

    /** graduate reports */
    public function graduates()
    {
        $graduates = Graduate::with(['student', 'student.department'])->get();
        $stats = $this->getGraduateStats();
        
        return view('report.graduates', compact('graduates', 'stats'));
    }

    /** export report */
    public function export(Request $request)
    {
        $type = $request->input('type', 'students');
        $format = $request->input('format', 'pdf');
        
        switch ($type) {
            case 'students':
                $data = Student::with(['department', 'grades'])->get();
                break;
            case 'teachers':
                $data = Teacher::with(['department', 'courses'])->get();
                break;
            case 'grades':
                $data = Grade::with(['student', 'course'])->get();
                break;
            case 'graduates':
                $data = Graduate::with(['student', 'student.department'])->get();
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

    /** get dashboard statistics */
    private function getDashboardStats()
    {
        $totalStudents = Student::count();
        $totalTeachers = Teacher::count();
        $totalDepartments = Department::count();
        $totalCourses = Course::count();
        $totalGraduates = Graduate::count();
        $totalEvents = Event::count();
        
        // Recent activity
        $recentStudents = Student::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $recentGrades = Grade::where('created_at', '>=', Carbon::now()->subDays(30))->count();
        $upcomingEvents = Event::where('start_date', '>=', Carbon::now())->count();
        
        // Department distribution
        $departmentStats = Department::withCount('students', 'teachers')->get();
        
        // Gender distribution
        $maleStudents = Student::where('gender', 'male')->count();
        $femaleStudents = Student::where('gender', 'female')->count();
        
        // Employment rate for graduates
        $employedGraduates = Graduate::where('employment_status', 'employed')->count();
        $employmentRate = $totalGraduates > 0 ? round(($employedGraduates / $totalGraduates) * 100, 2) : 0;
        
        return [
            'total_students' => $totalStudents,
            'total_teachers' => $totalTeachers,
            'total_departments' => $totalDepartments,
            'total_courses' => $totalCourses,
            'total_graduates' => $totalGraduates,
            'total_events' => $totalEvents,
            'recent_students' => $recentStudents,
            'recent_grades' => $recentGrades,
            'upcoming_events' => $upcomingEvents,
            'department_stats' => $departmentStats,
            'male_students' => $maleStudents,
            'female_students' => $femaleStudents,
            'employment_rate' => $employmentRate,
        ];
    }

    /** get student statistics */
    private function getStudentStats()
    {
        $totalStudents = Student::count();
        $activeStudents = Student::where('is_active', true)->count();
        $graduatedStudents = Student::where('is_graduated', true)->count();
        
        // Age distribution
        $ageGroups = [
            '18-20' => Student::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 18 AND 20')->count(),
            '21-25' => Student::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 21 AND 25')->count(),
            '26-30' => Student::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 26 AND 30')->count(),
            '30+' => Student::whereRaw('TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) > 30')->count(),
        ];
        
        // Department distribution
        $departmentDistribution = Department::withCount('students')->get();
        
        // GPA statistics
        $avgGPA = Grade::avg('gpa');
        $maxGPA = Grade::max('gpa');
        $minGPA = Grade::min('gpa');
        
        return [
            'total_students' => $totalStudents,
            'active_students' => $activeStudents,
            'graduated_students' => $graduatedStudents,
            'age_groups' => $ageGroups,
            'department_distribution' => $departmentDistribution,
            'avg_gpa' => round($avgGPA, 2),
            'max_gpa' => $maxGPA,
            'min_gpa' => $minGPA,
        ];
    }

    /** get teacher statistics */
    private function getTeacherStats()
    {
        $totalTeachers = Teacher::count();
        $activeTeachers = Teacher::where('is_active', true)->count();
        
        // Department distribution
        $departmentDistribution = Department::withCount('teachers')->get();
        
        // Course load
        $avgCoursesPerTeacher = Course::count() / max($totalTeachers, 1);
        
        // Experience levels
        $experienceGroups = [
            '0-5 years' => Teacher::whereRaw('TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) BETWEEN 0 AND 5')->count(),
            '6-10 years' => Teacher::whereRaw('TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) BETWEEN 6 AND 10')->count(),
            '11-15 years' => Teacher::whereRaw('TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) BETWEEN 11 AND 15')->count(),
            '15+ years' => Teacher::whereRaw('TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) > 15')->count(),
        ];
        
        return [
            'total_teachers' => $totalTeachers,
            'active_teachers' => $activeTeachers,
            'department_distribution' => $departmentDistribution,
            'avg_courses_per_teacher' => round($avgCoursesPerTeacher, 1),
            'experience_groups' => $experienceGroups,
        ];
    }

    /** get academic statistics */
    private function getAcademicStats()
    {
        $totalGrades = Grade::count();
        $avgGPA = Grade::avg('gpa');
        
        // Grade distribution
        $gradeDistribution = [
            'A (90-100)' => Grade::where('gpa', '>=', 3.7)->count(),
            'B (80-89)' => Grade::whereBetween('gpa', [3.0, 3.69])->count(),
            'C (70-79)' => Grade::whereBetween('gpa', [2.0, 2.99])->count(),
            'D (60-69)' => Grade::whereBetween('gpa', [1.0, 1.99])->count(),
            'F (0-59)' => Grade::where('gpa', '<', 1.0)->count(),
        ];
        
        // Course performance
        $coursePerformance = Course::withCount('grades')
                                  ->withAvg('grades', 'gpa')
                                  ->get();
        
        // Department performance
        $departmentPerformance = Department::withCount('students')
                                          ->withAvg('students.grades', 'gpa')
                                          ->get();
        
        return [
            'total_grades' => $totalGrades,
            'avg_gpa' => round($avgGPA, 2),
            'grade_distribution' => $gradeDistribution,
            'course_performance' => $coursePerformance,
            'department_performance' => $departmentPerformance,
        ];
    }

    /** get financial statistics */
    private function getFinancialStats()
    {
        // Placeholder data - would integrate with actual financial system
        return [
            'total_revenue' => 0,
            'total_expenses' => 0,
            'net_profit' => 0,
            'tuition_fees' => 0,
            'scholarships' => 0,
            'other_income' => 0,
        ];
    }

    /** get attendance statistics */
    private function getAttendanceStats()
    {
        // Placeholder data - would integrate with actual attendance system
        return [
            'total_attendance' => 0,
            'attendance_rate' => 0,
            'absent_students' => 0,
            'late_students' => 0,
        ];
    }

    /** get graduate statistics */
    private function getGraduateStats()
    {
        $totalGraduates = Graduate::count();
        
        // Employment statistics
        $employmentStats = [
            'employed' => Graduate::where('employment_status', 'employed')->count(),
            'unemployed' => Graduate::where('employment_status', 'unemployed')->count(),
            'self_employed' => Graduate::where('employment_status', 'self-employed')->count(),
            'continuing_education' => Graduate::where('employment_status', 'continuing_education')->count(),
        ];
        
        // Average GPA
        $avgGPA = Graduate::avg('gpa');
        
        // Graduation by year
        $graduationByYear = Graduate::selectRaw('YEAR(graduation_date) as year, COUNT(*) as count')
                                   ->groupBy('year')
                                   ->orderBy('year', 'desc')
                                   ->get();
        
        // Salary ranges
        $salaryRanges = [
            'Under $30k' => Graduate::where('salary_range', 'like', '%30k%')->count(),
            '$30k-$50k' => Graduate::where('salary_range', 'like', '%50k%')->count(),
            '$50k-$75k' => Graduate::where('salary_range', 'like', '%75k%')->count(),
            '$75k+' => Graduate::where('salary_range', 'like', '%75k%')->count(),
        ];
        
        return [
            'total_graduates' => $totalGraduates,
            'employment_stats' => $employmentStats,
            'avg_gpa' => round($avgGPA, 2),
            'graduation_by_year' => $graduationByYear,
            'salary_ranges' => $salaryRanges,
        ];
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
            
            // Write headers based on type
            switch ($type) {
                case 'students':
                    fputcsv($file, ['ID', 'Name', 'Email', 'Department', 'Phone', 'Status']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->first_name . ' ' . $item->last_name,
                            $item->email,
                            $item->department ? $item->department->name : 'N/A',
                            $item->phone_number,
                            $item->is_active ? 'Active' : 'Inactive'
                        ]);
                    }
                    break;
                case 'teachers':
                    fputcsv($file, ['ID', 'Name', 'Email', 'Department', 'Phone', 'Status']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->id,
                            $item->first_name . ' ' . $item->last_name,
                            $item->email,
                            $item->department ? $item->department->name : 'N/A',
                            $item->phone_number,
                            $item->is_active ? 'Active' : 'Inactive'
                        ]);
                    }
                    break;
                case 'grades':
                    fputcsv($file, ['Student', 'Course', 'GPA', 'Letter Grade', 'Semester']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->student ? $item->student->first_name . ' ' . $item->student->last_name : 'N/A',
                            $item->course ? $item->course->name : 'N/A',
                            $item->gpa,
                            $this->calculateLetterGrade($item->gpa),
                            $item->semester
                        ]);
                    }
                    break;
                case 'graduates':
                    fputcsv($file, ['Name', 'Degree', 'Graduation Date', 'GPA', 'Employment Status', 'Employer']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->student ? $item->student->first_name . ' ' . $item->student->last_name : 'N/A',
                            $item->degree_type,
                            $item->graduation_date,
                            $item->gpa,
                            $item->employment_status,
                            $item->employer ?? 'N/A'
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

    /** calculate letter grade */
    private function calculateLetterGrade($gpa)
    {
        if ($gpa >= 3.7) return 'A';
        if ($gpa >= 3.0) return 'B';
        if ($gpa >= 2.0) return 'C';
        if ($gpa >= 1.0) return 'D';
        return 'F';
    }
}
