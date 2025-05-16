<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Timetable;
use App\Models\TimetableSlot;
use App\Models\Course;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Student;
use App\Models\ClassRoom;
use Carbon\Carbon;

class TimetableController extends Controller
{
    /** timetable dashboard */
    public function index()
    {
        $stats = $this->getTimetableStats();
        $recentTimetables = Timetable::with(['course', 'teacher'])
                                   ->orderBy('created_at', 'desc')
                                   ->limit(10)
                                   ->get();
        
        return view('timetable.index', compact('stats', 'recentTimetables'));
    }

    /** timetables list */
    public function timetables()
    {
        $timetables = Timetable::with(['course', 'teacher', 'subject'])->get();
        $courses = Course::where('is_active', true)->get();
        $teachers = Teacher::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $classrooms = ClassRoom::where('is_active', true)->get();
        
        return view('timetable.timetables', compact('timetables', 'courses', 'teachers', 'subjects', 'classrooms'));
    }

    /** create timetable */
    public function create()
    {
        $courses = Course::where('is_active', true)->get();
        $teachers = Teacher::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $classrooms = ClassRoom::where('is_active', true)->get();
        
        return view('timetable.create', compact('courses', 'teachers', 'subjects', 'classrooms'));
    }

    /** store timetable */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'classroom_id' => 'required|exists:class_rooms,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'semester' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
            'is_active' => 'boolean',
        ]);

        try {
            // Check for time conflicts
            $conflict = Timetable::where('day_of_week', $request->day_of_week)
                               ->where('classroom_id', $request->classroom_id)
                               ->where(function($query) use ($request) {
                                   $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                                         ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                                         ->orWhere(function($q) use ($request) {
                                             $q->where('start_time', '<=', $request->start_time)
                                               ->where('end_time', '>=', $request->end_time);
                                         });
                               })
                               ->exists();

            if ($conflict) {
                return redirect()->back()->with('error', 'Time slot conflicts with existing timetable.');
            }

            // Check teacher availability
            $teacherConflict = Timetable::where('day_of_week', $request->day_of_week)
                                      ->where('teacher_id', $request->teacher_id)
                                      ->where(function($query) use ($request) {
                                          $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                                                ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                                                ->orWhere(function($q) use ($request) {
                                                    $q->where('start_time', '<=', $request->start_time)
                                                      ->where('end_time', '>=', $request->end_time);
                                                });
                                      })
                                      ->exists();

            if ($teacherConflict) {
                return redirect()->back()->with('error', 'Teacher is not available during this time slot.');
            }

            $timetable = new Timetable();
            $timetable->course_id = $request->course_id;
            $timetable->subject_id = $request->subject_id;
            $timetable->teacher_id = $request->teacher_id;
            $timetable->classroom_id = $request->classroom_id;
            $timetable->day_of_week = $request->day_of_week;
            $timetable->start_time = $request->start_time;
            $timetable->end_time = $request->end_time;
            $timetable->semester = $request->semester;
            $timetable->academic_year = $request->academic_year;
            $timetable->is_active = $request->boolean('is_active');
            $timetable->created_by = auth()->id();
            $timetable->save();
            
            return redirect()->route('timetable.timetables')->with('success', 'Timetable created successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to create timetable: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create timetable. Please try again.');
        }
    }

    /** edit timetable */
    public function edit($id)
    {
        $timetable = Timetable::with(['course', 'teacher', 'subject', 'classroom'])->findOrFail($id);
        $courses = Course::where('is_active', true)->get();
        $teachers = Teacher::where('is_active', true)->get();
        $subjects = Subject::where('is_active', true)->get();
        $classrooms = ClassRoom::where('is_active', true)->get();
        
        return view('timetable.edit', compact('timetable', 'courses', 'teachers', 'subjects', 'classrooms'));
    }

    /** update timetable */
    public function update(Request $request, $id)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'subject_id' => 'required|exists:subjects,id',
            'teacher_id' => 'required|exists:teachers,id',
            'classroom_id' => 'required|exists:class_rooms,id',
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'semester' => 'required|string|max:50',
            'academic_year' => 'required|string|max:20',
            'is_active' => 'boolean',
        ]);

        try {
            $timetable = Timetable::findOrFail($id);
            
            // Check for time conflicts (excluding current timetable)
            $conflict = Timetable::where('id', '!=', $id)
                               ->where('day_of_week', $request->day_of_week)
                               ->where('classroom_id', $request->classroom_id)
                               ->where(function($query) use ($request) {
                                   $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                                         ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                                         ->orWhere(function($q) use ($request) {
                                             $q->where('start_time', '<=', $request->start_time)
                                               ->where('end_time', '>=', $request->end_time);
                                         });
                               })
                               ->exists();

            if ($conflict) {
                return redirect()->back()->with('error', 'Time slot conflicts with existing timetable.');
            }

            // Check teacher availability (excluding current timetable)
            $teacherConflict = Timetable::where('id', '!=', $id)
                                      ->where('day_of_week', $request->day_of_week)
                                      ->where('teacher_id', $request->teacher_id)
                                      ->where(function($query) use ($request) {
                                          $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                                                ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                                                ->orWhere(function($q) use ($request) {
                                                    $q->where('start_time', '<=', $request->start_time)
                                                      ->where('end_time', '>=', $request->end_time);
                                                });
                                      })
                                      ->exists();

            if ($teacherConflict) {
                return redirect()->back()->with('error', 'Teacher is not available during this time slot.');
            }

            $timetable->course_id = $request->course_id;
            $timetable->subject_id = $request->subject_id;
            $timetable->teacher_id = $request->teacher_id;
            $timetable->classroom_id = $request->classroom_id;
            $timetable->day_of_week = $request->day_of_week;
            $timetable->start_time = $request->start_time;
            $timetable->end_time = $request->end_time;
            $timetable->semester = $request->semester;
            $timetable->academic_year = $request->academic_year;
            $timetable->is_active = $request->boolean('is_active');
            $timetable->save();
            
            return redirect()->route('timetable.timetables')->with('success', 'Timetable updated successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to update timetable: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update timetable. Please try again.');
        }
    }

    /** delete timetable */
    public function destroy($id)
    {
        try {
            $timetable = Timetable::findOrFail($id);
            $timetable->delete();
            
            return redirect()->route('timetable.timetables')->with('success', 'Timetable deleted successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to delete timetable: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete timetable. Please try again.');
        }
    }

    /** view timetable */
    public function view($id)
    {
        $timetable = Timetable::with(['course', 'teacher', 'subject', 'classroom'])->findOrFail($id);
        
        return view('timetable.view', compact('timetable'));
    }

    /** weekly timetable view */
    public function weekly()
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $timeSlots = $this->generateTimeSlots();
        
        $weeklyTimetable = [];
        foreach ($days as $day) {
            $weeklyTimetable[$day] = Timetable::with(['course', 'teacher', 'subject', 'classroom'])
                                            ->where('day_of_week', $day)
                                            ->where('is_active', true)
                                            ->orderBy('start_time')
                                            ->get()
                                            ->groupBy('start_time');
        }
        
        return view('timetable.weekly', compact('weeklyTimetable', 'days', 'timeSlots'));
    }

    /** teacher timetable */
    public function teacherTimetable($teacherId = null)
    {
        $teacherId = $teacherId ?? auth()->user()->teacher_id ?? request('teacher_id');
        
        if (!$teacherId) {
            return redirect()->back()->with('error', 'Teacher ID is required.');
        }
        
        $teacher = Teacher::findOrFail($teacherId);
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        $teacherTimetable = [];
        foreach ($days as $day) {
            $teacherTimetable[$day] = Timetable::with(['course', 'subject', 'classroom'])
                                            ->where('teacher_id', $teacherId)
                                            ->where('day_of_week', $day)
                                            ->where('is_active', true)
                                            ->orderBy('start_time')
                                            ->get();
        }
        
        return view('timetable.teacher', compact('teacherTimetable', 'teacher', 'days'));
    }

    /** student timetable */
    public function studentTimetable($studentId = null)
    {
        $studentId = $studentId ?? request('student_id');
        
        if (!$studentId) {
            return redirect()->back()->with('error', 'Student ID is required.');
        }
        
        $student = Student::findOrFail($studentId);
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        
        $studentTimetable = [];
        foreach ($days as $day) {
            $studentTimetable[$day] = Timetable::with(['course', 'subject', 'teacher', 'classroom'])
                                            ->where('course_id', $student->course_id)
                                            ->where('day_of_week', $day)
                                            ->where('is_active', true)
                                            ->orderBy('start_time')
                                            ->get();
        }
        
        return view('timetable.student', compact('studentTimetable', 'student', 'days'));
    }

    /** classroom management */
    public function classrooms()
    {
        $classrooms = ClassRoom::withCount('timetables')->get();
        
        return view('timetable.classrooms', compact('classrooms'));
    }

    /** store classroom */
    public function storeClassroom(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:class_rooms',
            'capacity' => 'required|integer|min:1',
            'building' => 'nullable|string|max:100',
            'floor' => 'nullable|string|max:20',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        try {
            $classroom = new ClassRoom();
            $classroom->name = $request->name;
            $classroom->capacity = $request->capacity;
            $classroom->building = $request->building;
            $classroom->floor = $request->floor;
            $classroom->description = $request->description;
            $classroom->is_active = $request->boolean('is_active');
            $classroom->save();
            
            return redirect()->route('timetable.classrooms')->with('success', 'Classroom added successfully!');
            
        } catch (\Exception $e) {
            \Log::error('Failed to add classroom: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add classroom. Please try again.');
        }
    }

    /** timetable reports */
    public function reports()
    {
        $stats = $this->getTimetableReportStats();
        
        return view('timetable.reports', compact('stats'));
    }

    /** export timetable data */
    public function export(Request $request)
    {
        $type = $request->input('type', 'timetables');
        $format = $request->input('format', 'csv');
        
        switch ($type) {
            case 'timetables':
                $data = Timetable::with(['course', 'teacher', 'subject', 'classroom'])->get();
                break;
            case 'classrooms':
                $data = ClassRoom::withCount('timetables')->get();
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

    /** get timetable statistics */
    private function getTimetableStats()
    {
        $totalTimetables = Timetable::count();
        $activeTimetables = Timetable::where('is_active', true)->count();
        
        $totalClassrooms = ClassRoom::count();
        $activeClassrooms = ClassRoom::where('is_active', true)->count();
        
        $totalTeachers = Teacher::where('is_active', true)->count();
        $teachersWithTimetables = Teacher::whereHas('timetables')->count();
        
        $totalCourses = Course::where('is_active', true)->count();
        $coursesWithTimetables = Course::whereHas('timetables')->count();
        
        // Recent activity
        $recentTimetables = Timetable::where('created_at', '>=', Carbon::now()->subDays(7))->count();
        
        return [
            'total_timetables' => $totalTimetables,
            'active_timetables' => $activeTimetables,
            'total_classrooms' => $totalClassrooms,
            'active_classrooms' => $activeClassrooms,
            'total_teachers' => $totalTeachers,
            'teachers_with_timetables' => $teachersWithTimetables,
            'total_courses' => $totalCourses,
            'courses_with_timetables' => $coursesWithTimetables,
            'recent_timetables' => $recentTimetables,
        ];
    }

    /** get timetable report statistics */
    private function getTimetableReportStats()
    {
        // Timetable distribution by day
        $dayDistribution = Timetable::selectRaw('day_of_week, COUNT(*) as count')
                                  ->groupBy('day_of_week')
                                  ->get();
        
        // Timetable distribution by teacher
        $teacherDistribution = Teacher::withCount('timetables')
                                    ->orderBy('timetables_count', 'desc')
                                    ->limit(10)
                                    ->get();
        
        // Classroom utilization
        $classroomUtilization = ClassRoom::withCount('timetables')
                                       ->orderBy('timetables_count', 'desc')
                                       ->get();
        
        // Course-wise timetable distribution
        $courseDistribution = Course::withCount('timetables')
                                  ->orderBy('timetables_count', 'desc')
                                  ->get();
        
        return [
            'day_distribution' => $dayDistribution,
            'teacher_distribution' => $teacherDistribution,
            'classroom_utilization' => $classroomUtilization,
            'course_distribution' => $courseDistribution,
        ];
    }

    /** generate time slots */
    private function generateTimeSlots()
    {
        $slots = [];
        $start = Carbon::createFromTime(8, 0, 0); // 8:00 AM
        $end = Carbon::createFromTime(18, 0, 0); // 6:00 PM
        
        while ($start < $end) {
            $slots[] = $start->format('H:i');
            $start->addMinutes(60); // 1-hour slots
        }
        
        return $slots;
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
                case 'timetables':
                    fputcsv($file, ['Course', 'Subject', 'Teacher', 'Classroom', 'Day', 'Start Time', 'End Time', 'Semester', 'Academic Year', 'Status']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->course ? $item->course->name : 'N/A',
                            $item->subject ? $item->subject->name : 'N/A',
                            $item->teacher ? $item->teacher->first_name . ' ' . $item->teacher->last_name : 'N/A',
                            $item->classroom ? $item->classroom->name : 'N/A',
                            ucfirst($item->day_of_week),
                            $item->start_time,
                            $item->end_time,
                            $item->semester,
                            $item->academic_year,
                            $item->is_active ? 'Active' : 'Inactive'
                        ]);
                    }
                    break;
                case 'classrooms':
                    fputcsv($file, ['Name', 'Capacity', 'Building', 'Floor', 'Timetables Count', 'Status']);
                    foreach ($data as $item) {
                        fputcsv($file, [
                            $item->name,
                            $item->capacity,
                            $item->building ?? 'N/A',
                            $item->floor ?? 'N/A',
                            $item->timetables_count,
                            $item->is_active ? 'Active' : 'Inactive'
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
