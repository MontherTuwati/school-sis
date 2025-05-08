<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Teacher;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /** attendance dashboard */
    public function index()
    {
        $today = Carbon::today();
        $stats = $this->getAttendanceStats($today);
        $recentAttendance = Attendance::with(['student', 'course'])
                                    ->whereDate('date', $today)
                                    ->orderBy('created_at', 'desc')
                                    ->limit(10)
                                    ->get();
        
        return view('attendance.index', compact('stats', 'recentAttendance', 'today'));
    }

    /** take attendance */
    public function take()
    {
        $courses = Course::with(['teacher', 'department'])->where('is_active', true)->get();
        $today = Carbon::today();
        
        return view('attendance.take', compact('courses', 'today'));
    }

    /** get students for course */
    public function getStudents(Request $request)
    {
        $courseId = $request->input('course_id');
        $date = $request->input('date', Carbon::today()->format('Y-m-d'));
        
        $course = Course::with(['students', 'teacher'])->findOrFail($courseId);
        $existingAttendance = Attendance::where('course_id', $courseId)
                                       ->whereDate('date', $date)
                                       ->get()
                                       ->keyBy('student_id');
        
        return response()->json([
            'course' => $course,
            'students' => $course->students,
            'existing_attendance' => $existingAttendance
        ]);
    }

    /** save attendance */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'date' => 'required|date',
            'attendance_data' => 'required|array',
            'attendance_data.*.student_id' => 'required|exists:students,id',
            'attendance_data.*.status' => 'required|in:present,absent,late,excused',
            'attendance_data.*.remarks' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $courseId = $request->course_id;
            $date = $request->date;
            
            // Delete existing attendance for this course and date
            Attendance::where('course_id', $courseId)
                     ->whereDate('date', $date)
                     ->delete();
            
            // Insert new attendance records
            foreach ($request->attendance_data as $data) {
                $attendance = new Attendance();
                $attendance->student_id = $data['student_id'];
                $attendance->course_id = $courseId;
                $attendance->date = $date;
                $attendance->status = $data['status'];
                $attendance->remarks = $data['remarks'] ?? null;
                $attendance->recorded_by = auth()->id();
                $attendance->save();
            }
            
            DB::commit();
            return redirect()->route('attendance.index')->with('success', 'Attendance recorded successfully!');
            
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to save attendance: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to save attendance. Please try again.');
        }
    }

    /** view attendance */
    public function view(Request $request)
    {
        $courses = Course::with(['teacher', 'department'])->get();
        $students = Student::with(['department'])->get();
        
        $selectedCourse = $request->input('course_id');
        $selectedStudent = $request->input('student_id');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $query = Attendance::with(['student', 'course']);
        
        if ($selectedCourse) {
            $query->where('course_id', $selectedCourse);
        }
        
        if ($selectedStudent) {
            $query->where('student_id', $selectedStudent);
        }
        
        $attendance = $query->whereBetween('date', [$startDate, $endDate])
                           ->orderBy('date', 'desc')
                           ->orderBy('created_at', 'desc')
                           ->get();
        
        $stats = $this->getFilteredAttendanceStats($attendance);
        
        return view('attendance.view', compact('courses', 'students', 'attendance', 'stats', 'selectedCourse', 'selectedStudent', 'startDate', 'endDate'));
    }

    /** edit attendance */
    public function edit($id)
    {
        $attendance = Attendance::with(['student', 'course'])->findOrFail($id);
        return view('attendance.edit', compact('attendance'));
    }

    /** update attendance */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:present,absent,late,excused',
            'remarks' => 'nullable|string|max:255',
        ]);

        try {
            $attendance = Attendance::findOrFail($id);
            $attendance->status = $request->status;
            $attendance->remarks = $request->remarks;
            $attendance->updated_by = auth()->id();
            $attendance->save();
            
            return redirect()->route('attendance.view')->with('success', 'Attendance updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to update attendance: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update attendance. Please try again.');
        }
    }

    /** delete attendance */
    public function destroy($id)
    {
        try {
            $attendance = Attendance::findOrFail($id);
            $attendance->delete();
            
            return redirect()->route('attendance.view')->with('success', 'Attendance record deleted successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to delete attendance: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete attendance record. Please try again.');
        }
    }

    /** attendance report */
    public function report()
    {
        $courses = Course::with(['teacher', 'department'])->get();
        $students = Student::with(['department'])->get();
        
        $stats = $this->getAttendanceReportStats();
        
        return view('attendance.report', compact('courses', 'students', 'stats'));
    }

    /** export attendance */
    public function export(Request $request)
    {
        $courseId = $request->input('course_id');
        $studentId = $request->input('student_id');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        $format = $request->input('format', 'csv');
        
        $query = Attendance::with(['student', 'course']);
        
        if ($courseId) {
            $query->where('course_id', $courseId);
        }
        
        if ($studentId) {
            $query->where('student_id', $studentId);
        }
        
        $attendance = $query->whereBetween('date', [$startDate, $endDate])
                           ->orderBy('date', 'desc')
                           ->get();
        
        if ($format === 'csv') {
            return $this->exportToCSV($attendance);
        } else {
            return $this->exportToPDF($attendance);
        }
    }

    /** get attendance statistics */
    private function getAttendanceStats($date = null)
    {
        if (!$date) {
            $date = Carbon::today();
        }
        
        $totalRecords = Attendance::whereDate('date', $date)->count();
        $present = Attendance::whereDate('date', $date)->where('status', 'present')->count();
        $absent = Attendance::whereDate('date', $date)->where('status', 'absent')->count();
        $late = Attendance::whereDate('date', $date)->where('status', 'late')->count();
        $excused = Attendance::whereDate('date', $date)->where('status', 'excused')->count();
        
        $attendanceRate = $totalRecords > 0 ? round((($present + $late) / $totalRecords) * 100, 2) : 0;
        
        // Course-wise attendance
        $courseAttendance = Course::withCount(['attendance' => function($query) use ($date) {
            $query->whereDate('date', $date);
        }])->get();
        
        return [
            'total_records' => $totalRecords,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'attendance_rate' => $attendanceRate,
            'course_attendance' => $courseAttendance,
        ];
    }

    /** get filtered attendance statistics */
    private function getFilteredAttendanceStats($attendance)
    {
        $totalRecords = $attendance->count();
        $present = $attendance->where('status', 'present')->count();
        $absent = $attendance->where('status', 'absent')->count();
        $late = $attendance->where('status', 'late')->count();
        $excused = $attendance->where('status', 'excused')->count();
        
        $attendanceRate = $totalRecords > 0 ? round((($present + $late) / $totalRecords) * 100, 2) : 0;
        
        return [
            'total_records' => $totalRecords,
            'present' => $present,
            'absent' => $absent,
            'late' => $late,
            'excused' => $excused,
            'attendance_rate' => $attendanceRate,
        ];
    }

    /** get attendance report statistics */
    private function getAttendanceReportStats()
    {
        $currentMonth = Carbon::now()->startOfMonth();
        
        // Monthly attendance trends
        $monthlyTrends = [];
        for ($i = 0; $i < 6; $i++) {
            $month = $currentMonth->copy()->subMonths($i);
            $total = Attendance::whereYear('date', $month->year)
                              ->whereMonth('date', $month->month)
                              ->count();
            $present = Attendance::whereYear('date', $month->year)
                                ->whereMonth('date', $month->month)
                                ->whereIn('status', ['present', 'late'])
                                ->count();
            
            $rate = $total > 0 ? round(($present / $total) * 100, 2) : 0;
            $monthlyTrends[$month->format('M Y')] = $rate;
        }
        
        // Course-wise attendance rates
        $courseRates = Course::withCount(['attendance as total_attendance'])
                            ->withCount(['attendance as present_attendance' => function($query) {
                                $query->whereIn('status', ['present', 'late']);
                            }])
                            ->get()
                            ->map(function($course) {
                                $rate = $course->total_attendance > 0 ? 
                                    round(($course->present_attendance / $course->total_attendance) * 100, 2) : 0;
                                return [
                                    'name' => $course->name,
                                    'rate' => $rate,
                                    'total' => $course->total_attendance,
                                    'present' => $course->present_attendance
                                ];
                            });
        
        // Student attendance rates
        $studentRates = Student::withCount(['attendance as total_attendance'])
                              ->withCount(['attendance as present_attendance' => function($query) {
                                  $query->whereIn('status', ['present', 'late']);
                              }])
                              ->get()
                              ->map(function($student) {
                                  $rate = $student->total_attendance > 0 ? 
                                      round(($student->present_attendance / $student->total_attendance) * 100, 2) : 0;
                                  return [
                                      'name' => $student->first_name . ' ' . $student->last_name,
                                      'rate' => $rate,
                                      'total' => $student->total_attendance,
                                      'present' => $student->present_attendance
                                  ];
                              });
        
        return [
            'monthly_trends' => $monthlyTrends,
            'course_rates' => $courseRates,
            'student_rates' => $studentRates,
        ];
    }

    /** export to CSV */
    private function exportToCSV($attendance)
    {
        $filename = 'attendance_report_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($attendance) {
            $file = fopen('php://output', 'w');
            
            // Write headers
            fputcsv($file, ['Date', 'Student', 'Course', 'Status', 'Remarks', 'Recorded By']);
            
            // Write data
            foreach ($attendance as $record) {
                fputcsv($file, [
                    $record->date,
                    $record->student ? $record->student->first_name . ' ' . $record->student->last_name : 'N/A',
                    $record->course ? $record->course->name : 'N/A',
                    ucfirst($record->status),
                    $record->remarks ?? '',
                    $record->recorded_by ? 'User ID: ' . $record->recorded_by : 'N/A'
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    /** export to PDF */
    private function exportToPDF($attendance)
    {
        // Placeholder for PDF export - would use a library like DomPDF
        return response()->json(['message' => 'PDF export not implemented yet']);
    }
}
