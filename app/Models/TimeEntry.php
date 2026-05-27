<?php
// app/Models/TimeEntry.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TimeEntry extends Model
{
    protected $fillable = [
        'task_id', 'user_id', 'start_time', 'end_time', 
        'duration_minutes', 'description'
    ];
    
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'duration_minutes' => 'integer',
    ];
    
    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    protected static function booted()
    {
        static::saving(function ($timeEntry) {
            if ($timeEntry->start_time && $timeEntry->end_time) {
                $timeEntry->duration_minutes = $timeEntry->start_time->diffInMinutes($timeEntry->end_time);
            }
        });
    }
}