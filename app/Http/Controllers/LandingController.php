<?php

namespace App\Http\Controllers;

class LandingController extends Controller
{
    public function index()
    {
        // If an admin is already authenticated, send them straight to admin dashboard
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('landing');
    }
}