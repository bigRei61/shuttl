<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('auth.login');
    }

    public function authenticate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'This field is required.',
            'password.required' => 'This field is required.',
        ]);

        $credentials = $request->only('email', 'password');

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            $deactivatedUser = User::withTrashed()
                ->where('email', $validated['email'])
                ->first();

            if ($deactivatedUser?->trashed() && Hash::check($validated['password'], $deactivatedUser->password)) {
                return back()->withErrors([
                    'email' => User::DEACTIVATED_MESSAGE,
                ])->withInput($request->only('email'));
            }

            return back()->withErrors([
                'email' => 'No account found with this email.',
            ])->withInput($request->only('email'));
        }

        $request->session()->regenerate();

        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Regular players should return to the landing (home) page after login
        return redirect()->route('landing');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
