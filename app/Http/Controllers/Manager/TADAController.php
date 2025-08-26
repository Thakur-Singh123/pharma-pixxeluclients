<?php
namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\TADARecords;
use Illuminate\Http\Request;

class TADAController extends Controller
{
    //function for view all TADA records
    public function index(Request $request)
    {
        $manger = auth()->user();
        //mr list with pending tada records
        $mrs          = $manger->mrs()->pluck('users.id');
        if(request()->has('status') && in_array(request('status'), ['pending', 'approved', 'rejected'])) {
            $tada_records = TADARecords::whereIn('mr_id', $mrs)
                ->where('status', request('status'))
                ->paginate(10);
        } else {
            $tada_records = TADARecords::whereIn('mr_id', $mrs)->paginate(10);
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
}
