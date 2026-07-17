<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectDeactivatedUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $authenticatedUser = Auth::user();

        if ($authenticatedUser instanceof User && $authenticatedUser->trashed()) {
            return $this->redirectToLogin($request);
        }

        $sessionUserId = $request->session()->get(Auth::guard()->getName());

        if ($sessionUserId !== null && $authenticatedUser === null) {
            $sessionUser = User::withTrashed()->find($sessionUserId);

            if ($sessionUser?->trashed()) {
                return $this->redirectToLogin($request);
            }
        }

        return $next($request);
    }

    private function redirectToLogin(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'email' => User::DEACTIVATED_MESSAGE,
        ]);
    }
}
