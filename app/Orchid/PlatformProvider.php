<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);
    }

   public function menu(): array
{
    return [
        // Dashboard
        Menu::make('Dashboard')
            ->icon('home')
            ->route('platform.dashboard')
            ->title('Overview'),
            
        // Tasks Section
        Menu::make('Tasks')
            ->icon('list')
            ->title('Task Management'),
            
        Menu::make('All Tasks')
            ->icon('list')
            ->route('platform.task.list')
            ->parent('Tasks')
            ->badge(fn () => \App\Models\Task::pending()->count()),
            
        Menu::make('Create Task')
            ->icon('plus')
            ->route('platform.task.create')
            ->parent('Tasks'),
            
        Menu::make('Task Categories')
            ->icon('folder')
            ->route('platform.task.categories')
            ->parent('Tasks')
            ->canSee(false), // To be implemented
            
        // Projects (existing)
        Menu::make('Projects')
            ->icon('briefcase')
            ->route('platform.project.list'),
            
        // Users & Roles (existing)
        Menu::make('Users')
            ->icon('user')
            ->route('platform.systems.users')
            ->permission('platform.systems.users'),
            
        Menu::make('Roles')
            ->icon('lock')
            ->route('platform.systems.roles')
            ->permission('platform.systems.roles'),
            
        // Activity Logs (existing)
        Menu::make('Activity Logs')
            ->icon('clock')
            ->route('platform.activity.logs')
            ->permission('platform.systems.users'),
    ];
}
    public function permissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),
        ];
    }
}