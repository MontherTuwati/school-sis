<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'course_code',
        'title',
        'description',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /** auto genarate id */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $getUser = self::orderBy('course_code', 'desc')->first();

            if ($getUser) {
                $latestID = intval(substr($getUser->course_code, 5));
                $nextID = $latestID + 1;
            } else {
                $nextID = 1;
            }
            $model->course_code = 'C-' . sprintf("%03s", $nextID);
            while (self::where('course_code', $model->course_code)->exists()) {
                $nextID++;
                $model->course_code = 'SUB-' . sprintf("%03s", $nextID);
            }
        });
    }
}
