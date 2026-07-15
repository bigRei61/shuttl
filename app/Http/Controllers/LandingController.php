<?php

namespace App\Http\Controllers;

use App\Models\Event;

class LandingController extends Controller
{
    public function index()
    {
        // If an admin is already authenticated, send them straight to admin dashboard
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $featured = Event::featured()
            ->with([
                'organizer',
                'players' => fn ($query) => $query->whereKey(auth()->id()),
            ])
            ->whereDate('end_date', '>=', today())
            ->orderBy('start_date')
            ->orderBy('end_date')
            ->orderBy('id')
            ->limit(3)
            ->get();

        return view('landing', compact('featured'));
    }
}
