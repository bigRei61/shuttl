<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email',
            'password'      => 'required|min:8|confirmed',
            'phone'         => 'required|string|max:20',
            'gender'        => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
        ], [
            'email.unique'      => 'This email is already in use by another account.',
            'password.min'      => 'Password must be at least 8 characters.',
            'password.confirmed'=> 'Passwords do not match.',
            'email.email'       => 'Please enter a valid email address.',
        ]);

        User::create([
            'name'          => $request->name,
            'email'         => $request->email,
            'password'      => Hash::make($request->password),
            'phone'         => $request->phone,
            'gender'        => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'role'          => 'player',
            'rating_value'  => 1000.00,
            'matches_played'=> 0,
        ]);

        return redirect()->route('login')
            ->with('success', 'Registration successful. Please log in.');
    }
}