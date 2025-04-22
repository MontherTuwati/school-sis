<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use App\Models\Department;

class DepartmentController extends Controller
{
    /** department list */
    public function index()
    {
        $departments = Department::all();
        return view('department.index', compact('departments'));
    }

    /** add department page */
    public function create()
    {
        return view('department.create');
    }

    /** save record */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments',
            'description' => 'nullable|string',
            'head_of_department' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $department = new Department;
            $department->name = $request->name;
            $department->description = $request->description;
            $department->head_of_department = $request->head_of_department;
            $department->contact_email = $request->contact_email;
            $department->contact_phone = $request->contact_phone;
            $department->save();

            DB::commit();
            return redirect()->route('departments.index')->with('success', 'Department added successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to add department: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add department. Please try again.');
        }
    }

    /** edit record */
    public function edit($id)
    {
        $department = Department::findOrFail($id);
        return view('department.edit', compact('department'));
    }

    /** update record */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'description' => 'nullable|string',
            'head_of_department' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
        ]);

        DB::beginTransaction();
        try {
            $department = Department::findOrFail($id);
            $department->name = $request->name;
            $department->description = $request->description;
            $department->head_of_department = $request->head_of_department;
            $department->contact_email = $request->contact_email;
            $department->contact_phone = $request->contact_phone;
            $department->save();

            DB::commit();
            return redirect()->route('departments.index')->with('success', 'Department updated successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to update department: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update department. Please try again.');
        }
    }

    /** delete record */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $department = Department::findOrFail($id);
            
            // Check if department has associated teachers or students
            if ($department->teachers()->count() > 0 || $department->students()->count() > 0) {
                return redirect()->back()->with('error', 'Cannot delete department. It has associated teachers or students.');
            }
            
            $department->delete();
            
            DB::commit();
            return redirect()->route('departments.index')->with('success', 'Department deleted successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to delete department: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete department. Please try again.');
        }
    }

    /** view department details */
    public function show($id)
    {
        $department = Department::with(['teachers', 'students'])->findOrFail($id);
        return view('department.show', compact('department'));
    }
}
