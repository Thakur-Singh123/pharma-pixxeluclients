<?php
namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use App\Models\Events;
use App\Models\MangerMR;
use App\Models\User;
use App\Notifications\EventAssignedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class EventController extends Controller
{
    //functions for event management can be added here
    public function index()
    {
        $events = Events::where('mr_id', auth()->id())->with('mr')->paginate(10);
        return view('mr.events.index', compact('events'));
    }
    //function for add events
    public function create()
    {
        return view('mr.events.create');
    }

    //function for add events
    public function store(Request $request)
    {
        //Validation input fields
        $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'location'       => 'nullable|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime'   => 'required|date|after_or_equal:start_datetime',
            'status'         => 'required|in:pending,in_progress,completed',
        ]);

        //manager id
        $manager_id = MangerMR::where('mr_id', auth()->id())->value('manager_id');
        //Create event
        $event                 = new Events();
        $event->mr_id          = auth()->id();
        $event->manager_id     = $manager_id;
        $event->title          = $request->title;
        $event->description    = $request->description;
        $event->location       = $request->location;
        $event->start_datetime = $request->start_datetime;
        $event->end_datetime   = $request->end_datetime;
        $event->status         = $request->status;
        $event->save();
        //Generate QR code for event join link
        $joinUrl     = url('/join-event/' . $event->id);
        $qrCodeImage = QrCode::format('png')->size(300)->generate($joinUrl);

        // Save QR code image to storage
        $filename = 'event_' . $event->id . '.png';
        $folder   = public_path('qr_codes');
        // Make sure the folder exists
        if (! file_exists($folder)) {
            mkdir($folder, 0775, true);
        }
        // Save the QR code image directly to public folder
        file_put_contents($folder . '/' . $filename, $qrCodeImage);

        // Update event with QR code path
        $event->qr_code_path = $filename;
        $event->save();
        $user = User::find(auth()->id());
        //Notification
        if ($user) {
            $user->notify(new EventAssignedNotification($event));
        }
        return redirect()->route('mr.events.index')->with('success', 'Event created successfully.');
    }

    public function edit($id) {
        //Get event 
        $event_detail = Events::find($id);
        return view('mr.events.edit-event', compact('event_detail'));
    }

    //Function for update event
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'title' =>'required|string|max:255',
            'description' =>'nullable|string',
            'location' =>'nullable|string|max:255',
            'start_datetime' =>'required|date',
            'end_datetime' =>'required|date|after_or_equal:start_datetime',
            'status' =>'required|in:pending,in_progress,completed'
        ]);
        //Find event
        $event = Events::findOrFail($id);
        //Update task
        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
            'status' => $request->status,
        ]);
        return redirect()->route('mr.events.index')->with('success', 'Event updated successfully.');
    }

    //Function for destroy event
    public function destroy($id) {
        //Delete event
        $is_delete_event = Events::findOrFail($id);
        //check qr code path
        if ($is_delete_event->qr_code_path) {
            $qrCodePath = public_path('qr_codes/' . $is_delete_event->qr_code_path);
            if (file_exists($qrCodePath)) {
                unlink($qrCodePath);
            }
        }
        $is_delete_event->delete();
        //Check if event deleted or not
        if($is_delete_event) {
            return redirect()->route('mr.events.index')->with('success', 'Event deleted successfully.');
        } else {
            return back()->with('unsuccess', 'Opps something went wrong!');
        }
    }

    //function for show join form
    public function showJoinForm($id)
    {
        $event = Events::findOrFail($id);
        return view('join-event', compact('event'));
    }

    //function for submit 
    public function submitJoinForm(Request $request, $id)
    {
        $event = Events::findOrFail($id);

        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        // Save to pivot table or separate table
        DB::table('event_users')->insert([
            'event_id' => $event->id,
            'name' => $request->name,
            'phone' => $request->phone,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'You have successfully joined the event!');
    }
}
