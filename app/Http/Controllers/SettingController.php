<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    /** settings index */
    public function index()
    {
        $settings = $this->getAllSettings();
        return view('setting.index', compact('settings'));
    }

    /** general settings */
    public function general()
    {
        $settings = $this->getGeneralSettings();
        return view('setting.general', compact('settings'));
    }

    /** academic settings */
    public function academic()
    {
        $settings = $this->getAcademicSettings();
        return view('setting.academic', compact('settings'));
    }

    /** email settings */
    public function email()
    {
        $settings = $this->getEmailSettings();
        return view('setting.email', compact('settings'));
    }

    /** system settings */
    public function system()
    {
        $settings = $this->getSystemSettings();
        return view('setting.system', compact('settings'));
    }

    /** update general settings */
    public function updateGeneral(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'school_name' => 'required|string|max:255',
            'school_address' => 'required|string|max:500',
            'school_phone' => 'required|string|max:20',
            'school_email' => 'required|email',
            'school_website' => 'nullable|url',
            'school_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'school_motto' => 'nullable|string|max:255',
            'timezone' => 'required|string',
            'date_format' => 'required|string|in:Y-m-d,d/m/Y,m/d/Y,d-m-Y',
            'time_format' => 'required|string|in:H:i:s,H:i,12',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $settings = [
                'school_name' => $request->school_name,
                'school_address' => $request->school_address,
                'school_phone' => $request->school_phone,
                'school_email' => $request->school_email,
                'school_website' => $request->school_website,
                'school_motto' => $request->school_motto,
                'timezone' => $request->timezone,
                'date_format' => $request->date_format,
                'time_format' => $request->time_format,
            ];

            // Handle logo upload
            if ($request->hasFile('school_logo')) {
                $logo = $request->file('school_logo');
                $logoName = 'school_logo.' . $logo->getClientOriginalExtension();
                $logo->storeAs('public/settings', $logoName);
                $settings['school_logo'] = $logoName;
            }

            $this->saveSettings('general', $settings);
            
            return redirect()->route('settings.general')->with('success', 'General settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update general settings. Please try again.');
        }
    }

    /** update academic settings */
    public function updateAcademic(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'academic_year' => 'required|string|max:20',
            'semester_count' => 'required|integer|min:1|max:4',
            'semester_names' => 'required|array',
            'semester_names.*' => 'required|string|max:50',
            'grade_scale' => 'required|string|in:4.0,5.0,10.0,100',
            'passing_grade' => 'required|numeric|min:0',
            'max_credits_per_semester' => 'required|integer|min:1|max:30',
            'attendance_required' => 'required|boolean',
            'min_attendance_percentage' => 'required_if:attendance_required,1|numeric|min:0|max:100',
            'late_arrival_threshold' => 'required|integer|min:0|max:60',
            'exam_duration_default' => 'required|integer|min:30|max:300',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $settings = [
                'academic_year' => $request->academic_year,
                'semester_count' => $request->semester_count,
                'semester_names' => $request->semester_names,
                'grade_scale' => $request->grade_scale,
                'passing_grade' => $request->passing_grade,
                'max_credits_per_semester' => $request->max_credits_per_semester,
                'attendance_required' => $request->attendance_required,
                'min_attendance_percentage' => $request->min_attendance_percentage,
                'late_arrival_threshold' => $request->late_arrival_threshold,
                'exam_duration_default' => $request->exam_duration_default,
            ];

            $this->saveSettings('academic', $settings);
            
            return redirect()->route('settings.academic')->with('success', 'Academic settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update academic settings. Please try again.');
        }
    }

    /** update email settings */
    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mail_driver' => 'required|string|in:smtp,mailgun,ses,postmark',
            'mail_host' => 'required|string|max:255',
            'mail_port' => 'required|integer|min:1|max:65535',
            'mail_username' => 'required|string|max:255',
            'mail_password' => 'required|string|max:255',
            'mail_encryption' => 'required|string|in:tls,ssl',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string|max:255',
            'enable_email_notifications' => 'required|boolean',
            'notification_types' => 'required|array',
            'notification_types.*' => 'string|in:grades,attendance,events,announcements',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $settings = [
                'mail_driver' => $request->mail_driver,
                'mail_host' => $request->mail_host,
                'mail_port' => $request->mail_port,
                'mail_username' => $request->mail_username,
                'mail_password' => $request->mail_password,
                'mail_encryption' => $request->mail_encryption,
                'mail_from_address' => $request->mail_from_address,
                'mail_from_name' => $request->mail_from_name,
                'enable_email_notifications' => $request->enable_email_notifications,
                'notification_types' => $request->notification_types,
            ];

            $this->saveSettings('email', $settings);
            
            return redirect()->route('settings.email')->with('success', 'Email settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update email settings. Please try again.');
        }
    }

    /** update system settings */
    public function updateSystem(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'maintenance_mode' => 'required|boolean',
            'debug_mode' => 'required|boolean',
            'session_lifetime' => 'required|integer|min:1|max:1440',
            'max_login_attempts' => 'required|integer|min:1|max:10',
            'lockout_duration' => 'required|integer|min:1|max:60',
            'password_expiry_days' => 'required|integer|min:0|max:365',
            'require_password_change' => 'required|boolean',
            'backup_frequency' => 'required|string|in:daily,weekly,monthly',
            'auto_backup' => 'required|boolean',
            'file_upload_max_size' => 'required|integer|min:1|max:100',
            'allowed_file_types' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $settings = [
                'maintenance_mode' => $request->maintenance_mode,
                'debug_mode' => $request->debug_mode,
                'session_lifetime' => $request->session_lifetime,
                'max_login_attempts' => $request->max_login_attempts,
                'lockout_duration' => $request->lockout_duration,
                'password_expiry_days' => $request->password_expiry_days,
                'require_password_change' => $request->require_password_change,
                'backup_frequency' => $request->backup_frequency,
                'auto_backup' => $request->auto_backup,
                'file_upload_max_size' => $request->file_upload_max_size,
                'allowed_file_types' => $request->allowed_file_types,
            ];

            $this->saveSettings('system', $settings);
            
            return redirect()->route('settings.system')->with('success', 'System settings updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to update system settings. Please try again.');
        }
    }

    /** backup settings */
    public function backup()
    {
        try {
            $settings = $this->getAllSettings();
            $backupData = json_encode($settings, JSON_PRETTY_PRINT);
            $filename = 'settings_backup_' . date('Y-m-d_H-i-s') . '.json';
            
            Storage::put('backups/' . $filename, $backupData);
            
            return response()->download(storage_path('app/backups/' . $filename))->deleteFileAfterSend();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create backup. Please try again.');
        }
    }

    /** restore settings */
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:json|max:2048'
        ]);

        try {
            $backupData = json_decode($request->file('backup_file')->get(), true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid backup file format');
            }

            foreach ($backupData as $category => $settings) {
                $this->saveSettings($category, $settings);
            }
            
            return redirect()->route('settings.index')->with('success', 'Settings restored successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to restore settings. Please check your backup file.');
        }
    }

    /** get all settings */
    private function getAllSettings()
    {
        return [
            'general' => $this->getGeneralSettings(),
            'academic' => $this->getAcademicSettings(),
            'email' => $this->getEmailSettings(),
            'system' => $this->getSystemSettings(),
        ];
    }

    /** get general settings */
    private function getGeneralSettings()
    {
        return Cache::remember('settings.general', 3600, function () {
            return [
                'school_name' => config('settings.school_name', 'School SIS'),
                'school_address' => config('settings.school_address', ''),
                'school_phone' => config('settings.school_phone', ''),
                'school_email' => config('settings.school_email', ''),
                'school_website' => config('settings.school_website', ''),
                'school_logo' => config('settings.school_logo', ''),
                'school_motto' => config('settings.school_motto', ''),
                'timezone' => config('app.timezone', 'UTC'),
                'date_format' => config('settings.date_format', 'Y-m-d'),
                'time_format' => config('settings.time_format', 'H:i:s'),
            ];
        });
    }

    /** get academic settings */
    private function getAcademicSettings()
    {
        return Cache::remember('settings.academic', 3600, function () {
            return [
                'academic_year' => config('settings.academic_year', date('Y') . '-' . (date('Y') + 1)),
                'semester_count' => config('settings.semester_count', 2),
                'semester_names' => config('settings.semester_names', ['Fall', 'Spring']),
                'grade_scale' => config('settings.grade_scale', '4.0'),
                'passing_grade' => config('settings.passing_grade', 2.0),
                'max_credits_per_semester' => config('settings.max_credits_per_semester', 18),
                'attendance_required' => config('settings.attendance_required', true),
                'min_attendance_percentage' => config('settings.min_attendance_percentage', 75),
                'late_arrival_threshold' => config('settings.late_arrival_threshold', 15),
                'exam_duration_default' => config('settings.exam_duration_default', 120),
            ];
        });
    }

    /** get email settings */
    private function getEmailSettings()
    {
        return Cache::remember('settings.email', 3600, function () {
            return [
                'mail_driver' => config('mail.default', 'smtp'),
                'mail_host' => config('mail.mailers.smtp.host', ''),
                'mail_port' => config('mail.mailers.smtp.port', 587),
                'mail_username' => config('mail.mailers.smtp.username', ''),
                'mail_password' => config('mail.mailers.smtp.password', ''),
                'mail_encryption' => config('mail.mailers.smtp.encryption', 'tls'),
                'mail_from_address' => config('mail.from.address', ''),
                'mail_from_name' => config('mail.from.name', ''),
                'enable_email_notifications' => config('settings.enable_email_notifications', true),
                'notification_types' => config('settings.notification_types', ['grades', 'attendance', 'events']),
            ];
        });
    }

    /** get system settings */
    private function getSystemSettings()
    {
        return Cache::remember('settings.system', 3600, function () {
            return [
                'maintenance_mode' => config('app.debug', false),
                'debug_mode' => config('app.debug', false),
                'session_lifetime' => config('session.lifetime', 120),
                'max_login_attempts' => config('settings.max_login_attempts', 5),
                'lockout_duration' => config('settings.lockout_duration', 15),
                'password_expiry_days' => config('settings.password_expiry_days', 90),
                'require_password_change' => config('settings.require_password_change', false),
                'backup_frequency' => config('settings.backup_frequency', 'weekly'),
                'auto_backup' => config('settings.auto_backup', true),
                'file_upload_max_size' => config('settings.file_upload_max_size', 10),
                'allowed_file_types' => config('settings.allowed_file_types', 'jpg,jpeg,png,pdf,doc,docx'),
            ];
        });
    }

    /** save settings */
    private function saveSettings($category, $settings)
    {
        foreach ($settings as $key => $value) {
            config(['settings.' . $key => $value]);
        }
        
        Cache::forget('settings.' . $category);
        Cache::remember('settings.' . $category, 3600, function () use ($settings) {
            return $settings;
        });
    }
}
