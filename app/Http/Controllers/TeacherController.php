<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Teacher;
use App\Models\Department;

class TeacherController extends Controller
{
    /** teacher list */
    public function index()
    {
        $teachers = Teacher::with(['user', 'department'])->get();
        return view('teacher.index', compact('teachers'));
    }

    /** teacher grid view */
    public function grid()
    {
        $teachers = Teacher::with(['user', 'department'])->get();
        return view('teacher.grid', compact('teachers'));
    }

    /** add teacher page */
    public function create()
    {
        $users = User::where('role', 'teacher')->get();
        $departments = Department::all();
        return view('teacher.create', compact('users', 'departments'));
    }

    /** save record */
    public function store(Request $request)
    {
        $request->validate([
            'full_name'     => 'required|string',
            'gender'        => 'required|string',
            'experience'    => 'required|string',
            'qualification' => 'required|string',
            'address'       => 'required|string',
            'city'          => 'required|string',
            'state'         => 'required|string',
            'zip_code'      => 'required|string',
            'country'       => 'required|string',
            'department_id' => 'required|exists:departments,id',
        ]);

        DB::beginTransaction();
        try {
            $teacher = new Teacher;
            $teacher->full_name     = $request->full_name;
            $teacher->user_id       = $request->user_id;
            $teacher->gender        = $request->gender;
            $teacher->experience    = $request->experience;
            $teacher->department_id = $request->department_id;
            $teacher->qualification = $request->qualification;
            $teacher->address       = $request->address;
            $teacher->city          = $request->city;
            $teacher->state         = $request->state;
            $teacher->zip_code      = $request->zip_code;
            $teacher->country       = $request->country;
            $teacher->save();
   
            DB::commit();
            return redirect()->route('teachers.index')->with('success', 'Teacher added successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to add teacher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add teacher. Please try again.');
        }
    }

    /** edit record */
    public function edit($id)
    {
        $teacher = Teacher::with(['user', 'department'])->findOrFail($id);
        $users = User::where('role', 'teacher')->get();
        $departments = Department::all();
        return view('teacher.edit', compact('teacher', 'users', 'departments'));
    }

    /** update record teacher */
    public function update(Request $request, $id)
    {
        $request->validate([
            'full_name'     => 'required|string',
            'gender'        => 'required|string',
            'experience'    => 'required|string',
            'qualification' => 'required|string',
            'address'       => 'required|string',
            'city'          => 'required|string',
            'state'         => 'required|string',
            'zip_code'      => 'required|string',
            'country'       => 'required|string',
            'department_id' => 'required|exists:departments,id',
        ]);

        DB::beginTransaction();
        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->full_name     = $request->full_name;
            $teacher->user_id       = $request->user_id;
            $teacher->gender        = $request->gender;
            $teacher->experience    = $request->experience;
            $teacher->department_id = $request->department_id;
            $teacher->qualification = $request->qualification;
            $teacher->address       = $request->address;
            $teacher->city          = $request->city;
            $teacher->state         = $request->state;
            $teacher->zip_code      = $request->zip_code;
            $teacher->country       = $request->country;
            $teacher->save();

            DB::commit();
            return redirect()->route('teachers.index')->with('success', 'Teacher updated successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to update teacher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update teacher. Please try again.');
        }
    }

    /** delete record */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $teacher = Teacher::findOrFail($id);
            $teacher->delete();
            
            DB::commit();
            return redirect()->route('teachers.index')->with('success', 'Teacher deleted successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to delete teacher: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete teacher. Please try again.');
        }
    }

    /** view teacher details */
    public function show($id)
    {
        $teacher = Teacher::with(['user', 'department'])->findOrFail($id);
        return view('teacher.show', compact('teacher'));
    }
}
