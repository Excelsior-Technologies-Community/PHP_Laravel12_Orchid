<?php
// app/Orchid/Screens/Task/TaskListScreen.php

namespace App\Orchid\Screens\Task;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\DateRange;
use Orchid\Screen\Repository;
use Orchid\Support\Facades\Alert;

class TaskListScreen extends Screen
{
    public $name = 'Tasks';
    public $description = 'Manage all tasks in the system';
    public $permission = 'platform.tasks';
    
    public function query(): array
    {
        $tasks = Task::with(['project', 'assignee', 'category'])
            ->filters()
            ->filtersApply([
                'status' => fn($query, $value) => $query->where('status', $value),
                'priority' => fn($query, $value) => $query->where('priority', $value),
                'project_id' => fn($query, $value) => $query->where('project_id', $value),
                'assigned_to' => fn($query, $value) => $query->where('assigned_to', $value),
                'date_range' => function($query, $value) {
                    if (isset($value['start'])) {
                        $query->whereDate('due_date', '>=', $value['start']);
                    }
                    if (isset($value['end'])) {
                        $query->whereDate('due_date', '<=', $value['end']);
                    }
                }
            ])
            ->latest()
            ->paginate(15);
            
        return [
            'tasks' => $tasks,
            'statusOptions' => Task::STATUSES,
            'priorityOptions' => Task::PRIORITIES,
            'projects' => Project::pluck('name', 'id'),
            'users' => User::pluck('name', 'id'),
        ];
    }
    
    public function name(): ?string
    {
        return 'Tasks Management';
    }
    
    public function permission(): ?iterable
    {
        return ['platform.tasks'];
    }
    
    public function commandBar(): array
    {
        return [
            Link::make('Create New Task')
                ->icon('plus')
                ->route('platform.task.create'),
                
            Link::make('Export')
                ->icon('cloud-download')
                ->route('platform.task.export'),
        ];
    }
    
    public function layout(): array
    {
        return [
            Layout::wrapper('orchid.layouts.wrapper', [
                'col_left' => [
                    Layout::view('orchid.filters.task-filters'),
                ],
                'col_right' => [
                    Layout::table('tasks', [
                        TD::make('id', 'ID')
                            ->sort()
                            ->width('50px'),
                            
                        TD::make('title', 'Title')
                            ->sort()
                            ->filter(Select::make())
                            ->render(function (Task $task) {
                                return Link::make($task->title)
                                    ->route('platform.task.edit', $task);
                            }),
                            
                        TD::make('status', 'Status')
                            ->sort()
                            ->filter(Select::make()->options(Task::STATUSES))
                            ->render(function (Task $task) {
                                return view('orchid.components.status-badge', [
                                    'status' => $task->status,
                                    'label' => Task::STATUSES[$task->status],
                                    'type' => $task->status_badge
                                ]);
                            }),
                            
                        TD::make('priority', 'Priority')
                            ->sort()
                            ->filter(Select::make()->options(Task::PRIORITIES))
                            ->render(function (Task $task) {
                                return view('orchid.components.priority-badge', [
                                    'priority' => $task->priority,
                                    'label' => Task::PRIORITIES[$task->priority],
                                    'type' => $task->priority_badge
                                ]);
                            }),
                            
                        TD::make('project.name', 'Project')
                            ->sort()
                            ->filter(Select::make()->fromModel(Project::class, 'name', 'id')),
                            
                        TD::make('assignee.name', 'Assigned To')
                            ->sort()
                            ->filter(Select::make()->fromModel(User::class, 'name', 'id')),
                            
                        TD::make('due_date', 'Due Date')
                            ->sort()
                            ->render(function (Task $task) {
                                if (!$task->due_date) return '—';
                                $class = $task->is_overdue ? 'text-danger' : '';
                                return "<span class='{$class}'>" . $task->due_date->format('M d, Y') . "</span>";
                            }),
                            
                        TD::make('actions', 'Actions')
                            ->alignRight()
                            ->render(function (Task $task) {
                                return view('orchid.components.task-actions', ['task' => $task]);
                            }),
                    ]),
                    
                    Layout::view('orchid.components.task-stats'),
                ]
            ])
        ];
    }
}