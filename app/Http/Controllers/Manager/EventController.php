<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Events;
use App\Models\EventUser;
use App\Models\User;
use App\Notifications\EventAssignedNotification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventController extends Controller
{
    //Functions for show all events
    public function index() {
        //Get events
        $query = Events::where('manager_id', auth()->id());
         if(request()->filled('created_by')) {
             $query = $query->where('created_by', request('created_by'));
         }
        $events = $query->with('mr')->orderBy('created_at', 'desc')->where('is_active', 1)->paginate(10);
        return view('manager.events.index', compact('events'));
    }

    //public function for show waiting for approval events
    public function waitingForApproval() {
        //Get events
        $query = Events::where('manager_id', auth()->id());
         if(request()->filled('created_by')) {
             $query = $query->where('created_by', request('created_by'));
         }
        $events = $query->with('mr')->orderBy('created_at', 'desc')->where('is_active', 0)->paginate(10);
        return view('manager.events.waiting-for-approval', compact('events'));
    }

    //function for approved or rejected events
    public function approvedevents(Request $request, $id) {
        $event = Events::find($id);
        $event->is_active = 1;
        $event->save();

        $joinUrl     = url('/join-event/' . $event->id);
        $qrCodeImage = QrCode::format('png')->size(300)->generate($joinUrl);
        //Save QR code image to storage
        $filename = 'event_' . $event->id . '.png';
        $folder   = public_path('qr_codes');
        // Make sure the folder exists
        if (! file_exists($folder)) {
            mkdir($folder, 0775, true);
        }
        //Save the QR code image directly to public folder
        file_put_contents($folder . '/' . $filename, $qrCodeImage);
        //Update event with QR code path
        $event->qr_code_path = $filename;
        $event->save();
        return redirect()->back();
    }

    //function for rejected events
    public function rejectedevents(Request $request, $id) {
        $event = Events::find($id);
        $event->is_active = 0;
        $event->save();
        return redirect()->back();
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
        $event->created_by     = 'manager';
        $event->is_active = 1;
        $event->save();

        $joinUrl     = url('/join-event/' . $event->id);
        $qrCodeImage = QrCode::format('png')->size(300)->generate($joinUrl);

        //Save QR code image to storage
        $filename = 'event_' . $event->id . '.png';
        $folder   = public_path('qr_codes');
        // Make sure the folder exists
        if (! file_exists($folder)) {
            mkdir($folder, 0775, true);
        }
        //Save the QR code image directly to public folder
        file_put_contents($folder . '/' . $filename, $qrCodeImage);

        //Update event with QR code path
        $event->qr_code_path = $filename;
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

    //Function for active participations
    public function participations() {
        //Get participations
        $all_participations = EventUser::with(['event_detail.mr'])->OrderBy('ID', 'DESC')->paginate(5);
        // echo "<pre>"; print_r($all_participations->toArray());exit;
        return view('manager.event-users.active-participations', compact('all_participations'));
    }
}
