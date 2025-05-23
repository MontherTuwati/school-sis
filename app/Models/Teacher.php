<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;
    protected $fillable = [
        'teacher_id',
        'full_name',
        'gender',
        'date_of_birth',
        'mobile',
        'joining_date',
        'qualification',
        'experience',
        'username',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
    ];
    
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $getUser = self::orderBy('teacher_id', 'desc')->first();

            if ($getUser) {
                $latestID = intval(substr($getUser->teacher_id, 8));
                $nextID = $latestID + 1;
            } else {
                $nextID = 1;
            }
            $model->teacher_id = 'PROF' . sprintf("%08s", $nextID);
            while (self::where('teacher_id', $model->teacher_id)->exists()) {
                $nextID++;
                $model->teacher_id = 'PROF' . sprintf("%08s", $nextID);
            }
        });
    }
}
