<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use DB;
use Auth;
use Session;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout',
            'locked',
            'unlock'
        ]);
    }

    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        try {
            if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
                $user = auth()->user();

                Session::put([
                    'user_id' => $user->user_id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'join_date' => $user->join_date,
                    'phone_number' => $user->phone_number,
                    'status' => $user->status,
                    'role' => $user->role,
                    'avatar' => $user->avatar,
                    'position' => $user->position,
                    'department' => $user->department,
                ]);

                Toastr::success('Login successful', 'Success');
                switch ($user->role) {
                    case 'Super Admin':
                        return redirect()->route('dashboard.superadmin');
                    case 'Admin':
                        return redirect()->route('dashboard.admin');
                    case 'Department Manager':
                        return redirect()->route('dashboard.departmentmanager');
                    // Add more cases for other roles as needed
                    default:
                        return redirect('home'); // Default redirection for unknown roles
                }
            } else {
                Toastr::error('Fail, WRONG USERNAME OR PASSWORD :)', 'Error');
                return redirect('login');
            }
        } catch (\Exception $e) {
            Toastr::error('Fail, LOGIN :)', 'Error');
            return redirect()->back();
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->flush();

        Toastr::success('Logout successful', 'Success');
        return redirect('login');
    }
}
