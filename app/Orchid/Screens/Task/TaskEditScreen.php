<?php
// app/Orchid/Screens/Task/TaskEditScreen.php

namespace App\Orchid\Screens\Task;

use App\Models\Task;
use App\Models\Project;
use App\Models\TaskCategory;
use App\Models\User;
use App\Models\Tag;
use Illuminate\Http\Request;
use Orchid\Screen\Screen;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Fields\TextArea;
use Orchid\Screen\Fields\Select;
use Orchid\Screen\Fields\DateTimer;
use Orchid\Screen\Fields\Relation;
use Orchid\Screen\Fields\Quill;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Tag as TagField;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Alert;
use Illuminate\Support\Str;

class TaskEditScreen extends Screen
{
    public $task;
    public $name = 'Edit Task';
    public $description = 'Edit task details';
    
    public function query(Task $task): array
    {
        $this->name = $task->exists ? 'Edit Task: ' . $task->title : 'Create New Task';
        $this->description = $task->exists ? 'Update task information' : 'Add a new task to the system';
        
        $task->load(['tags', 'subtasks', 'comments.user']);
        
        return [
            'task' => $task,
            'projects' => Project::all(),
            'categories' => TaskCategory::all(),
            'users' => User::all(),
            'statuses' => Task::STATUSES,
            'priorities' => Task::PRIORITIES,
            'availableTags' => Tag::all()->pluck('name', 'id'),
        ];
    }
    
    public function permission(): ?iterable
    {
        return ['platform.tasks'];
    }
    
    public function commandBar(): array
    {
        return [
            Button::make('Save')
                ->icon('check')
                ->method('save')
                ->class('btn btn-primary'),
                
            Button::make('Save and Continue')
                ->icon('refresh')
                ->method('saveAndContinue')
                ->class('btn btn-secondary'),
                
            Button::make('Delete')
                ->icon('trash')
                ->method('delete')
                ->canSee($this->task->exists)
                ->class('btn btn-danger'),
        ];
    }
    
    public function layout(): array
    {
        return [
            Layout::split([
                Layout::rows([
                    Input::make('task.title')
                        ->title('Title')
                        ->placeholder('Enter task title')
                        ->required(),
                        
                    Quill::make('task.description')
                        ->title('Description')
                        ->toolbar(['text', 'color', 'header', 'list', 'format']),
                        
                    Layout::columns([
                        Layout::rows([
                            Select::make('task.project_id')
                                ->title('Project')
                                ->fromModel(Project::class, 'name', 'id')
                                ->empty('No Project'),
                                
                            Select::make('task.category_id')
                                ->title('Category')
                                ->fromModel(TaskCategory::class, 'name', 'id')
                                ->empty('No Category'),
                                
                            Select::make('task.assigned_to')
                                ->title('Assign To')
                                ->fromModel(User::class, 'name', 'id')
                                ->empty('Unassigned'),
                        ]),
                        
                        Layout::rows([
                            Select::make('task.status')
                                ->title('Status')
                                ->options(Task::STATUSES)
                                ->required(),
                                
                            Select::make('task.priority')
                                ->title('Priority')
                                ->options(Task::PRIORITIES)
                                ->required(),
                                
                            DateTimer::make('task.due_date')
                                ->title('Due Date')
                                ->format('Y-m-d'),
                        ]),
                    ]),
                    
                    Layout::columns([
                        Layout::rows([
                            Input::make('task.estimated_hours')
                                ->title('Estimated Hours')
                                ->type('number')
                                ->step(0.5)
                                ->placeholder('e.g., 4.5'),
                                
                            Input::make('task.actual_hours')
                                ->title('Actual Hours')
                                ->type('number')
                                ->step(0.5)
                                ->readonly(!$this->task->exists),
                        ]),
                        
                        Layout::rows([
                            TagField::make('task.tags')
                                ->title('Tags')
                                ->placeholder('Add tags...')
                                ->multiple(),
                                
                            Select::make('task.parent_task_id')
                                ->title('Parent Task (Subtask of)')
                                ->fromModel(Task::class, 'title', 'id')
                                ->empty('No Parent Task'),
                        ]),
                    ]),
                    
                    CheckBox::make('task.completed')
                        ->title('Mark as Completed')
                        ->value($this->task->status === Task::STATUS_COMPLETED)
                        ->sendTrueOrFalse()
                        ->canSee($this->task->exists && $this->task->status !== Task::STATUS_COMPLETED),
                ])->title('Basic Information'),
            ], [
                Layout::rows([
                    Layout::view('orchid.components.task-subtasks', ['task' => $this->task]),
                    Layout::view('orchid.components.task-comments', ['task' => $this->task]),
                    Layout::view('orchid.components.task-time-tracking', ['task' => $this->task]),
                ])->title('Additional Information')->canSee($this->task->exists),
            ])->ratio('70/30'),
        ];
    }
    
    public function save(Task $task, Request $request)
    {
        $request->validate([
            'task.title' => 'required|string|max:255',
            'task.status' => 'required|in:' . implode(',', array_keys(Task::STATUSES)),
            'task.priority' => 'required|in:' . implode(',', array_keys(Task::PRIORITIES)),
        ]);
        
        $data = $request->get('task');
        
        // Handle completed status
        if (isset($data['completed']) && $data['completed']) {
            $data['status'] = Task::STATUS_COMPLETED;
            $data['completed_at'] = now();
        } elseif ($task->exists && $task->status === Task::STATUS_COMPLETED && !isset($data['completed'])) {
            $data['status'] = Task::STATUS_PENDING;
            $data['completed_at'] = null;
        }
        
        unset($data['completed']);
        
        // Set created_by for new tasks
        if (!$task->exists) {
            $data['created_by'] = auth()->id();
        }
        
        $task->fill($data)->save();
        
        // Handle tags
        if (isset($data['tags'])) {
            $tags = collect($data['tags'])->map(function($tagName) {
                return Tag::firstOrCreate([
                    'slug' => Str::slug($tagName)
                ], [
                    'name' => $tagName
                ])->id;
            });
            $task->tags()->sync($tags);
        }
        
        Alert::info('Task saved successfully!');
        
        return redirect()->route('platform.task.list');
    }
    
    public function saveAndContinue(Task $task, Request $request)
    {
        $this->save($task, $request);
        return redirect()->route('platform.task.edit', $task);
    }
    
    public function delete(Task $task)
    {
        $task->delete();
        Alert::info('Task deleted successfully!');
        return redirect()->route('platform.task.list');
    }
}