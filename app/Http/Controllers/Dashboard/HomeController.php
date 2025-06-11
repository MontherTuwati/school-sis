<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
    * Show the application dashboard.
    *
    * @return \Illuminate\Contracts\Support\Renderable
    */
    /** home dashboard */
    public function index()
    {
        // Redirect users based on their roles
        if (auth()->check()) {
            $role = auth()->user()->role;

            switch ($role) {
                case 'Super Admin':
                    return redirect()->route('dashboard.superadmin');
                case 'Admin':
                    return redirect()->route('dashboard.admin');
                case 'Department Manager':
                    return redirect()->route('dashboard.departmentmanager');
                // Add more cases for other roles as needed

            }
        }
        // Redirect to login if not authenticated
        return redirect('/auth/login');
    }

    /** profile user */
    public function userProfile()
    {
        return view('dashboard.profile');
    }
}
