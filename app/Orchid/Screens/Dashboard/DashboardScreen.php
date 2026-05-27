<?php
// app/Orchid/Screens/Dashboard/DashboardScreen.php

namespace App\Orchid\Screens\Dashboard;

use App\Models\Task;
use App\Models\Project;
use App\Models\User;
use Orchid\Screen\Screen;
use Orchid\Screen\Layouts\Chart;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Repository;

class DashboardScreen extends Screen
{
    public $name = 'Dashboard';
    public $description = 'Task Management Overview';
    
    public function query(): array
    {
        $taskStats = [
            'total' => Task::count(),
            'pending' => Task::pending()->count(),
            'in_progress' => Task::inProgress()->count(),
            'completed' => Task::completed()->count(),
            'overdue' => Task::overdue()->count(),
            'high_priority' => Task::highPriority()->count(),
        ];
        
        // Task status distribution for chart
        $statusData = [];
        foreach (Task::STATUSES as $status => $label) {
            $statusData[] = [
                'status' => $label,
                'count' => Task::where('status', $status)->count(),
            ];
        }
        
        // Tasks by priority
        $priorityData = [];
        foreach (Task::PRIORITIES as $priority => $label) {
            $priorityData[] = [
                'priority' => $label,
                'count' => Task::where('priority', $priority)->count(),
            ];
        }
        
        // Recent tasks
        $recentTasks = Task::with(['assignee', 'project'])
            ->latest()
            ->limit(10)
            ->get();
            
        // Tasks by project
        $projectStats = Project::withCount('tasks')->get();
        
        // User task summary
        $userStats = User::withCount(['tasks as assigned_tasks_count' => function($query) {
            $query->whereNotIn('status', [Task::STATUS_COMPLETED, Task::STATUS_CANCELLED]);
        }])->get();
        
        // Monthly task completion trend
        $monthlyData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $completed = Task::where('status', Task::STATUS_COMPLETED)
                ->whereYear('completed_at', $month->year)
                ->whereMonth('completed_at', $month->month)
                ->count();
            $created = Task::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
                
            $monthlyData[] = [
                'month' => $month->format('M Y'),
                'created' => $created,
                'completed' => $completed,
            ];
        }
        
        return [
            'taskStats' => $taskStats,
            'statusChart' => $statusData,
            'priorityChart' => $priorityData,
            'recentTasks' => $recentTasks,
            'projectStats' => $projectStats,
            'userStats' => $userStats,
            'monthlyTrend' => $monthlyData,
        ];
    }
    
    public function commandBar(): array
    {
        return [];
    }
    
    public function layout(): array
    {
        return [
            Layout::wrapper('orchid.layouts.dashboard-wrapper', [
                'stats' => Layout::view('orchid.components.dashboard-stats', [
                    'stats' => $this->query()['taskStats']
                ]),
                
                'charts' => Layout::split([
                    Layout::columns([
                        Layout::chart([
                            Chart::make('statusChart')
                                ->type('doughnut')
                                ->title('Tasks by Status'),
                        ]),
                        Layout::chart([
                            Chart::make('priorityChart')
                                ->type('bar')
                                ->title('Tasks by Priority'),
                        ]),
                    ]),
                    Layout::chart([
                        Chart::make('monthlyTrend')
                            ->type('line')
                            ->title('Monthly Task Trend')
                            ->line(['created', 'completed']),
                    ]),
                ])->ratio('50/50'),
                
                'tables' => Layout::split([
                    Layout::table('recentTasks', [
                        TD::make('title', 'Recent Tasks')
                            ->render(function ($task) {
                                return view('orchid.components.dashboard-task-row', ['task' => $task]);
                            }),
                    ])->title('Recent Tasks'),
                    
                    Layout::table('projectStats', [
                        TD::make('name', 'Project'),
                        TD::make('tasks_count', 'Tasks'),
                    ])->title('Project Summary'),
                ])->ratio('60/40'),
            ])
        ];
    }
}