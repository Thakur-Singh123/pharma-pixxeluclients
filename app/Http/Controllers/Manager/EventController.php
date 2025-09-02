<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Events;
use App\Models\User;
use App\Notifications\EventAssignedNotification;
class EventController extends Controller
{
    //Functions for show all events
    public function index() {
        //Get events
        $events = Events::where('manager_id', auth()->id())->with('mr')->paginate(10);
        return view('manager.events.index', compact('events'));
    }

    //Functions for create event
    public function create() {
        //Get mrs
        $manager = auth()->user();
        $mrs =  $manager->mrs;
        return view('manager.events.create', compact('mrs'));
    }

    //Functions for storing event
    public function store(Request $request) {
        //Validation input fields
        $request->validate([
            'mr_id' =>'required|exists:users,id',
            'title' =>'required|string|max:255',
            'description' =>'nullable|string',
            'location' =>'nullable|string|max:255',
            'start_datetime' =>'required|date',
            'end_datetime' =>'required|date|after_or_equal:start_datetime',
            'status' =>'required|in:pending,in_progress,completed'
        ]);
        //Create event
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
        //Create mr
        $user = User::find($request->mr_id);
        //Notification
        if($user){
            $user->notify(new EventAssignedNotification($event));
        }
        return redirect()->route('manager.events.index')->with('success', 'Event created successfully.');
    }
    
    //Function for edit event
    public function edit($id) {
        //Get event 
        $event_detail = Events::find($id);
        //Get mrs
        $manager = auth()->user();
        $mrs =  $manager->mrs;
        return view('manager.events.edit-event', compact('event_detail','mrs'));
    }

    //Function for update event
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'mr_id' =>'required|exists:users,id',
            'title' =>'required|string|max:255',
            'description' =>'nullable|string',
            'location' =>'nullable|string|max:255',
            'start_datetime' =>'required|date',
            'end_datetime' =>'required|date|after_or_equal:start_datetime',
            'status' =>'required|in:pending,in_progress,completed'
        ]);
        //Find event
        $event = Events::findOrFail($id);
        //Store old MR
        $oldMrId = $event->mr_id;
        //Update task
        $event->update([
            'mr_id' => $request->mr_id,
            'manager_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'status' => $request->status,
        ]);
        //If MR changed, notify the new MR
        if ($oldMrId != $request->mr_id) {
            $user = User::find($request->mr_id);
            if ($user) {
                $user->notify(new EventAssignedNotification($event));
            }
        }
        return redirect()->route('manager.events.index')->with('success', 'Event updated successfully.');
    }

    //Function for destroy event
    public function destroy($id) {
        //Delete event
        $is_delete_event = Events::where('id', $id)->delete();
        //Check if event deleted or not
        if($is_delete_event) {
            return redirect()->route('manager.events.index')->with('success', 'Event deleted successfully.');
        } else {
            return back()->with('unsuccess', 'Opps something went wrong!');
        }
    }
}
