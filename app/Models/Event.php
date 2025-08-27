<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'event_date',
        'start_time',
        'end_time',
        'location',
        'event_type',
        'priority',
        'is_all_day',
        'is_active',
        'color',
        'created_by'
    ];

    protected $casts = [
        'event_date' => 'date',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_all_day' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', Carbon::today());
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', Carbon::today());
    }

    public function scopeToday($query)
    {
        return $query->whereDate('event_date', Carbon::today());
    }

    public function scopeByType($query, $type)
    {
        return $query->where('event_type', $type);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    // Accessors
    public function getStatusAttribute()
    {
        if ($this->event_date->isPast()) {
            return 'past';
        } elseif ($this->event_date->isToday()) {
            return 'today';
        } else {
            return 'upcoming';
        }
    }

    public function getFormattedDateAttribute()
    {
        return $this->event_date->format('d M Y');
    }

    public function getFormattedStartTimeAttribute()
    {
        if ($this->is_all_day) {
            return 'All Day';
        }
        return $this->start_time ? $this->start_time->format('h:i A') : 'N/A';
    }

    public function getFormattedEndTimeAttribute()
    {
        if ($this->is_all_day) {
            return 'All Day';
        }
        return $this->end_time ? $this->end_time->format('h:i A') : 'N/A';
    }

    public function getDurationAttribute()
    {
        if ($this->is_all_day) {
            return 'All Day';
        }
        
        if (!$this->start_time || !$this->end_time) {
            return 'N/A';
        }

        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);
        $duration = $start->diffInMinutes($end);
        
        if ($duration < 60) {
            return $duration . ' min';
        } elseif ($duration < 1440) {
            return round($duration / 60, 1) . ' hours';
        } else {
            return round($duration / 1440, 1) . ' days';
        }
    }

    public function getEventTypeLabelAttribute()
    {
        return ucfirst($this->event_type);
    }

    public function getPriorityLabelAttribute()
    {
        return ucfirst($this->priority);
    }

    public function getPriorityColorAttribute()
    {
        switch ($this->priority) {
            case 'high':
                return 'danger';
            case 'medium':
                return 'warning';
            case 'low':
                return 'success';
            default:
                return 'secondary';
        }
    }

    public function getEventTypeColorAttribute()
    {
        switch ($this->event_type) {
            case 'academic':
                return 'primary';
            case 'social':
                return 'success';
            case 'sports':
                return 'warning';
            case 'cultural':
                return 'info';
            case 'meeting':
                return 'secondary';
            case 'other':
                return 'dark';
            default:
                return 'secondary';
        }
    }

    // Methods
    public function isUpcoming()
    {
        return $this->event_date->isFuture();
    }

    public function isToday()
    {
        return $this->event_date->isToday();
    }

    public function isPast()
    {
        return $this->event_date->isPast();
    }

    public function isAllDay()
    {
        return $this->is_all_day;
    }
}
