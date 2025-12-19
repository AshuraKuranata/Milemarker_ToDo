<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TodoList;
use App\Models\Todo;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

// To-do: Set up Seeder for initial database 

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create a test user
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Create a todo list for the user
        $todoList = TodoList::create([
            'list_name' => 'My First To-Do List',
            'user_id' => $user->id,
        ]);

        // Create some todos for the list
        Todo::create([
            'task_name' => 'Complete Laravel project',
            'due_by' => '2025-12-31',
            'task_priority' => 'High',
            'task_status' => false,
            'todo_list_id' => $todoList->id,
            'user_id' => $user->id,
        ]);

        Todo::create([
            'task_name' => 'Write documentation',
            'due_by' => '2025-12-25',
            'task_priority' => 'Medium',
            'task_status' => false,
            'todo_list_id' => $todoList->id,
            'user_id' => $user->id,
        ]);
    }
}
