<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;
use Orchid\Filters\Filterable;

class Project extends Model
{
    use AsSource, Filterable;

    protected $fillable = ['name', 'description', 'status'];
}