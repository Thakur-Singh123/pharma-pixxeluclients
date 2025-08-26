<?php
namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use App\Models\TADARecords;
use Illuminate\Http\Request;

class TADAController extends Controller
{
    //function for view create TADA form
    public function create()
    {
        return view('mr.TADA.create');
    }

    //function for store TADA form
    public function store(Request $request)
    {
        $request->validate([
            'travel_date'    => 'required|date',
            'place_visited'  => 'required|string',
            'distance_km'    => 'required|numeric',
            'ta_amount'      => 'required|numeric',
            'da_amount'      => 'required|numeric',
            'mode_of_travel' => 'required|string',
            'purpose'        => 'required|string',
            'remarks'        => 'nullable|string',
            'attachment'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);
        // Handle file upload if exists
        $attachmentPath = null;
         if($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $extension = $file->getClientOriginalExtension();
            $attachmentPath = time() . '.' . $extension;
            $file->move(public_path('uploads/ta_da'), $attachmentPath);
        }
        //total amount
        $total_amount = $request->ta_amount + $request->da_amount;
        $create = TADARecords::create([
            'mr_id'            => auth()->id(),
            'travel_date'      => $request->travel_date,
            'place_visited'    => $request->place_visited,
            'distance_km'      => $request->distance_km,
            'ta_amount'        => $request->ta_amount,
            'da_amount'        => $request->da_amount,
            'total_amount'     => $total_amount,
            'mode_of_travel'   => $request->mode_of_travel,
            'purpose_of_visit' => $request->purpose,
            'remarks'          => $request->remarks,
            'attachment'       => $attachmentPath,
            'status'           => 'Pending',
            'approved_by'      => null,
            'approved_at'      => null,
        ]);
        if (! $create) {
            return redirect()->back()->with('error', 'Failed to submit TADA claim.');
        }
        return redirect()->route('mr.tada.index')->with('success', 'TADA claim submitted successfully.');
    }

    //function for view all TADA records
    public function index()
    {
        $query = TADARecords::where('mr_id', auth()->id());
        $query = $query->orderBy('created_at', 'desc');
        if(request()->has('status') && in_array(request('status'), ['pending', 'approved', 'rejected'])) {
            $query = $query->where('status', request('status'));
        }
        $tada_records = $query->paginate(10);
        return view('mr.TADA.index', compact('tada_records'));
    }
}
