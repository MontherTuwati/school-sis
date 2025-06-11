<?php

namespace App\Http\Controllers\Dashboard;
use App\Http\Controllers\Controller;

class DepartmentManagerController extends Controller
{
    public function dashboard()
    {
        $user = auth()->user(); // Assuming you have a logged-in user

        // Get the department of the logged-in user
        $department = $user->department;

        // Get students and subjects related to the department
        $students = $department->students;
        $subjects = $department->subjects;

        // Other logic...

        return view('dashboard.departmentmanager.index', compact('students', 'subjects'));
    }
}