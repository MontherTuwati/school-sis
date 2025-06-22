<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/** for side bar menu active */
function set_active( $route ) {
    if( is_array( $route ) ){
        return in_array(Request::path(), $route) ? 'active' : '';
    }
    return Request::path() == $route ? 'active' : '';
}

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['middleware'=>'auth'], function () {
    Route::get('home', function () {
        return view('home');
    });
});

Auth::routes();
Route::group(['namespace' => 'App\Http\Controllers\Auth'],function()
{
    // ----------------------------login ------------------------------//
    Route::controller(LoginController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login', 'authenticate');
        Route::get('/logout', 'logout')->name('logout');
        Route::post('change/password', 'changePassword')->name('change/password');
    });

    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('password/reset', 'showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'sendResetLinkEmail')->name('password.email');
    });

    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('password/reset/{token}', 'showResetForm')->name('password.reset');
        Route::post('password/reset', 'reset')->name('password.update');
    });

    // ----------------------------- register -------------------------//
    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'register')->name('register');
        Route::post('/register','storeUser')->name('register');
    });

});

Route::group(['namespace' => 'App\Http\Controllers\Dashboard'],function()
{
    Route::controller(HomeController::class)->group(function () {
        // Main dashboard routes
        Route::get('/home', 'index')->middleware('auth')->name('home');
        Route::get('user/profile/page', 'userProfile')->middleware('auth')->name('user/profile/page');
        Route::get('/dashboard/superadmin/index', 'SuperAdminController@dashboard')->name('dashboard.superadmin');
        Route::get('/dashboard/admin/index', 'AdminController@dashboard')->name('dashboard.admin');
        // Add more routes for other roles as needed
        });
});

// Department Manager routes with middleware
Route::group(['middleware' => ['auth', 'department.manager']], function () {
    Route::get('/dashboard/departmentmanager/index', 'App\Http\Controllers\Dashboard\DepartmentManagerController@dashboard')->name('dashboard.departmentmanager');
    // Add more routes for the department manager as needed
});

