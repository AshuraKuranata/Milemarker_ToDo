<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use App\Http\Controllers\TodoListController;
use App\Http\Controllers\TodoController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;


// HOME ROUTE - Display hero for guests, user's todo lists for authenticated users

Route::get('/', function () {
    return Inertia::render('Home', [
        'user' => auth()->user() ? auth()->user()->load(['todolists.tasks']) : null,
    ]);
})->name('home');


// AUTHENTICATION ROUTES

// Show registration form
Route::get('/register', [RegisterController::class, 'create'])
    ->middleware('guest')
    ->name('register');
// Handle registration
Route::post('/register', [RegisterController::class, 'store'])
    ->middleware('guest');
// Show login form
Route::get('/login', [LoginController::class, 'create'])
    ->middleware('guest')
    ->name('login');
// Handle login
Route::post('/login', [LoginController::class, 'store'])
    ->middleware('guest');
// Handle logout
Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');


// TODO LISTS PAGE (SPA Route)
// REFACTORED: Dedicated route for authenticated users to view their todo lists
Route::get('/todolists', function () {
    return Inertia::render('TodoLists', [
        'user' => auth()->user()->load(['todolists.tasks']),
    ]);
})->middleware('auth')->name('todolists');


// TODO LIST CRUD ROUTES (Protected - Requires Authentication)

Route::middleware('auth')->group(function () {
    // Display all todo lists for a specific user
    Route::get('/users/{user}/todolists', [TodoListController::class, 'index'])->name('todolists.index');
    // Show form to create a new todo list for a user
    Route::get('/users/{user}/todolists/create', [TodoListController::class, 'create'])->name('todolists.create');
    // Store a new todo list for a user
    Route::post('/users/{user}/todolists', [TodoListController::class, 'store'])->name('todolists.store');
    // Show a specific todo list with its tasks
    Route::get('/todolists/{todolist}', [TodoListController::class, 'show'])->name('todolists.show');
    // Show form to edit a todo list
    Route::get('/todolists/{todolist}/edit', [TodoListController::class, 'edit'])->name('todolists.edit');
    // Update a todo list
    Route::put('/todolists/{todolist}', [TodoListController::class, 'update'])->name('todolists.update');
    // Delete a todo list
    Route::delete('/todolists/{todolist}', [TodoListController::class, 'destroy'])->name('todolists.destroy');


    // TODO (TASK) CRUD ROUTES (Protected - Requires Authentication)

    // Display all todos for a specific todo list
    Route::get('/todolists/{todolist}/todos', [TodoController::class, 'index'])->name('todos.index');
    // Show form to create a new todo for a todo list
    Route::get('/todolists/{todolist}/todos/create', [TodoController::class, 'create'])->name('todos.create');
    // Store a new todo for a todo list
    Route::post('/todolists/{todolist}/todos', [TodoController::class, 'store'])->name('todos.store');
    // Show a specific todo
    Route::get('/todos/{todo}', [TodoController::class, 'show'])->name('todos.show');
    // Show form to edit a todo
    Route::get('/todos/{todo}/edit', [TodoController::class, 'edit'])->name('todos.edit');
    // Update a todo
    Route::put('/todos/{todo}', [TodoController::class, 'update'])->name('todos.update');
    // Delete a todo
    Route::delete('/todos/{todo}', [TodoController::class, 'destroy'])->name('todos.destroy');
    // Toggle todo status (complete/incomplete)
    Route::patch('/todos/{todo}/toggle', [TodoController::class, 'toggle'])->name('todos.toggle');
});
