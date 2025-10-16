<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\TADARecords;
use Illuminate\Http\Request;
use App\Models\User;

class TADAController extends Controller
{
    //function for view all TADA records
    public function index(Request $request)
    {
        $manger = auth()->user();
        //mr list with pending tada records
        $mrs = $manger->mrs()->pluck('users.id');
        if(request()->has('status') && in_array(request('status'), ['pending', 'approved', 'rejected'])) {
            $tada_records = TADARecords::OrderBy('ID', 'DESC')->whereIn('mr_id', $mrs)
                ->where('status', request('status'))
                ->paginate(5);
        } else {
            $tada_records = TADARecords::OrderBy('ID', 'DESC')->whereIn('mr_id', $mrs)->paginate(5);
        }
        return view('manager.TADA.index', compact('tada_records'));
    }

    //function for approve TADA record
    public function approve($id)
    {
        $tada_record              = TADARecords::findOrFail($id);
        $tada_record->status      = 'approved';
        $tada_record->approved_by = auth()->user()->id;
        $tada_record->approved_at = now();
        $tada_record->save();

        return redirect()->back()->with('success', 'TADA record approved successfully.');
    }

    //function for reject TADA record
    public function reject($id)
    {
        $tada_record              = TADARecords::findOrFail($id);
        $tada_record->status      = 'rejected';
        $tada_record->approved_by = auth()->user()->id;
        $tada_record->approved_at = now();
        $tada_record->save();
        return redirect()->back()->with('success', 'TADA record rejected successfully.');
    }

     //Function for edit TADA
    public function edit_tada($id) {
        //Get tada detail
        $tada_detail = TADARecords::find($id);
        return view('manager.TADA.edit-tada', compact('tada_detail'));
    }

    //Function for update tada
    public function update_tada(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'travel_date' => 'required|date',
            'place_visited' => 'required|string',
            'distance_km' => 'required|numeric',
            'ta_amount' => 'required|numeric',
            'da_amount' => 'required|numeric',
            'mode_of_travel' => 'required|string',
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
                'travel_date' => $request->travel_date,
                'place_visited' => $request->place_visited,
                'distance_km' => $request->distance_km,
                'ta_amount' => $request->ta_amount,
                'da_amount' => $request->da_amount,
                'total_amount' => $total_amount,
                'mode_of_travel' => $request->mode_of_travel,
                'purpose_of_visit' => $request->purpose,
                'remarks' => $request->remarks,
                'attachment' => $attachmentPath,
                'approved_by' => null,
                'approved_at' => null,
            ]);
            //Check if tada updated or not
            if ($is_update_tada) {
                return redirect()->route('manager.tada.index')->with('success', 'TADA claim updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update TADA claim.');
            }
        } else {
            //Total amount
            $total_amount = $request->ta_amount + $request->da_amount;
            //Update TADA with image
            $is_update_tada = TADARecords::where('id',$id)->update([
                'travel_date' => $request->travel_date,
                'place_visited' => $request->place_visited,
                'distance_km' => $request->distance_km,
                'ta_amount' => $request->ta_amount,
                'da_amount' => $request->da_amount,
                'total_amount' => $total_amount,
                'mode_of_travel' => $request->mode_of_travel,
                'purpose_of_visit' => $request->purpose,
                'remarks' => $request->remarks,
                'approved_by' => null,
                'approved_at' => null,
            ]);
            //Check if tada updated or not
            if ($is_update_tada) {
                return redirect()->route('manager.tada.index')->with('success', 'TADA claim updated successfully.');
            } else {
                return redirect()->back()->with('error', 'Failed to update TADA claim.');
            }
        }
    }

    //Function for delete tada
    public function delete_tada($id) {
        //Delete tada record
        $is_delete_tada = TADARecords::where('id', $id)->delete();
        //Check if tada deleted or not
        if ($is_delete_tada) {
            return redirect()->route('manager.tada.index')->with('success', 'TADA claim deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to delete TADA claim.');
        }
    }
}
