<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'course_id',
        'subject_id',
        'exam_type',
        'exam_date',
        'start_time',
        'end_time',
        'duration',
        'total_marks',
        'passing_marks',
        'classroom_id',
        'semester',
        'academic_year',
        'instructions',
        'is_active',
        'created_by'
    ];

    protected $casts = [
        'exam_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function results()
    {
        return $this->hasMany(ExamResult::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('exam_date', '>=', Carbon::today());
    }

    public function scopePast($query)
    {
        return $query->where('exam_date', '<', Carbon::today());
    }

    // Accessors
    public function getStatusAttribute()
    {
        if ($this->exam_date->isPast()) {
            return 'completed';
        } elseif ($this->exam_date->isToday()) {
            return 'today';
        } else {
            return 'upcoming';
        }
    }

    public function getFormattedDateAttribute()
    {
        return $this->exam_date->format('d M Y');
    }

    public function getFormattedStartTimeAttribute()
    {
        return $this->start_time ? $this->start_time->format('h:i A') : 'N/A';
    }

    public function getFormattedEndTimeAttribute()
    {
        return $this->end_time ? $this->end_time->format('h:i A') : 'N/A';
    }

    public function getDurationFormattedAttribute()
    {
        if (!$this->duration) return 'N/A';
        
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        } else {
            return "{$minutes}m";
        }
    }

    // Methods
    public function isUpcoming()
    {
        return $this->exam_date->isFuture();
    }

    public function isToday()
    {
        return $this->exam_date->isToday();
    }

    public function isCompleted()
    {
        return $this->exam_date->isPast();
    }

    public function getExamTypeLabel()
    {
        $types = [
            'midterm' => 'Midterm',
            'final' => 'Final',
            'quiz' => 'Quiz',
            'assignment' => 'Assignment',
            'practical' => 'Practical'
        ];

        return $types[$this->exam_type] ?? ucfirst($this->exam_type);
    }
}
