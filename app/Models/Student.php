<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable = [
        'student_id',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'email',
        'phone_number',
        'nation_id',
        'semester',
        'departments',
        'upload',
    ];

    public function departments()
    {
        return $this->belongsTo(Department::class);
    }

    public function sunjects()
    {
        return $this->belongsTo(Subject::class);
    }
    
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
    
    public function transcript()
    {
        return $this->hasOne(Transcript::class);
    }
    
    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $getUser = self::orderBy('student_id', 'desc')->first();

            if ($getUser) {
                $latestID = intval(substr($getUser->student_id, 8));
                $nextID = $latestID + 1;
            } else {
                $nextID = 1;
            }

            $model->student_id = 'STU' . sprintf("%08s", $nextID);
            while (self::where('student_id', $model->student_id)->exists()) {
                $nextID++;
                $model->student_id = 'STU' . sprintf("%08s", $nextID);
            }
        });
    }
}
