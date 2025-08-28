<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Display a listing of events
     */
    public function index()
    {
        $events = Event::with(['creator'])
            ->orderBy('event_date', 'desc')
            ->orderBy('start_time')
            ->paginate(15);

        $stats = [
            'total' => Event::count(),
            'upcoming' => Event::upcoming()->count(),
            'today' => Event::today()->count(),
            'past' => Event::past()->count(),
        ];

        return view('events.index', compact('events', 'stats'));
    }

    /**
     * Show the form for creating a new event
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Store a newly created event
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'required_if:is_all_day,false|nullable|date_format:H:i',
            'end_time' => 'required_if:is_all_day,false|nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'event_type' => 'required|in:academic,social,sports,cultural,meeting,other',
            'priority' => 'required|in:low,medium,high',
            'is_all_day' => 'boolean',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $event = new Event();
            $event->title = $request->title;
            $event->description = $request->description;
            $event->event_date = $request->event_date;
            $event->start_time = $request->is_all_day ? null : $request->start_time;
            $event->end_time = $request->is_all_day ? null : $request->end_time;
            $event->location = $request->location;
            $event->event_type = $request->event_type;
            $event->priority = $request->priority;
            $event->is_all_day = $request->boolean('is_all_day', false);
            $event->color = $request->color ?? '#007bff';
            $event->is_active = $request->boolean('is_active', true);
            $event->created_by = auth()->id();
            $event->save();

            DB::commit();

            return redirect()->route('events.index')->with('success', 'Event created successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to create event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to create event. Please try again.');
        }
    }

    /**
     * Display the specified event
     */
    public function show($id)
    {
        $event = Event::with(['creator'])->findOrFail($id);
        return view('events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified event
     */
    public function edit($id)
    {
        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    /**
     * Update the specified event
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'start_time' => 'required_if:is_all_day,false|nullable|date_format:H:i',
            'end_time' => 'required_if:is_all_day,false|nullable|date_format:H:i|after:start_time',
            'location' => 'nullable|string|max:255',
            'event_type' => 'required|in:academic,social,sports,cultural,meeting,other',
            'priority' => 'required|in:low,medium,high',
            'is_all_day' => 'boolean',
            'color' => 'nullable|string|max:7',
            'is_active' => 'boolean'
        ]);

        try {
            DB::beginTransaction();

            $event = Event::findOrFail($id);
            $event->title = $request->title;
            $event->description = $request->description;
            $event->event_date = $request->event_date;
            $event->start_time = $request->is_all_day ? null : $request->start_time;
            $event->end_time = $request->is_all_day ? null : $request->end_time;
            $event->location = $request->location;
            $event->event_type = $request->event_type;
            $event->priority = $request->priority;
            $event->is_all_day = $request->boolean('is_all_day', false);
            $event->color = $request->color ?? '#007bff';
            $event->is_active = $request->boolean('is_active', true);
            $event->save();

            DB::commit();

            return redirect()->route('events.index')->with('success', 'Event updated successfully!');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Failed to update event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update event. Please try again.');
        }
    }

    /**
     * Remove the specified event
     */
    public function destroy($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->delete();

            return redirect()->route('events.index')->with('success', 'Event deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Failed to delete event: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete event. Please try again.');
        }
    }

    /**
     * Toggle event status
     */
    public function toggleStatus($id)
    {
        try {
            $event = Event::findOrFail($id);
            $event->is_active = !$event->is_active;
            $event->save();

            $status = $event->is_active ? 'activated' : 'deactivated';
            return redirect()->route('events.index')->with('success', "Event {$status} successfully!");
        } catch (\Exception $e) {
            \Log::error('Failed to toggle event status: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to toggle event status. Please try again.');
        }
    }

    /**
     * Get upcoming events
     */
    public function upcoming()
    {
        $events = Event::with(['creator'])
            ->upcoming()
            ->active()
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->limit(10)
            ->get();

        return response()->json($events);
    }

    /**
     * Get events by type
     */
    public function byType($type)
    {
        $events = Event::with(['creator'])
            ->byType($type)
            ->active()
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();

        return response()->json($events);
    }

    /**
     * Get events by priority
     */
    public function byPriority($priority)
    {
        $events = Event::with(['creator'])
            ->byPriority($priority)
            ->active()
            ->orderBy('event_date')
            ->orderBy('start_time')
            ->get();

        return response()->json($events);
    }

    /**
     * Export events to CSV
     */
    public function export()
    {
        $events = Event::with(['creator'])->get();

        $filename = 'events_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($events) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, [
                'Title', 'Description', 'Date', 'Start Time', 'End Time', 
                'Location', 'Type', 'Priority', 'All Day', 'Status', 'Created By'
            ]);
            
            foreach ($events as $event) {
                fputcsv($file, [
                    $event->title,
                    $event->description ?? 'N/A',
                    $event->formatted_date,
                    $event->formatted_start_time,
                    $event->formatted_end_time,
                    $event->location ?? 'N/A',
                    $event->event_type_label,
                    $event->priority_label,
                    $event->is_all_day ? 'Yes' : 'No',
                    $event->status,
                    $event->creator ? $event->creator->name : 'N/A'
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
