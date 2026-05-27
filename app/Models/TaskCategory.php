<?php
// app/Models/TaskCategory.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskCategory extends Model
{
    protected $table = 'task_categories';
    
    protected $fillable = [
        'name', 'slug', 'color', 'description'
    ];
    
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'category_id');
    }
}