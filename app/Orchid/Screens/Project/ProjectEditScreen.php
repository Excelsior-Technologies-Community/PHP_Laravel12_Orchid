<?php

declare(strict_types=1);

namespace App\Orchid\Screens\Project;

use App\Models\Project;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Actions\Button;
use Illuminate\Http\Request;
use Orchid\Support\Facades\Alert;

class ProjectEditScreen extends Screen
{
    public $project;

    public function query(Project $project): iterable
    {
        return [
            'project' => $project
        ];
    }

    public function name(): ?string
    {
        return $this->project->exists ? 'Edit Project' : 'Creating a new Project';
    }

    public function commandBar(): iterable
    {
        return [
            Button::make('Save')
                ->icon('bs.check')
                ->method('createOrUpdate'),

            Button::make('Remove')
                ->icon('bs.trash')
                ->method('remove')
                ->canSee($this->project->exists),
        ];
    }

    public function layout(): iterable
    {
        return [
            Layout::rows([
                Input::make('project.name')
                    ->title('Name')
                    ->required(),

                TextArea::make('project.description')
                    ->title('Description')
                    ->rows(5),

                Select::make('project.status')
                    ->options([
                        'active' => 'Active',
                        'pending' => 'Pending',
                        'finished' => 'Finished',
                    ])
                    ->title('Status'),
            ])
        ];
    }

   public function createOrUpdate(Request $request)
    {
        
        $projectModel = $this->project->exists ? $this->project : new Project();

        
        $projectModel->fill($request->get('project'))->save();

        Alert::info('Project saved successfully.');

        return redirect()->route('platform.project.list');
    }

    public function remove(Request $request)
    {
        $project = $request->route('project');

        if ($project) {
            $project->delete();
        }

        Alert::info('Project deleted successfully.');

        return redirect()->route('platform.project.list');
    }
}