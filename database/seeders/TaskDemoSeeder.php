<?php
// database/seeders/TaskDemoSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\Project;
use App\Models\User;
use App\Models\Tag;

class TaskDemoSeeder extends Seeder
{
    public function run()
    {
        // Create categories
        $categories = [
            ['name' => 'Development', 'slug' => 'development', 'color' => '#6366f1'],
            ['name' => 'Design', 'slug' => 'design', 'color' => '#ec4899'],
            ['name' => 'Testing', 'slug' => 'testing', 'color' => '#10b981'],
            ['name' => 'Documentation', 'slug' => 'documentation', 'color' => '#f59e0b'],
            ['name' => 'Meeting', 'slug' => 'meeting', 'color' => '#8b5cf6'],
        ];
        
        foreach ($categories as $cat) {
            TaskCategory::create($cat);
        }
        
        // Create tags
        $tags = ['Bug', 'Feature', 'Enhancement', 'Urgent', 'Backend', 'Frontend', 'API'];
        foreach ($tags as $tag) {
            Tag::create(['name' => $tag, 'slug' => \Illuminate\Support\Str::slug($tag)]);
        }
        
        // Create tasks
        $users = User::all();
        $projects = Project::all();
        $categories = TaskCategory::all();
        
        for ($i = 1; $i <= 50; $i++) {
            $status = array_rand(Task::STATUSES);
            $priority = array_rand(Task::PRIORITIES);
            
            $task = Task::create([
                'title' => "Task $i: " . fake()->sentence(4),
                'description' => fake()->paragraphs(3, true),
                'status' => $status,
                'priority' => $priority,
                'due_date' => fake()->dateTimeBetween('-1 month', '+2 months'),
                'estimated_hours' => rand(1, 40),
                'project_id' => $projects->random()->id ?? null,
                'category_id' => $categories->random()->id ?? null,
                'assigned_to' => $users->random()->id ?? null,
                'created_by' => $users->first()->id,
                'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
            ]);
            
            // Add random tags
            $task->tags()->attach(Tag::all()->random(rand(1, 3))->pluck('id'));
        }
    }
}