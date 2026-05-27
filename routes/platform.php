<?php
// Add to existing routes/platform.php

use App\Orchid\Screens\Task\TaskListScreen;
use App\Orchid\Screens\Task\TaskEditScreen;
use App\Orchid\Screens\Dashboard\DashboardScreen;

// Task Management Routes
Route::screen('tasks', TaskListScreen::class)
    ->name('platform.task.list')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Tasks', route('platform.task.list')));

Route::screen('task/{task?}', TaskEditScreen::class)
    ->name('platform.task.edit')
    ->breadcrumbs(fn (Trail $trail, $task = null) => $trail
        ->parent('platform.task.list')
        ->push($task?->title ?? 'Create Task', route('platform.task.edit', $task)));

Route::screen('task/create', TaskEditScreen::class)
    ->name('platform.task.create')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.task.list')
        ->push('Create Task', route('platform.task.create')));

// Dashboard
Route::screen('dashboard', DashboardScreen::class)
    ->name('platform.dashboard')
    ->breadcrumbs(fn (Trail $trail) => $trail
        ->parent('platform.index')
        ->push('Dashboard', route('platform.dashboard')));

// Task Export Route
Route::get('tasks/export', function() {
    return \App\Exports\TasksExport::download();
})->name('platform.task.export');