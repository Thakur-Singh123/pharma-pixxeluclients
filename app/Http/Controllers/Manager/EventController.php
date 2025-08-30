<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Events;
use App\Models\User;
use App\Notifications\EventAssignedNotification;
class EventController extends Controller
{
    //functions for event management can be added here
    public function index()
    {
        $events = Events::where('manager_id', auth()->id())->with('mr')->paginate(10);
        return view('manager.events.index', compact('events'));
    }

    //functions for creating, updating, deleting events can be added here
    public function create()
    {
        $manager = auth()->user();
        $mrs =  $manager->mrs;
        return view('manager.events.create', compact('mrs'));
    }

    //functions for storing events
    public function store(Request $request)
    {
        $request->validate([
            'mr_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'status' => 'required|in:pending,in_progress,completed'
        ]);

        $event = new Events();
        $event->mr_id = $request->mr_id;
        $event->manager_id = auth()->id();
        $event->title = $request->title;
        $event->description = $request->description;
        $event->location = $request->location;
        $event->start_datetime = $request->start_datetime;
        $event->end_datetime = $request->end_datetime;
        $event->status = $request->status;
        $event->save();

        $user = User::find($request->mr_id);
        // Notification
        if($user){
            $user->notify(new EventAssignedNotification($event));
        }
        return redirect()->route('manager.events.index')->with('success', 'Event created successfully.');
    }
}
