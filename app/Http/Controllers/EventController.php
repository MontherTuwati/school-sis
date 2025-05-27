<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('events.index', compact('events'));
    }

    public function create()
    {
        return view('events.create');
    }

    public function store(Request $request)
    {
        // Validation

        Event::create($request->all());

        return redirect()->route('events.index')->with('success', 'Event created successfully');
    }

    // Define other methods for update, edit, delete, etc.
}
