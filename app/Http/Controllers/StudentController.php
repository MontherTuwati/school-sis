<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Student;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\Models\Department;
use App\Models\Subject;


class StudentController extends Controller
{
    /** index page student list */
    public function student()
    {
        $studentList = Student::all();
        return view('student.student',compact('studentList'));
    }

    /** index page student list */
    public function studentCount()
    {
        $studentCount = Student::count();
        return view('student.studentCount', compact('studentCount'));
    }

    /** index page student grid */
    public function studentGrid()
    {
        $studentList = Student::all();
        return view('student.student-grid',compact('studentList'));
    }

    /** student add page */
    public function studentAdd()
    {
        $departments = Department::all();
        $subjects = Subject::all();
        return view('student.add-student', compact('departments','subjects'));
    }
    
    /** student save record */
    public function studentSave(Request $request)
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
            $upload_file = rand() . '.' . $request->upload->extension();
            $request->upload->move(storage_path('/app/public/student-photos/'), $upload_file);
            if(!empty($request->upload)) {
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
                $student->upload           = $request->image;
                $student->save();

                Toastr::success('Has been add successfully :)','Success');
                DB::commit();
            }

            return redirect()->back();
            
        } catch(\Exception $e) {
            DB::rollback();
            // Log the error for debugging
            \Log::error('Failed to add student: ' . $e->getMessage());
            Toastr::error('fail, Add new student  :)','Error');
            return redirect()->back();
        }
    }

    /** view for edit student */
    public function studentEdit($id)
    {
        $departments = Department::all();
        $studentEdit = Student::findOrFail($id);
        return view('student.edit-student',compact('studentEdit', 'departments'));
    }

    public function graduatedStudent()
    {
        $graduatedStudents = Student::where('graduated', 1)->get();

        return view('student.graduated-students', compact('graduatedStudents'));
    }

    /** update record */
    public function studentUpdate(Request $request)
    {
        DB::beginTransaction();
        try {

            if (!empty($request->upload)) {
                unlink(storage_path('app/public/student-photos/'.$request->image_hidden));
                $upload_file = rand() . '.' . $request->upload->extension();
                $request->upload->move(storage_path('app/public/student-photos/'), $upload_file);
            } else {
                $upload_file = $request->image_hidden;
            }
            
            $updateRecord = [
                'upload' => $upload_file,
            ];
            Student::where('id',$request->id)->update($updateRecord);
            
            Toastr::success('Has been update successfully :)','Success');
            DB::commit();
            return redirect()->back();
            
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('fail, update student  :)','Error');
            return redirect()->back();
        }
    }

    /** student delete */
    public function studentDelete(Request $request)
    {
        DB::beginTransaction();
        try {
           
            if (!empty($request->id)) {
                Student::destroy($request->id);
                unlink(storage_path('app/public/student-photos/'.$request->upload));
                DB::commit();
                Toastr::success('Student deleted successfully :)','Success');
                return redirect()->back();
            }
    
        } catch(\Exception $e) {
            DB::rollback();
            Toastr::error('Student deleted fail :)','Error');
            return redirect()->back();
        }
    }

    /** student profile page */
    public function studentProfile($id)
    {
        $student = Student::findOrFail($id);
        $grades = $student->grades;
        $transcript = $student->transcript;
        $enrollments = $student->enrollments;
        $departments = $student->departments;
        $subjects = $student->subjects;

        $studentProfile = Student::where('id',$id)->first();
        return view('student.student-profile',compact('studentProfile','grades','transcript','enrollments','departments','subjects', 'student'));
    }

    /** get students data */
    public function getStudentsData(Request $request)
    {
        $draw            = $request->get('draw');
        $start           = $request->get("start");
        $rowPerPage      = $request->get("length"); // total number of rows per page
        $columnIndex_arr = $request->get('order');
        $columnName_arr  = $request->get('columns');
        $order_arr       = $request->get('order');
        $search_arr      = $request->get('search');

        $columnIndex     = $columnIndex_arr[0]['column']; // Column index
        $columnName      = $columnName_arr[$columnIndex]['data']; // Column name
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchValue     = $search_arr['value']; // Search value

        $students =  DB::table('students');
        $students = Student::with('departments');
        $students->leftJoin('departments', 'students.department_id', '=', 'departments.id');
        $totalRecords = $students->count();

        $totalRecordsWithFilter = $students->where(function ($query) use ($searchValue) {
            $query->where('student_id', 'like', '%' . $searchValue . '%');
            $query->orWhere('first_name', 'like', '%' . $searchValue . '%');
            $query->orWhere('last_name', 'like', '%' . $searchValue . '%');
            $query->orWhere('email', 'like', '%' . $searchValue . '%');
            $query->orWhere('phone_number', 'like', '%' . $searchValue . '%');
        })->count();

        if ($columnName == 'first_name') {
            $columnName = 'students.first_name';
        } elseif ($columnName == 'last_name') {
            $columnName = 'students.last_name';
        } elseif ($columnName == 'departments') {
            $columnName = 'departments.department_name';
        }

        $records = $students->orderBy($columnName, $columnSortOrder)
            ->where(function ($query) use ($searchValue) {
                $query->where('student_id', 'like', '%' . $searchValue . '%');
                $query->orWhere('first_name', 'like', '%' . $searchValue . '%');
                $query->orWhere('last_name', 'like', '%' . $searchValue . '%');
                $query->orWhere('email', 'like', '%' . $searchValue . '%');
                $query->orWhere('phone_number', 'like', '%' . $searchValue . '%');
            })
            ->skip($start)
            ->take($rowPerPage)
            ->get();
        $data_arr = [];
        
        foreach ($records as $key => $record) {
            $modify = '
                <td class="text-right">
                    <div class="dropdown dropdown-action">
                        <a href="" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-ellipsis-v ellipse_color"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a class="dropdown-item" href="'.url('student/edit/'.$record->student_id).'">
                                <i class="fas fa-pencil-alt m-r-5"></i> Edit
                            </a>
                            <a class="dropdown-item" href="'.url('student/delete/'.$record->id).'">
                                <i class="fas fa-trash-alt m-r-5"></i> Delete
                            </a>
                        </div>
                    </div>
                </td>
            ';
            $upload = '
                <td>
                    <h2 class="table-avatar">
                        <a class="avatar-sm me-2">
                            <img class="upload-img rounded-circle avatar" data-avatar="'.$record->upload.'" src="storage/app/public/student-photos/'.$record->upload.'" alt="'.$record->first_name.', '.$record->last_name.'">
                        </a>
                    </h2>
                </td>
            ';
            $modify = '
                <td class="text-end">
                    <div class="actions">
                        <a href="'.url('student/profile/'.$record->student_id).'" class="btn btn-sm bg-danger-light">
                            <i class="fe fe-eye"></i>
                        </a>
                        <a href="'.url('student/edit/'.$record->student_id).'" class="btn btn-sm bg-danger-light">
                            <i class="feather-edit"></i>
                        </a>
                        <a class="btn btn-sm bg-danger-light delete student_id" data-bs-toggle="modal" data-student_id="'.$record->student_id.'" data-bs-target="#delete">
                            <i class="fe fe-trash-2"></i>
                        </a>
                    </div>
                </td>
            ';
            
            $department = $record->department ? $record->department->department_name : '';
        
            $data_arr [] = [
                "student_id"    => $record->student_id,
                "upload"        => $record->upload,
                "first_name"    => $record->first_name,
                "last_name"     => $record->last_name,
                "email"         => $record->email,
                "phone_number"  => $record->phone_number,
                "departments"   => $record->department_name,
                "modify"        => $modify,
            ];
        }

        $response = [
            "draw"                 => intval($draw),
            "iTotalRecords"        => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordsWithFilter,
            "aaData"               => $data_arr
        ];
        return response()->json($response);
    }

}
