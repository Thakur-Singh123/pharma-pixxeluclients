<?php
namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use App\Models\TADARecords;
use Illuminate\Http\Request;
use App\Models\User;
use App\Notifications\TADACreateNotification;
class TADAController extends Controller
{
    //Function for view create TADA form
    public function create() {
        return view('mr.TADA.create');
    }

    //Function for store TADA form
    public function store(Request $request) {
        //Validate input fields
        $request->validate([
            'travel_date' => 'required|date',
            'place_visited' => 'required|string',
            'distance_km' => 'required|numeric',
            'ta_amount' => 'required|numeric', 
            'da_amount' => 'required|numeric',
            'mode_of_travel' => 'required|string',
            'outstation_stay' => 'required|string',
            'purpose' => 'required|string',
            'remarks' => 'nullable|string',
            'attachment' => 'required|file',
        ]);
        //Check if attachment exists or not
        $attachmentPath = null;
        if($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $attachmentPath = time() . '.' . $extension;
            $file->move(public_path('uploads/ta_da'), $attachmentPath);
        }
        //Total amount
        $total_amount = $request->ta_amount + $request->da_amount;
        //Create TADA
        $is_create_tada = TADARecords::create([
            'mr_id' => auth()->id(),
            'travel_date' => $request->travel_date,
            'place_visited' => $request->place_visited,
            'distance_km' => $request->distance_km,
            'ta_amount' => $request->ta_amount,
            'da_amount' => $request->da_amount,
            'total_amount' => $total_amount,
            'mode_of_travel' => $request->mode_of_travel,
            'purpose_of_visit' => $request->purpose,
            'outstation_stay' => $request->outstation_stay,
            'remarks' => $request->remarks,
            'attachment' => $attachmentPath,
            'status' => 'Pending',
            'approved_by' => null,
            'approved_at' => null,
        ]);
        //Check if tada created or not
        if (!$is_create_tada) {
            return redirect()->back()->with('error', 'Failed to create TADA claim.');
        }
        //notification
        $manager  = auth()->user()->managers->pluck('id');
        $manager  = $manager->first();
        $manager  = User::find($manager);
        $manager->notify(new TADACreateNotification($is_create_tada));
        return redirect()->route('mr.tada.index')->with('success', 'TADA claim created successfully.');
    }

    //Function for all TADA records
    public function index() {
        $query = TADARecords::where('mr_id', auth()->id());
        $query = $query->orderBy('created_at', 'desc');
        if(request()->has('status') && in_array(request('status'), ['pending', 'approved', 'rejected'])) {
            $query = $query->where('status', request('status'));
        }
        $tada_records = $query->paginate(5);
        return view('mr.TADA.index', compact('tada_records'));
    }

    //Function for edit TADA
    public function edit($id) {
        //Get tada detail
        $tada_detail = TADARecords::find($id);
        return view('mr.TADA.edit-tada', compact('tada_detail'));
    }

    //Function for update tada
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'travel_date' => 'required|date',
            'place_visited' => 'required|string',
            'distance_km' => 'required|numeric',
            'ta_amount' => 'required|numeric',
            'da_amount' => 'required|numeric',
            'mode_of_travel' => 'required|string',
            'outstation_stay' => 'required|string',
            'purpose' => 'required|string',
            'remarks' => 'nullable|string',
        ]);
        //Check if attachment exists or not
        $attachmentPath = null;
        if($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $attachmentPath = time() . '.' . $extension;
            $file->move(public_path('uploads/ta_da'), $attachmentPath);
            //Total amount
            $total_amount = $request->ta_amount + $request->da_amount;
            //Update TADA with image
            $is_update_tada = TADARecords::where('id',$id)->update([
                'mr_id' => auth()->id(),
                'travel_date' => $request->travel_date,
                'place_visited' => $request->place_visited,
                'distance_km' => $request->distance_km,
                'ta_amount' => $request->ta_amount,
                'da_amount' => $request->da_amount,
                'total_amount' => $total_amount,
                'mode_of_travel' => $request->mode_of_travel,
                'outstation_stay' => $request->outstation_stay,
                'purpose_of_visit' => $request->purpose,
                'remarks' => $request->remarks,
                'attachment' => $attachmentPath,
                'approved_by' => null,
                'approved_at' => null,
            ]);
            //Check if tada updated or not
            if ($is_update_tada) {
                //notification
                // $mr = auth()->user();
                // $manager  = $mr->managers()->pluck('users.id');
                // $manager  = $manager->first();
                // $manager  = User::find($manager);
                // $manager->notify(new TADACreateNotification($is_update_tada));
                return redirect()->route('mr.tada.index')->with('success', 'TADA claim updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update TADA claim.');
            }
        } else {
            //Total amount
            $total_amount = $request->ta_amount + $request->da_amount;
            //Update TADA with image
            $is_update_tada = TADARecords::where('id',$id)->update([
                'mr_id' => auth()->id(),
                'travel_date' => $request->travel_date,
                'place_visited' => $request->place_visited,
                'distance_km' => $request->distance_km,
                'ta_amount' => $request->ta_amount,
                'da_amount' => $request->da_amount,
                'total_amount' => $total_amount,
                'mode_of_travel' => $request->mode_of_travel,
                'purpose_of_visit' => $request->purpose,
                'outstation_stay' => $request->outstation_stay,
                'remarks' => $request->remarks,
                'approved_by' => null,
                'approved_at' => null,
            ]);
            //Check if tada updated or not
            if ($is_update_tada) {
                   //notification
                // $mr = auth()->user();
                // $manager  = $mr->managers()->pluck('users.id');
                // $manager  = $manager->first();
                // $manager  = User::find($manager);
                // $manager->notify(new TADACreateNotification($is_update_tada));
                return redirect()->route('mr.tada.index')->with('success', 'TADA claim updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update TADA claim.');
            }
        }
    }

    //Function for delete tada
    public function destroy($id) {
        //Delete tada record
        $is_delete_tada = TADARecords::where('id', $id)->delete();
        //Check if tada deleted or not
        if ($is_delete_tada) {
            return redirect()->route('mr.tada.index')->with('success', 'TADA claim deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to update TADA claim.');
        }
    }
}
