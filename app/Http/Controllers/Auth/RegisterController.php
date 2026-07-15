<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function show(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->merge([
            'phone' => $this->normalizePhilippinePhoneNumber($request->input('phone')),
        ]);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'phone' => ['required', 'regex:/^9\d{9}$/'],
            'gender' => 'required|in:male,female,other',
            'date_of_birth' => 'required|date',
        ], [
            'email.unique' => 'This email is already in use by another account.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Passwords do not match.',
            'email.email' => 'Please enter a valid email address.',
            'phone.regex' => 'Please enter a valid Philippine mobile number.',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $this->formatPhilippinePhoneNumber($validated['phone']),
            'gender' => $validated['gender'],
            'date_of_birth' => $validated['date_of_birth'],
            'role' => 'player',
            'rating_value' => User::STARTING_RATING,
            'matches_played' => 0,
        ]);

        Auth::login($user);

        return redirect()->route('landing');
    }

    private function normalizePhilippinePhoneNumber(mixed $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        if (str_starts_with($digits, '63')) {
            $digits = substr($digits, 2);
        }

        if (str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return $digits;
    }

    private function formatPhilippinePhoneNumber(string $phone): string
    {
        return '+63 '.substr($phone, 0, 3).' '.substr($phone, 3, 3).' '.substr($phone, 6);
    }
}
