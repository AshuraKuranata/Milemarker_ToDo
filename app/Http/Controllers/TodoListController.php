<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoListController extends Controller
{

    public function index(User $user)
    {
        $todolists = $user->todolists()->with('tasks')->get();

        return Inertia::render('TodoLists/Index', [
            'user' => $user,
            'todolists' => $todolists,
        ]);
    }

    public function create(User $user)
    {
        return Inertia::render('TodoLists/Create', [
            'user' => $user,
        ]);
    }

    public function store(Request $request, User $user)
    {
        if ($user->id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'list_name' => 'required|string|max:255',
        ]);

        $todolist = $user->todolists()->create([
            'list_name' => $validated['list_name'],
        ]);

        return redirect()->route('home')->with('success', 'Todo list created successfully!');
    }

    public function show(TodoList $todolist)
    {
        $todolist->load('tasks', 'user');

        return Inertia::render('TodoLists/Show', [
            'todolist' => $todolist,
        ]);
    }

    public function edit(TodoList $todolist)
    {
        return Inertia::render('TodoLists/Edit', [
            'todolist' => $todolist,
        ]);
    }

    public function update(Request $request, TodoList $todolist)
    {
        if ($todolist->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $validated = $request->validate([
            'list_name' => 'required|string|max:255',
        ]);

        $todolist->update($validated);

        return redirect()->route('home')->with('success', 'Todo list updated successfully!');
    }

    public function destroy(Request $request, TodoList $todolist)
    {
        if ($todolist->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        $todolist->delete();

        return redirect()->route('home')->with('success', 'Todo list deleted successfully!');
    }
}
