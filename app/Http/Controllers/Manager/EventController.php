<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Events;
use App\Models\EventUser;
use App\Models\User;
use App\Models\Doctor; 
use App\Models\DoctorMrAssignement;
use App\Notifications\EventAssignedNotification;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventController extends Controller
{
    //Functions for show all events
    public function index() {
        //Filter event
        $query = Events::where('manager_id', auth()->id());
        if(request()->filled('created_by')) {
            $query = $query->where('created_by', request('created_by'));
        }
        //Get events
        $events = $query->with('mr','doctor_detail')->OrderBy('ID', 'DESC')->where('is_active', 1)->paginate(5);

        return view('manager.events.index', compact('events'));
    }

    //Function for show waiting for approval events
    public function waitingForApproval() {
        //Filter event
        $query = Events::where('manager_id', auth()->id());
        if(request()->filled('created_by')) {
            $query = $query->where('created_by', request('created_by'));
        }
        //Get events
        $events = $query->with('mr','doctor_detail')->orderBy('ID', 'DESC')->where('is_active', 0)->paginate(5);
        // echo "<pre>";
        // print_r($events->toArray());
        // echo "</pre>";die;

        return view('manager.events.waiting-for-approval', compact('events'));
    }

    //Function for approved or rejected events
    public function approvedevents(Request $request, $id) {
        //Get event detail
        $event = Events::find($id);
        $event->is_active = 1;
        $event->save();
        //Get url
        $joinUrl     = url('/join-event/' . $event->id);
        $qrCodeImage = QrCode::format('png')->size(300)->generate($joinUrl);
        //Save QR code image to storage
        $filename = 'event_' . $event->id . '.png';
        $folder   = public_path('qr_codes');
        //Make sure the folder exists
        if (! file_exists($folder)) {
            mkdir($folder, 0775, true);
        }
        //Save the QR code image directly to public folder
        file_put_contents($folder . '/' . $filename, $qrCodeImage);
        //Update event with QR code path
        $event->qr_code_path = $filename;
        $event->save();

        return redirect()->route('manager.events.index')->with('success', 'Event approved successfully.');
    }

    //Function for rejected event
    public function rejectedevents(Request $request, $id) {
        $event = Events::find($id);
        $event->is_active = 0;
        $event->save();
        return redirect()->back()->with('success', 'Event rejected successfully.');
    }

    //Functions for create event
    public function create() {
        //Get logged-in manager
        $manager = auth()->user();
        //Get all MRs assigned
        $mrs = $manager->mrs;
        //Get all doctors
        $all_doctors = Doctor::whereHas('mr', function($query) use ($mrs) {
            $query->whereIn('users.id', $mrs->pluck('id'));
        })->orderBy('id','DESC')->where('status', 'active')->get();
        return view('manager.events.create', compact('mrs','all_doctors'));
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
        ]);
        //Create event
        $event = new Events();
        $event->mr_id = $request->mr_id; 
        $event->manager_id = auth()->id();
        $event->doctor_id = $request->doctor_id;
        $event->title = $request->title;
        $event->description = $request->description;
        $event->location = $request->location;
        $event->pin_code = $request->pin_code;
        $event->start_datetime = $request->start_datetime;
        $event->end_datetime = $request->end_datetime;
        $event->status = 'pending';
        $event->created_by     = 'manager';
        $event->is_active = 1;
        $event->save();

        //Assign new doctor MR
        DoctorMrAssignement::firstOrCreate([
            'doctor_id' => $request->doctor_id,
            'mr_id'     => $request->mr_id,
        ]);

        //Generate Qr code
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
    
    //Function to edit event
    public function edit($id) {
        //Get event detail 
        $event_detail = Events::find($id);
        //Get logged-in manager
        $manager = auth()->user();
        //Get MRs 
        $mrs = $manager->mrs;
        //Get all doctors
        $all_doctors = Doctor::whereHas('mr', function($query) use ($mrs) {
            $query->whereIn('users.id', $mrs->pluck('id'));
        })->orderBy('id', 'DESC')->where('status', 'active')->get();
        return view('manager.events.edit-event', compact('event_detail','mrs','all_doctors'));
    }
    
    //Function for update event status
    public function update_event_status(Request $request, $id) {
        //Get task detail
        $event = Events::findOrFail($id);
        //Get status
        $event->status = $request->status;
        //Update
        $event->save();

        return redirect()->back()->with('success', 'Event status updated successfully.');
    }

    //Function for update event
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'mr_id' =>'required|exists:users,id',
            'title' =>'required|string|max:255',
            'description' =>'nullable|string',
            'location' =>'nullable|string|max:255',
            'pin_code' =>'nullable|string|max:255',
            'start_datetime' =>'required|date',
            'end_datetime' =>'required|date|after_or_equal:start_datetime',
        ]);
        //Find event
        $event = Events::findOrFail($id);
        //Store old MR
        $oldMrId = $event->mr_id;
        //Update task
        $event->update([
            'mr_id' => $request->mr_id,
            'manager_id' => auth()->id(), 
            'doctor_id' => $request->doctor_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'pin_code' => $request->pin_code, 
            'created_by' => 'manager',
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
        ]);

        //Assign new doctor MR
        DoctorMrAssignement::firstOrCreate([
            'doctor_id' => $request->doctor_id,
            'mr_id'     => $request->mr_id,
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
        return view('manager.event-users.active-participations', compact('all_participations'));
    }
}
