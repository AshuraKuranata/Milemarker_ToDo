<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class RegisterController extends Controller
{
    /**
     * NEW: Show the registration form
     */
    public function create()
    {
        return Inertia::render('Auth/Register');
    }

    /**
     * NEW: Handle user registration
     */
    public function store(Request $request)
    {
        // Validate the registration data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Log the user in
        Auth::login($user);

        // REFACTORED: Redirect to todolists page instead of home
        return redirect()->route('todolists')->with('success', 'Registration successful! Welcome to your To-Do List.');
    }
}
