<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Course;
use App\Models\Department;
use App\Models\Teacher;

class CourseController extends Controller
{
    /** course list */
    public function index()
    {
        $courses = Course::with(['department', 'teacher'])->get();
        return view('course.index', compact('courses'));
    }

    /** Course add */
    public function create()
    {
        $departments = Department::all();
        $teachers = Teacher::all();
        return view('course.create', compact('departments', 'teachers'));
    }

    /** save record */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses',
            'description' => 'required|string',
            'credits' => 'required|integer|min:1|max:10',
            'department_id' => 'required|exists:departments,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'semester' => 'required|string|max:20',
            'academic_year' => 'required|string|max:10',
        ]);
        
        DB::beginTransaction();
        try {
            $course = new Course;
            $course->title = $request->title;
            $course->code = $request->code;
            $course->description = $request->description;
            $course->credits = $request->credits;
            $course->department_id = $request->department_id;
            $course->teacher_id = $request->teacher_id;
            $course->semester = $request->semester;
            $course->academic_year = $request->academic_year;
            $course->is_active = $request->has('is_active');
            $course->save();

            DB::commit();
            return redirect()->route('courses.index')->with('success', 'Course added successfully!');
            
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to add course: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add course. Please try again.');
        }
    }

    /** Course edit view */
    public function edit($id)
    {
        $course = Course::findOrFail($id);
        $departments = Department::all();
        $teachers = Teacher::all();
        return view('course.edit', compact('course', 'departments', 'teachers'));
    }

    /** update record */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:courses,code,' . $id,
            'description' => 'required|string',
            'credits' => 'required|integer|min:1|max:10',
            'department_id' => 'required|exists:departments,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'semester' => 'required|string|max:20',
            'academic_year' => 'required|string|max:10',
        ]);

        DB::beginTransaction();
        try {
            $course = Course::findOrFail($id);
            $course->title = $request->title;
            $course->code = $request->code;
            $course->description = $request->description;
            $course->credits = $request->credits;
            $course->department_id = $request->department_id;
            $course->teacher_id = $request->teacher_id;
            $course->semester = $request->semester;
            $course->academic_year = $request->academic_year;
            $course->is_active = $request->has('is_active');
            $course->save();

            DB::commit();
            return redirect()->route('courses.index')->with('success', 'Course updated successfully!');
           
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to update course: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update course. Please try again.');
        }
    }

    /** delete record */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $course = Course::findOrFail($id);
            $course->delete();
            
            DB::commit();
            return redirect()->route('courses.index')->with('success', 'Course deleted successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to delete course: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete course. Please try again.');
        }
    }

    /** view course details */
    public function show($id)
    {
        $course = Course::with(['department', 'teacher'])->findOrFail($id);
        return view('course.show', compact('course'));
    }
}
