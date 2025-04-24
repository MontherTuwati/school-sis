<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Subject;
use App\Models\Department;

class SubjectController extends Controller
{
    /** subject list */
    public function index()
    {
        $subjects = Subject::with('department')->get();
        return view('subject.index', compact('subjects'));
    }

    /** subject add */
    public function create()
    {
        $departments = Department::all();
        return view('subject.create', compact('departments'));
    }

    /** save record */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:10',
            'department_id' => 'required|exists:departments,id',
            'semester' => 'required|string|max:20',
            'academic_year' => 'required|string|max:10',
        ]);
        
        DB::beginTransaction();
        try {
            $subject = new Subject;
            $subject->name = $request->name;
            $subject->code = $request->code;
            $subject->description = $request->description;
            $subject->credits = $request->credits;
            $subject->department_id = $request->department_id;
            $subject->semester = $request->semester;
            $subject->academic_year = $request->academic_year;
            $subject->is_active = $request->has('is_active');
            $subject->save();

            DB::commit();
            return redirect()->route('subjects.index')->with('success', 'Subject added successfully!');
           
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to add subject: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add subject. Please try again.');
        }
    }

    /** subject edit view */
    public function edit($id)
    {
        $subject = Subject::findOrFail($id);
        $departments = Department::all();
        return view('subject.edit', compact('subject', 'departments'));
    }

    /** update record */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:subjects,code,' . $id,
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:1|max:10',
            'department_id' => 'required|exists:departments,id',
            'semester' => 'required|string|max:20',
            'academic_year' => 'required|string|max:10',
        ]);

        DB::beginTransaction();
        try {
            $subject = Subject::findOrFail($id);
            $subject->name = $request->name;
            $subject->code = $request->code;
            $subject->description = $request->description;
            $subject->credits = $request->credits;
            $subject->department_id = $request->department_id;
            $subject->semester = $request->semester;
            $subject->academic_year = $request->academic_year;
            $subject->is_active = $request->has('is_active');
            $subject->save();

            DB::commit();
            return redirect()->route('subjects.index')->with('success', 'Subject updated successfully!');
           
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to update subject: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update subject. Please try again.');
        }
    }

    /** delete record */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $subject = Subject::findOrFail($id);
            $subject->delete();
            
            DB::commit();
            return redirect()->route('subjects.index')->with('success', 'Subject deleted successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to delete subject: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete subject. Please try again.');
        }
    }

    /** view subject details */
    public function show($id)
    {
        $subject = Subject::with('department')->findOrFail($id);
        return view('subject.show', compact('subject'));
    }
}
