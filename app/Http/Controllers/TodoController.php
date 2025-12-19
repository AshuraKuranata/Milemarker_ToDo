<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use App\Models\TodoList;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoController extends Controller
{
    public function index(TodoList $todolist)
    {
        $todos = $todolist->tasks()->get();

        return Inertia::render('Todos/Index', [
            'todolist' => $todolist,
            'todos' => $todos,
        ]);
    }

    public function create(TodoList $todolist)
    {
        return Inertia::render('Todos/Create', [
            'todolist' => $todolist,
        ]);
    }

    public function store(Request $request, TodoList $todolist)
    {
        if ($todolist->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'due_by' => 'required|date',
            'task_priority' => 'required|in:Low,Medium,High',
            'task_status' => 'boolean',
        ]);

        $todo = $todolist->tasks()->create([
            'task_name' => $validated['task_name'],
            'due_by' => $validated['due_by'],
            'task_priority' => $validated['task_priority'],
            'task_status' => $validated['task_status'] ?? false,
            'user_id' => $todolist->user_id,
        ]);

        return redirect()->route('home')->with('success', 'Todo created successfully!');
    }

    public function show(Todo $todo)
    {
        $todo->load('todolist');

        return Inertia::render('Todos/Show', [
            'todo' => $todo,
        ]);
    }

    public function edit(Todo $todo)
    {
        return Inertia::render('Todos/Edit', [
            'todo' => $todo,
        ]);
    }

    public function update(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'task_name' => 'required|string|max:255',
            'due_by' => 'required|date',
            'task_priority' => 'required|in:Low,Medium,High',
            'task_status' => 'boolean',
        ]);

        $todo->update($validated);

        return redirect()->route('home')->with('success', 'Todo updated successfully!');
    }

    public function destroy(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $todo->delete();

        return redirect()->route('home')->with('success', 'Todo deleted successfully!');
    }

    public function toggle(Request $request, Todo $todo)
    {
        if ($todo->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $todo->update([
            'task_status' => !$todo->task_status,
        ]);

        return redirect()->back()->with('success', 'Todo status updated!');
    }
}
