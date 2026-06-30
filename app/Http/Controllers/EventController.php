<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        // list events for players
        $events = Event::orderBy('start_date', 'desc')->get();
        return view('events.index', compact('events'));
    }
}
