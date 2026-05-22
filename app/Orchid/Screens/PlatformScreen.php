<?php

declare(strict_types=1);

namespace App\Orchid\Screens;

use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use App\Models\User;
use App\Models\Project;

class PlatformScreen extends Screen
{
    public function query(): iterable
    {
        return [
            'metrics' => [
                'Total Users'     => ['value' => number_format(User::count()), 'diff' => 12],
                'Total Projects'  => ['value' => number_format(Project::count()), 'diff' => 5],
                'Active Projects' => ['value' => number_format(Project::where('status', 'active')->count()), 'diff' => 2],
            ],
        ];
    }

    public function name(): ?string
    {
        return 'Live Dashboard';
    }

    public function description(): ?string
    {
        return 'Welcome to your admin panel.';
    }

    public function commandBar(): iterable
    {
        return [];
    }

    public function layout(): iterable
    {
        return [
            Layout::metrics([
                'Total Users'     => 'metrics.Total Users',
                'Total Projects'  => 'metrics.Total Projects',
                'Active Projects' => 'metrics.Active Projects',
            ]),
        ];
    }
}