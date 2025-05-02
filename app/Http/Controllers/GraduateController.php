<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Student;
use App\Models\Graduate;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GraduateController extends Controller
{
    /** graduate list */
    public function index()
    {
        $graduates = Graduate::with(['student', 'department'])->get();
        return view('graduate.index', compact('graduates'));
    }

    /** graduate grid view */
    public function grid()
    {
        $graduates = Graduate::with(['student', 'department'])->get();
        return view('graduate.grid', compact('graduates'));
    }

    /** graduate add */
    public function create()
    {
        $students = Student::where('is_graduated', false)->get();
        return view('graduate.create', compact('students'));
    }

    /** save record */
    public function store(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,id|unique:graduates',
            'graduation_date' => 'required|date',
            'degree_type' => 'required|string|max:100',
            'major' => 'required|string|max:255',
            'gpa' => 'required|numeric|min:0|max:4',
            'honors' => 'nullable|string|max:255',
            'thesis_title' => 'nullable|string|max:500',
            'employment_status' => 'required|string|in:employed,unemployed,self-employed,continuing_education',
            'employer' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:1000',
        ]);
        
        DB::beginTransaction();
        try {
            $graduate = new Graduate;
            $graduate->student_id = $request->student_id;
            $graduate->graduation_date = $request->graduation_date;
            $graduate->degree_type = $request->degree_type;
            $graduate->major = $request->major;
            $graduate->gpa = $request->gpa;
            $graduate->honors = $request->honors;
            $graduate->thesis_title = $request->thesis_title;
            $graduate->employment_status = $request->employment_status;
            $graduate->employer = $request->employer;
            $graduate->job_title = $request->job_title;
            $graduate->salary_range = $request->salary_range;
            $graduate->contact_email = $request->contact_email;
            $graduate->contact_phone = $request->contact_phone;
            $graduate->address = $request->address;
            $graduate->remarks = $request->remarks;
            $graduate->save();

            // Mark student as graduated
            $student = Student::find($request->student_id);
            $student->is_graduated = true;
            $student->graduation_date = $request->graduation_date;
            $student->save();

            DB::commit();
            return redirect()->route('graduates.index')->with('success', 'Graduate record added successfully!');
            
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to add graduate: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add graduate record. Please try again.');
        }
    }

    /** graduate edit view */
    public function edit($id)
    {
        $graduate = Graduate::with('student')->findOrFail($id);
        return view('graduate.edit', compact('graduate'));
    }

    /** update record */
    public function update(Request $request, $id)
    {
        $request->validate([
            'graduation_date' => 'required|date',
            'degree_type' => 'required|string|max:100',
            'major' => 'required|string|max:255',
            'gpa' => 'required|numeric|min:0|max:4',
            'honors' => 'nullable|string|max:255',
            'thesis_title' => 'nullable|string|max:500',
            'employment_status' => 'required|string|in:employed,unemployed,self-employed,continuing_education',
            'employer' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'salary_range' => 'nullable|string|max:100',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'remarks' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $graduate = Graduate::findOrFail($id);
            $graduate->graduation_date = $request->graduation_date;
            $graduate->degree_type = $request->degree_type;
            $graduate->major = $request->major;
            $graduate->gpa = $request->gpa;
            $graduate->honors = $request->honors;
            $graduate->thesis_title = $request->thesis_title;
            $graduate->employment_status = $request->employment_status;
            $graduate->employer = $request->employer;
            $graduate->job_title = $request->job_title;
            $graduate->salary_range = $request->salary_range;
            $graduate->contact_email = $request->contact_email;
            $graduate->contact_phone = $request->contact_phone;
            $graduate->address = $request->address;
            $graduate->remarks = $request->remarks;
            $graduate->save();

            DB::commit();
            return redirect()->route('graduates.index')->with('success', 'Graduate record updated successfully!');
           
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to update graduate: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update graduate record. Please try again.');
        }
    }

    /** delete record */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $graduate = Graduate::findOrFail($id);
            
            // Mark student as not graduated
            $student = Student::find($graduate->student_id);
            $student->is_graduated = false;
            $student->graduation_date = null;
            $student->save();
            
            $graduate->delete();
            
            DB::commit();
            return redirect()->route('graduates.index')->with('success', 'Graduate record deleted successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to delete graduate: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete graduate record. Please try again.');
        }
    }

    /** view graduate details */
    public function show($id)
    {
        $graduate = Graduate::with(['student', 'student.department'])->findOrFail($id);
        return view('graduate.show', compact('graduate'));
    }

    /** alumni directory */
    public function alumni()
    {
        $graduates = Graduate::with(['student', 'student.department'])
                            ->where('employment_status', '!=', 'unemployed')
                            ->orderBy('graduation_date', 'desc')
                            ->get();
        return view('graduate.alumni', compact('graduates'));
    }

    /** employment statistics */
    public function statistics()
    {
        $totalGraduates = Graduate::count();
        $employed = Graduate::where('employment_status', 'employed')->count();
        $unemployed = Graduate::where('employment_status', 'unemployed')->count();
        $selfEmployed = Graduate::where('employment_status', 'self-employed')->count();
        $continuingEducation = Graduate::where('employment_status', 'continuing_education')->count();
        
        $avgGPA = Graduate::avg('gpa');
        $topGPA = Graduate::max('gpa');
        
        $stats = [
            'total' => $totalGraduates,
            'employed' => $employed,
            'unemployed' => $unemployed,
            'self_employed' => $selfEmployed,
            'continuing_education' => $continuingEducation,
            'avg_gpa' => round($avgGPA, 2),
            'top_gpa' => $topGPA,
        ];
        
        return view('graduate.statistics', compact('stats'));
    }
}