Route::group(['namespace' => 'App\Http\Controllers'],function()
{
    // ----------------------------- user controller ---------------------//
    Route::controller(UserManagementController::class)->group(function () {
        Route::get('list/users', 'index')->middleware('auth')->name('list/users');
        Route::post('change/password', 'changePassword')->name('change/password');
        Route::get('view/user/edit/{id}', 'userView')->middleware('auth');
        Route::post('user/update', 'userUpdate')->name('user/update');
        Route::post('user/delete', 'userDelete')->name('user/delete');
        Route::get('user/add', 'registerUser')->name('add/users');
        Route::post('user/add','storeNewUser')->name('add/users');
        Route::get('get-users-data', 'getUsersData')->name('get-users-data'); /** get all data users */
        Route::get('notifications/view', 'UserManagementController@viewNotifications')->name('notifications.view');
    });

    // ------------------------ setting -------------------------------//
    Route::controller(Setting::class)->group(function () {
        Route::get('setting/page', 'index')->middleware('auth')->name('setting/page');
    });

    // student routes
    Route::middleware(['auth'])->group(function () {
        Route::get('student/list', 'StudentController@student')->name('student/list');
        Route::get('student/grid', 'StudentController@studentGrid')->name('student/grid');
        Route::get('student/graduates', 'StudentController@graduatedStudent')->name('student/graduates');
        Route::get('student/add/page', 'StudentController@studentAdd')->name('student/add/page');
        Route::post('student/add/save', 'StudentController@studentSave')->name('student/add/save');
        Route::get('student/edit/{id}', 'StudentController@studentEdit')->name('student/edit');
        Route::post('student/update', 'StudentController@studentUpdate')->name('student/update');
        Route::post('student/delete', 'StudentController@studentDelete')->name('student/delete');
        Route::get('student/profile/{id}', 'StudentController@studentProfile')->name('student/profile');
        Route::get('student/grades/{id}', 'StudentController@studentGrades')->name('student/grades');
        Route::get('get-students-data', 'StudentController@getStudentsData')->name('get-students-data');
    });

    // ------------------------ teacher -------------------------------//
    Route::controller(TeacherController::class)->group(function () {
        Route::get('teacher/add/page', 'teacherAdd')->middleware('auth')->name('teacher/add/page'); // page teacher
        Route::get('teacher/list/page', 'teacherList')->middleware('auth')->name('teacher/list/page'); // page teacher
        Route::get('teacher/grid/page', 'teacherGrid')->middleware('auth')->name('teacher/grid/page'); // page grid teacher
        Route::post('teacher/save', 'saveRecord')->middleware('auth')->name('teacher/save'); // save record
        Route::get('teacher/edit/{user_id}', 'editRecord'); // view teacher record
        Route::post('teacher/update', 'updateRecordTeacher')->middleware('auth')->name('teacher/update'); // update record
        Route::post('teacher/delete', 'teacherDelete')->name('teacher/delete'); // delete record teacher
    });

    // ----------------------- department -----------------------------//
    Route::controller(DepartmentController::class)->group(function () {
        Route::get('department/list/page', 'departmentList')->middleware('auth')->name('department/list/page'); // department/list/page
        Route::get('department/add/page', 'indexDepartment')->middleware('auth')->name('department/add/page'); // page add department
        Route::get('department/edit/{department_id}', 'editDepartment'); // page add department
        Route::post('department/save', 'saveRecord')->middleware('auth')->name('department/save'); // department/save
        Route::post('department/update', 'updateRecord')->middleware('auth')->name('department/update'); // department/update
        Route::post('department/delete', 'deleteRecord')->middleware('auth')->name('department/delete'); // department/delete
        Route::get('department/profile/{id}', 'departmentProfile')->name('deparmtent/profile');
        Route::get('get-data-list', 'getDataList')->name('get-data-list'); // get data list
        Route::get('departments/download', 'DepartmentController@exportToExcel')->name('departments.download');


    });

    // ----------------------- subject -----------------------------//
    Route::controller(SubjectController::class)->group(function () {
        Route::get('subject/list/page', 'subjectList')->middleware('auth')->name('subject/list/page'); // subject/list/page
        Route::get('subject/add/page', 'subjectAdd')->middleware('auth')->name('subject/add/page'); // subject/add/page
        Route::post('subject/save', 'saveRecord')->name('subject/save'); // subject/save
        Route::post('subject/update', 'updateRecord')->name('subject/update'); // subject/update
        Route::post('subject/delete', 'deleteRecord')->name('subject/delete'); // subject/delete
        Route::get('subject/edit/{subject_id}', 'subjectEdit'); // subject/edit/page
    });

    // ----------------------- subject -----------------------------//
    Route::controller(CourseController::class)->group(function () {
        Route::get('course/list/page', 'courseList')->middleware('auth')->name('course/list/page'); // subject/list/page
        Route::get('course/add/page', 'courseAdd')->middleware('auth')->name('course/add/page'); // subject/add/page
        Route::post('course/save', 'saveRecord')->name('course/save'); // subject/save
        Route::post('course/update', 'updateRecord')->name('course/update'); // subject/update
        Route::post('course/delete', 'deleteRecord')->name('course/delete'); // subject/delete
        Route::get('course/edit/{course_code}', 'courseEdit'); // subject/edit/page
    });

        // ------------------------ graduated students -------------------------------//
        Route::controller(StudentController::class)->group(function () {
            Route::get('graduated-student/list', 'graduatedStudent')->middleware('auth')->name('graduated-student/list'); // list student
            Route::get('graduated-student/grid', 'graduatedStudentGrid')->middleware('auth')->name('graduated-student/grid'); // grid student
            Route::get('graduated-student/add/page', 'graduatedStudentAdd')->middleware('auth')->name('graduated-student/add/page'); // page student
            Route::post('graduated-student/add/save', 'graduatedStudentSave')->name('graduated-student/add/save'); // save record student
            Route::get('graduated-student/edit/{id}', 'graduatedStudentEdit'); // view for edit
            Route::post('graduated-student/update', 'graduatedStudentUpdate')->name('graduated-student/update'); // update record student
            Route::post('graduated-student/delete', 'graduatedStudentDelete')->name('graduated-student/delete'); // delete record student
            Route::get('graduated-student/profile/{id}', 'graduatedStudentProfile')->middleware('auth'); // profile student
        });
});