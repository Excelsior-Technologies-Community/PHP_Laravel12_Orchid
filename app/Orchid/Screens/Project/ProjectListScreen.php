<?php

namespace App\Orchid\Screens\Project;

use App\Models\Project;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Actions\Link;

class ProjectListScreen extends Screen
{
    public $name = 'Projects';
    public $description = 'Manage all your projects';

    public function query(): iterable
    {
        return ['projects' => Project::paginate()];
    }

    public function commandBar(): iterable
    {
        return [
            Link::make('Create new Project')
                ->icon('bs.pencil')
                ->route('platform.project.edit')
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::table('projects', [
                TD::make('name', 'Project Name')
                    ->render(function (Project $project) {
                        return Link::make($project->name)->route('platform.project.edit', $project);
                    }),
                
                TD::make('status', 'Status'),
                
                TD::make('created_at', 'Created')
                    ->render(function (Project $project) {
                        return $project->created_at->toDateTimeString();
                    }),
            ])
        ];
    }
}