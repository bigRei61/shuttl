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
            ->with('organizer')
            ->whereIn('status', ['open', 'ongoing'])
            ->whereDate('end_date', '>=', today())
            ->orderByDesc('start_date')
            ->get();

        return view('landing', compact('featured'));
    }
}
