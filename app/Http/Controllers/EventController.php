<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use DB;

class EventController extends Controller
{
    /** event list */
    public function index()
    {
        $events = Event::orderBy('start_date', 'asc')->get();
        return view('event.index', compact('events'));
    }

    /** event add */
    public function create()
    {
        return view('event.create');
    }

    /** save record */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'event_type' => 'required|string|max:100',
            'organizer' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'is_public' => 'boolean',
        ]);
        
        DB::beginTransaction();
        try {
            $event = new Event;
            $event->title = $request->title;
            $event->description = $request->description;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->start_time = $request->start_time;
            $event->end_time = $request->end_time;
            $event->location = $request->location;
            $event->event_type = $request->event_type;
            $event->organizer = $request->organizer;
            $event->contact_email = $request->contact_email;
            $event->contact_phone = $request->contact_phone;
            $event->is_public = $request->has('is_public');
            $event->save();

            DB::commit();
            return redirect()->route('events.index')->with('success', 'Event created successfully!');
            
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to create event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create event. Please try again.');
        }
    }

    /** event edit view */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('event.edit', compact('event'));
    }

    /** update record */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'location' => 'required|string|max:255',
            'event_type' => 'required|string|max:100',
            'organizer' => 'required|string|max:255',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:20',
            'is_public' => 'boolean',
        ]);

        DB::beginTransaction();
        try {
            $event = Event::findOrFail($id);
            $event->title = $request->title;
            $event->description = $request->description;
            $event->start_date = $request->start_date;
            $event->end_date = $request->end_date;
            $event->start_time = $request->start_time;
            $event->end_time = $request->end_time;
            $event->location = $request->location;
            $event->event_type = $request->event_type;
            $event->organizer = $request->organizer;
            $event->contact_email = $request->contact_email;
            $event->contact_phone = $request->contact_phone;
            $event->is_public = $request->has('is_public');
            $event->save();

            DB::commit();
            return redirect()->route('events.index')->with('success', 'Event updated successfully!');
           
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to update event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update event. Please try again.');
        }
    }

    /** delete record */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $event = Event::findOrFail($id);
            $event->delete();
            
            DB::commit();
            return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
        } catch(\Exception $e) {
            DB::rollback();
            \Log::error('Failed to delete event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete event. Please try again.');
        }
    }

    /** view event details */
    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('event.show', compact('event'));
    }

    /** calendar view */
    public function calendar()
    {
        $events = Event::where('is_public', true)
                      ->orderBy('start_date', 'asc')
                      ->get();
        return view('event.calendar', compact('events'));
    }
}
