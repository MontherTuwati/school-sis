<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'building',
        'floor',
        'room_number',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getFullLocationAttribute()
    {
        return "{$this->building} - Floor {$this->floor} - Room {$this->room_number}";
    }
}
