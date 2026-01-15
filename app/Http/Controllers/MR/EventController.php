<?php
namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use App\Models\Events; 
use App\Models\EventUser;
use App\Models\MangerMR;
use App\Models\User;
use App\Models\Doctor;
use App\Notifications\MrEventCreatedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    //Functions for event management can be added here
    public function index(Request $request) {
        //Get events
        $query = Events::where('mr_id', auth()->id());
         if(request()->filled('created_by')) {
             $query = $query->where('created_by', request('created_by'));
         }
        $events = $query->with('doctor_detail')->orderBy('created_at', 'desc')->paginate(5);
        return view('mr.events.index', compact('events'));
    }

    //function for pending approval
    public function pendingForApproval() {
        //Get events
        $query = Events::where('mr_id', auth()->id());
         if(request()->filled('created_by')) {
             $query = $query->where('created_by', request('created_by'));
         }
        $events = $query->with('mr')->orderBy('created_at', 'desc')->where('is_active', 0)->paginate(5);
        return view('mr.events.pending-approval', compact('events'));
    }

      //Function for manager assgin events
    public function assign_manger() {
        //Get manager tasks
        $manager_event = Events::where('created_by', 'manager')->with('doctor_detail')->paginate(5);
        return view('mr.events.all-events-manager', compact('manager_event'));
    }

    //Function for himself events
    public function himself() {
        //Get himself tasks
        $himself_event = Events::where('created_by', 'mr')->with('doctor_detail')->paginate(5);
        return view('mr.events.all-evenst-mr', compact('himself_event'));
    }
    //Function for add events
    public function create() {
        //Get auth login
        $mr = Auth::user();
        //Get doctors
        $all_doctors = $mr->doctors()->orderBy('ID', 'DESC')->get();
        return view('mr.events.create', compact('all_doctors'));
    }

    //Function for add events
    public function store(Request $request) {
        //Validation input fields
        $request->validate([
            'title'  => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
        ]);

        //manager id
        $manager_id = MangerMR::where('mr_id', auth()->id())->value('manager_id');
        //Create event
        $event                 = new Events();
        $event->mr_id          = auth()->id();
        $event->manager_id     = $manager_id;
        $event->doctor_id      = $request->doctor_id;
        $event->title          = $request->title;
        $event->description    = $request->description;
        $event->location       = $request->location;
        $event->pin_code       = $request->pin_code;
        $event->start_datetime = $request->start_datetime;
        $event->end_datetime   = $request->end_datetime;
        $event->status         = 'pending';
        $event->created_by     = 'mr';
        $event->save();
        //Notification
        $manager = User::find($manager_id);
        if ($manager) {
            $manager->notify(new MrEventCreatedNotification($event));
        }

        return redirect()->route('mr.events.index')->with('success', 'Event created successfully.');
    }

    public function edit($id) {
        //Get auth login
        $mr = Auth::user();
        //Get doctors
        $all_doctors = $mr->doctors()->orderBy('ID', 'DESC')->get();
        //Get event 
        $event_detail = Events::findOrFail($id);

        return view('mr.events.edit-event', compact('event_detail','all_doctors'));
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
            'title' =>'required|string|max:255',
            'description' =>'nullable|string',
            'location' =>'nullable|string|max:255',
            'pin_code' =>'nullable|string|max:255',
            'start_datetime' =>'required|date',
            'end_datetime' =>'required|date|after_or_equal:start_datetime',
        ]);
        //Find event
        $event = Events::findOrFail($id);
        //Update task
        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'pin_code' => $request->pin_code,
            'doctor_id' => $request->doctor_id,
            'start_datetime' => $request->start_datetime,
            'end_datetime' => $request->end_datetime,
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
        $event = Events::with('doctor_detail')->findOrFail($id);
        // echo "<pre>"; print_r($event->toArray());exit;
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

        //UID generate
        $name = trim($request->name);  
        $phone = preg_replace('/\D/', '', $request->phone); 
        $firstFour = strtoupper(substr($name, 0, 4));
        $lastFour = substr($phone, -4);
        //Generate UID
        $uid = $firstFour . $lastFour;
        //Save to pivot table or separate table
        DB::table('event_users')->insert([
            'event_id' => $event->id,
            'name' => $request->name,
            'email' => $request->email,
            'kyc' => '002',
            'age' => $request->age,
            'sex' => $request->sex,
            'phone' => $request->phone,
            'pin_code' => $request->pin_code,
            'uid' => $uid,
            'disease' => $request->disease,
            'health_declare' => $request->health_declare,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'You have successfully joined the event!');
    }

    //Function for active participations
    public function participations() {
        //Get participations
        $all_participations = EventUser::with(['event_detail.mr'])->OrderBy('ID', 'DESC')->paginate(5);
        return view('mr.event-users.active-participations', compact('all_participations'));
    }
}
