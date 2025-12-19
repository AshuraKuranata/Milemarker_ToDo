<?php

namespace App\Http\Controllers;

use App\Models\TodoList;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TodoListController extends Controller
{
    /**
     * UPDATED: Display all todo lists for a specific user
     */
    public function index(User $user)
    {
        $todolists = $user->todolists()->with('tasks')->get();

        return Inertia::render('TodoLists/Index', [
            'user' => $user,
            'todolists' => $todolists,
        ]);
    }

    /**
     * UPDATED: Show form to create a new todo list for a user
     */
    public function create(User $user)
    {
        return Inertia::render('TodoLists/Create', [
            'user' => $user,
        ]);
    }

    /**
     * UPDATED: Store a newly created todo list for authenticated user
     */
    public function store(Request $request, User $user)
    {
        // UPDATED: Ensure user can only create lists for themselves
        if ($user->id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validate([
            'list_name' => 'required|string|max:255',
        ]);

        // Create the todo list for the user
        $todolist = $user->todolists()->create([
            'list_name' => $validated['list_name'],
        ]);

        // Redirect back to home with success message
        return redirect()->route('home')->with('success', 'Todo list created successfully!');
    }

    /**
     * UPDATED: Display a specific todo list with its tasks
     */
    public function show(TodoList $todolist)
    {
        // Load the tasks relationship
        $todolist->load('tasks', 'user');

        return Inertia::render('TodoLists/Show', [
            'todolist' => $todolist,
        ]);
    }

    /**
     * UPDATED: Show form to edit a todo list
     */
    public function edit(TodoList $todolist)
    {
        return Inertia::render('TodoLists/Edit', [
            'todolist' => $todolist,
        ]);
    }

    /**
     * UPDATED: Update a todo list (with authorization)
     */
    public function update(Request $request, TodoList $todolist)
    {
        // UPDATED: Ensure user owns this todo list
        if ($todolist->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Validate the request
        $validated = $request->validate([
            'list_name' => 'required|string|max:255',
        ]);

        // Update the todo list
        $todolist->update($validated);

        // Redirect back with success message
        return redirect()->route('home')->with('success', 'Todo list updated successfully!');
    }

    /**
     * UPDATED: Delete a todo list (with authorization)
     */
    public function destroy(Request $request, TodoList $todolist)
    {
        // UPDATED: Ensure user owns this todo list
        if ($todolist->user_id !== $request->user()->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the todo list (cascades to todos via migration)
        $todolist->delete();

        // Redirect back with success message
        return redirect()->route('home')->with('success', 'Todo list deleted successfully!');
    }
}
