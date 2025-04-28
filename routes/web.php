<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\UserManagementController;

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/', function () {
        return redirect()->route('login');
    });
    
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended('dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    });
    
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
    
    Route::post('/register', function (Request $request) {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        Auth::login($user);
        return redirect()->route('dashboard');
    });
});

// Protected Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Student Routes
    Route::resource('students', StudentController::class);
    Route::get('/students/count', [StudentController::class, 'count'])->name('students.count');
    Route::get('/students/grid', [StudentController::class, 'grid'])->name('students.grid');
    
    // Teacher Routes
    Route::resource('teachers', TeacherController::class);
    Route::get('/teachers/grid', [TeacherController::class, 'grid'])->name('teachers.grid');
    
    // Department Routes
    Route::resource('departments', DepartmentController::class);
    
    // Course Routes
    Route::resource('courses', CourseController::class);
    
    // Subject Routes
    Route::resource('subjects', SubjectController::class);
    
    // Event Routes
    Route::resource('events', EventController::class);
    Route::get('/events/calendar', [EventController::class, 'calendar'])->name('events.calendar');
    
    // User Management Routes
    Route::resource('usermanagement', UserManagementController::class);
    Route::post('/usermanagement/{id}/change-password', [UserManagementController::class, 'changePassword'])->name('usermanagement.change-password');
    Route::post('/usermanagement/{id}/toggle-status', [UserManagementController::class, 'toggleStatus'])->name('usermanagement.toggle-status');
    
    Route::post('/logout', function (Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    })->name('logout');
});

// Default fallback
Route::fallback(function () {
    return redirect()->route('login');
});
