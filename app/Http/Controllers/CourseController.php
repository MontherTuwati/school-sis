<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use App\Models\Course;

use Brian2694\Toastr\Facades\Toastr;

class CourseController extends Controller
{
    /** index page */
    public function courseList()
    {
        $courseList = Course::all();
        return view('courses.course_list',compact('courseList'));
    }

    /** Course add */
    public function courseAdd()
    {
        return view('courses.course_add');
    }

    /** save record */
    public function saveRecord(Request $request)
    {
        $request->validate([
            'title'       => 'required|string',
            'description' => 'required|string',
        ]);
        
        DB::beginTransaction();
        try {
                $saveRecord = new Course;
                $saveRecord->title          = $request->title;
                $saveRecord->description    = $request->description;
                $saveRecord->save();

                Toastr::success('Has been add successfully :)','Success');
                DB::commit();
            return redirect()->back();
            
        } catch(\Exception $e) {
            \Log::info($e);
            DB::rollback();
            Toastr::error('fail, Add new record:)','Error');
            return redirect()->back();
        }
    }

    /** Course edit view */
    public function courseEdit($course_code)
    {
        $courseEdit = Course::where('course_code',$course_code)->first();
        return view('courses.course_edit',compact('courseEdit'));
    }

    /** update record */
    public function updateRecord(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $updateRecord = [
                'title'             => $request->title,
                'description'       => $request->description,
            ];

            Course::where('course_code',$request->course_code)->update($updateRecord);
            Toastr::success('Has been update successfully :)','Success');
            DB::commit();
            return redirect()->back();
           
        } catch(\Exception $e) {
            \Log::info($e);
            DB::rollback();
            Toastr::error('Fail, update record:)','Error');
            return redirect()->back();
        }
    }

    /** delete record */
    public function deleteRecord(Request $request)
    {
        DB::beginTransaction();
        try {

            Course::where('course_code',$request->course_code)->delete();
            DB::commit();
            Toastr::success('Deleted record successfully :)','Success');
            return redirect()->back();
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Deleted record fail :)','Error');
            return redirect()->back();
        }
    }

}
