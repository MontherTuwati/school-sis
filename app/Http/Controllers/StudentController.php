<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\Subject;

class StudentController extends Controller
{
    /** index page student list */
    public function index()
    {
        $studentList = Student::all();
        return view('student.index', compact('studentList'));
    }

    /** student count page */
    public function count()
    {
        $studentCount = Student::count();
        return view('student.count', compact('studentCount'));
    }

    /** student grid view */
    public function grid()
    {
        $studentList = Student::all();
        return view('student.grid', compact('studentList'));
    }

    /** student add page */
    public function create()
    {
        $departments = Department::all();
        $subjects = Subject::all();
        return view('student.create', compact('departments', 'subjects'));
    }
    
    /** student save record */
    public function store(Request $request)
    {
        $request->validate([
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'gender'            => 'required|not_in:0',
            'date_of_birth'     => 'required|string',
            'email'             => 'required|email',
            'phone_number'      => 'required',
            'guardian_name'     => 'required|string',
            'guardian_number'   => 'required|string',
            'address'           => 'required|string',
            'subjects'          => 'required',
            'nation_id'         => 'required|unique:students',
            'semester'          => 'required|string',
            'departments'       => 'required',
            'upload'            => 'image',
        ]);
        
        DB::beginTransaction();
        try {
            $upload_file = null;
            if ($request->hasFile('upload')) {
                $upload_file = time() . '_' . $request->upload->getClientOriginalName();
                $request->upload->move(storage_path('/app/public/student-photos/'), $upload_file);
            }

            $student = new Student;
            $student->first_name       = $request->first_name;
            $student->last_name        = $request->last_name;
            $student->gender           = $request->gender;
            $student->date_of_birth    = $request->date_of_birth;
            $student->email            = $request->email;
            $student->phone_number     = $request->phone_number;
            $student->guardian_name    = $request->guardian_name;
            $student->guardian_number  = $request->guardian_number;
            $student->address          = $request->address;
            $student->subject_id       = $request->subjects;
            $student->semester         = $request->semester;
            $student->nation_id        = $request->nation_id;
            $student->department_id    = $request->departments;
            $student->upload           = $upload_file;
            $student->save();

            DB::commit();
            return redirect()->route('students.index')->with('success', 'Student added successfully!');
            
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to add student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add student. Please try again.');
        }
    }

    /** student edit page */
    public function edit($id)
    {
        $student = Student::findOrFail($id);
        $departments = Department::all();
        $subjects = Subject::all();
        return view('student.edit', compact('student', 'departments', 'subjects'));
    }

    /** student update record */
    public function update(Request $request, $id)
    {
        $request->validate([
            'first_name'        => 'required|string',
            'last_name'         => 'required|string',
            'gender'            => 'required|not_in:0',
            'date_of_birth'     => 'required|string',
            'email'             => 'required|email',
            'phone_number'      => 'required',
            'guardian_name'     => 'required|string',
            'guardian_number'   => 'required|string',
            'address'           => 'required|string',
            'subjects'          => 'required',
            'nation_id'         => 'required|unique:students,nation_id,' . $id,
            'semester'          => 'required|string',
            'departments'       => 'required',
            'upload'            => 'image',
        ]);

        DB::beginTransaction();
        try {
            $student = Student::findOrFail($id);
            
            if ($request->hasFile('upload')) {
                $upload_file = time() . '_' . $request->upload->getClientOriginalName();
                $request->upload->move(storage_path('/app/public/student-photos/'), $upload_file);
                $student->upload = $upload_file;
            }

            $student->first_name       = $request->first_name;
            $student->last_name        = $request->last_name;
            $student->gender           = $request->gender;
            $student->date_of_birth    = $request->date_of_birth;
            $student->email            = $request->email;
            $student->phone_number     = $request->phone_number;
            $student->guardian_name    = $request->guardian_name;
            $student->guardian_number  = $request->guardian_number;
            $student->address          = $request->address;
            $student->subject_id       = $request->subjects;
            $student->semester         = $request->semester;
            $student->nation_id        = $request->nation_id;
            $student->department_id    = $request->departments;
            $student->save();

            DB::commit();
            return redirect()->route('students.index')->with('success', 'Student updated successfully!');
            
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to update student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update student. Please try again.');
        }
    }

    /** student delete record */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $student = Student::findOrFail($id);
            $student->delete();
            
            DB::commit();
            return redirect()->route('students.index')->with('success', 'Student deleted successfully!');
            
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to delete student: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete student. Please try again.');
        }
    }

    /** student view page */
    public function show($id)
    {
        $student = Student::with(['department', 'subject'])->findOrFail($id);
        return view('student.show', compact('student'));
    }
}
