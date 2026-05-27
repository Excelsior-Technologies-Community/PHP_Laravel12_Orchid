<?php
// app/Exports/TasksExport.php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TasksExport implements FromQuery, WithHeadings, WithMapping
{
    public function query()
    {
        return Task::with(['project', 'assignee', 'category']);
    }
    
    public function headings(): array
    {
        return [
            'ID', 'Title', 'Status', 'Priority', 'Project', 
            'Assigned To', 'Category', 'Due Date', 'Created At'
        ];
    }
    
    public function map($task): array
    {
        return [
            $task->id,
            $task->title,
            Task::STATUSES[$task->status],
            Task::PRIORITIES[$task->priority],
            $task->project?->name,
            $task->assignee?->name,
            $task->category?->name,
            $task->due_date?->format('Y-m-d'),
            $task->created_at->format('Y-m-d H:i'),
        ];
    }
}