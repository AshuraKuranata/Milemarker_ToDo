<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller
{
    /**
     * UPDATED: Display all todos for a specific todo list
     */
    public function index(TodoList $todolist)
    {
        $todos = $todolist->tasks()->get();

        return Inertia::render('Todos/Index', [
            'todolist' => $todolist,
            'todos' => $todos,
        ]);
    }

    /**
     * UPDATED: Show form to create a new todo for a todo list
     */
    public function create(TodoList $todolist)
    {
        return Inertia::render('Todos/Create', [
            'todolist' => $todolist,
        ]);
    }

    /**
     * UPDATED: Store a newly created todo for a todo list (with authorization)
     */
    public function store(Request $request, TodoList $todolist)
    {
        // UPDATED: Ensure user owns this todo list
        if ($todolist->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'due_by' => 'required|date',
            'task_priority' => 'required|in:Low,Medium,High',
            'task_status' => 'boolean',
        ]);

        // Create the todo for the todo list
        $todo = $todolist->tasks()->create([
            'task_name' => $validated['task_name'],
            'due_by' => $validated['due_by'],
            'task_priority' => $validated['task_priority'],
            'task_status' => $validated['task_status'] ?? false,
            'user_id' => $todolist->user_id,
        ]);

        // Redirect back to home with success message
        return redirect()->route('home')->with('success', 'Todo created successfully!');
    }

    /**
     * UPDATED: Display a specific todo
     */
    public function show(Todo $todo)
    {
        $todo->load('todolist');

        return Inertia::render('Todos/Show', [
            'todo' => $todo,
        ]);
    }

    /**
     * UPDATED: Show form to edit a todo
     */
    public function edit(Todo $todo)
    {
        return Inertia::render('Todos/Edit', [
            'todo' => $todo,
        ]);
    }

    /**
     * UPDATED: Update a todo (with authorization)
     */
    public function update(Request $request, Todo $todo)
    {
        // UPDATED: Ensure user owns this todo
        if ($todo->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'due_by' => 'required|date',
            'task_priority' => 'required|in:Low,Medium,High',
            'task_status' => 'boolean',
        ]);

        // Update the todo
        $todo->update($validated);

        // Redirect back with success message
        return redirect()->route('home')->with('success', 'Todo updated successfully!');
    }

    /**
     * UPDATED: Delete a todo (with authorization)
     */
    public function destroy(Request $request, Todo $todo)
    {
        // UPDATED: Ensure user owns this todo
        if ($todo->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the todo
        $todo->delete();

        // Redirect back with success message
        return redirect()->route('home')->with('success', 'Todo deleted successfully!');
    }

    /**
     * NEW: Toggle todo status (complete/incomplete) with authorization
     */
    public function toggle(Request $request, Todo $todo)
    {
        // UPDATED: Ensure user owns this todo
        if ($todo->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Toggle the task status
        $todo->update([
            'task_status' => !$todo->task_status,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Todo status updated!');
    }
}
