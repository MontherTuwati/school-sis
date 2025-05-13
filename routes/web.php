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
use App\Http\Controllers\GradeController;
use App\Http\Controllers\GraduateController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\LibraryController;
use App\Http\Controllers\FinancialController;

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
    
    // Grade Routes
    Route::resource('grades', GradeController::class);
    Route::get('/grades/transcript/{student_id}', [GradeController::class, 'transcript'])->name('grades.transcript');
    
    // Graduate Routes
    Route::resource('graduates', GraduateController::class);
    Route::get('/graduates/grid', [GraduateController::class, 'grid'])->name('graduates.grid');
    Route::get('/graduates/alumni', [GraduateController::class, 'alumni'])->name('graduates.alumni');
    Route::get('/graduates/statistics', [GraduateController::class, 'statistics'])->name('graduates.statistics');
    
    // Settings Routes
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::get('/settings/general', [SettingController::class, 'general'])->name('settings.general');
    Route::post('/settings/general', [SettingController::class, 'updateGeneral'])->name('settings.general.update');
    Route::get('/settings/academic', [SettingController::class, 'academic'])->name('settings.academic');
    Route::post('/settings/academic', [SettingController::class, 'updateAcademic'])->name('settings.academic.update');
    Route::get('/settings/email', [SettingController::class, 'email'])->name('settings.email');
    Route::post('/settings/email', [SettingController::class, 'updateEmail'])->name('settings.email.update');
    Route::get('/settings/system', [SettingController::class, 'system'])->name('settings.system');
    Route::post('/settings/system', [SettingController::class, 'updateSystem'])->name('settings.system.update');
    Route::get('/settings/backup', [SettingController::class, 'backup'])->name('settings.backup');
    Route::post('/settings/restore', [SettingController::class, 'restore'])->name('settings.restore');
    
    // Report Routes
    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/students', [ReportController::class, 'students'])->name('reports.students');
    Route::get('/reports/teachers', [ReportController::class, 'teachers'])->name('reports.teachers');
    Route::get('/reports/academic', [ReportController::class, 'academic'])->name('reports.academic');
    Route::get('/reports/financial', [ReportController::class, 'financial'])->name('reports.financial');
    Route::get('/reports/attendance', [ReportController::class, 'attendance'])->name('reports.attendance');
    Route::get('/reports/graduates', [ReportController::class, 'graduates'])->name('reports.graduates');
    Route::get('/reports/export', [ReportController::class, 'export'])->name('reports.export');
    
    // Attendance Routes
    Route::get('/attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/take', [AttendanceController::class, 'take'])->name('attendance.take');
    Route::post('/attendance/get-students', [AttendanceController::class, 'getStudents'])->name('attendance.get-students');
    Route::post('/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/attendance/view', [AttendanceController::class, 'view'])->name('attendance.view');
    Route::get('/attendance/{id}/edit', [AttendanceController::class, 'edit'])->name('attendance.edit');
    Route::put('/attendance/{id}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('/attendance/{id}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::get('/attendance/report', [AttendanceController::class, 'report'])->name('attendance.report');
    Route::get('/attendance/export', [AttendanceController::class, 'export'])->name('attendance.export');
    
    // Library Routes
    Route::get('/library', [LibraryController::class, 'index'])->name('library.index');
    Route::get('/library/books', [LibraryController::class, 'books'])->name('library.books');
    Route::get('/library/books/create', [LibraryController::class, 'create'])->name('library.create');
    Route::post('/library/books', [LibraryController::class, 'store'])->name('library.store');
    Route::get('/library/books/{id}/edit', [LibraryController::class, 'edit'])->name('library.edit');
    Route::put('/library/books/{id}', [LibraryController::class, 'update'])->name('library.update');
    Route::delete('/library/books/{id}', [LibraryController::class, 'destroy'])->name('library.destroy');
    Route::get('/library/borrow', [LibraryController::class, 'borrow'])->name('library.borrow');
    Route::post('/library/borrow', [LibraryController::class, 'processBorrow'])->name('library.process-borrow');
    Route::get('/library/return/{id}', [LibraryController::class, 'return'])->name('library.return');
    Route::put('/library/return/{id}', [LibraryController::class, 'processReturn'])->name('library.process-return');
    Route::get('/library/borrowings', [LibraryController::class, 'borrowings'])->name('library.borrowings');
    Route::get('/library/categories', [LibraryController::class, 'categories'])->name('library.categories');
    Route::post('/library/categories', [LibraryController::class, 'storeCategory'])->name('library.store-category');
    Route::get('/library/reports', [LibraryController::class, 'reports'])->name('library.reports');
    Route::get('/library/export', [LibraryController::class, 'export'])->name('library.export');
    
    // Financial Routes
    Route::get('/financial', [FinancialController::class, 'index'])->name('financial.index');
    Route::get('/financial/fees', [FinancialController::class, 'fees'])->name('financial.fees');
    Route::get('/financial/fees/create', [FinancialController::class, 'createFee'])->name('financial.create-fee');
    Route::post('/financial/fees', [FinancialController::class, 'storeFee'])->name('financial.store-fee');
    Route::get('/financial/fees/{id}/edit', [FinancialController::class, 'editFee'])->name('financial.edit-fee');
    Route::put('/financial/fees/{id}', [FinancialController::class, 'updateFee'])->name('financial.update-fee');
    Route::delete('/financial/fees/{id}', [FinancialController::class, 'destroyFee'])->name('financial.destroy-fee');
    Route::get('/financial/payments', [FinancialController::class, 'payments'])->name('financial.payments');
    Route::get('/financial/payments/record', [FinancialController::class, 'recordPayment'])->name('financial.record-payment');
    Route::post('/financial/payments', [FinancialController::class, 'storePayment'])->name('financial.store-payment');
    Route::get('/financial/scholarships', [FinancialController::class, 'scholarships'])->name('financial.scholarships');
    Route::get('/financial/scholarships/create', [FinancialController::class, 'createScholarship'])->name('financial.create-scholarship');
    Route::post('/financial/scholarships', [FinancialController::class, 'storeScholarship'])->name('financial.store-scholarship');
    Route::get('/financial/categories', [FinancialController::class, 'categories'])->name('financial.categories');
    Route::post('/financial/categories', [FinancialController::class, 'storeCategory'])->name('financial.store-category');
    Route::get('/financial/reports', [FinancialController::class, 'reports'])->name('financial.reports');
    Route::get('/financial/export', [FinancialController::class, 'export'])->name('financial.export');
    
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
