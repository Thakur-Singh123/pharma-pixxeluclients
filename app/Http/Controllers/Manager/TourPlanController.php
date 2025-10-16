<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MangerMR;
use App\Models\Doctor;
use App\Models\Task;
use App\Models\TaskTourPlan;
use Carbon\Carbon;
use App\Notifications\TourStatusNotification;

class TourPlanController extends Controller
{
    //Function for all tour plans
    public function tour_plans() {
        //Get tour plans   
        $all_tour_plan = TaskTourPlan::with('doctor','mr')->orderBy('ID', 'DESC')
            ->whereDate('start_date', Carbon::now()->addDays(1)) 
            ->where('manager_id', auth()->id())
            ->paginate(5);
        return view('manager.tour-plans.all-tour-plans', compact('all_tour_plan'));
    }

    //Function for edit tour plan
    public function edit_tour_plan($id) {
        //Get tour detail
        $tour_detail = TaskTourPlan::find($id);
        //Get mrs
        $mrs = User::find(auth()->id())->mrs;
        //Get doctors
        $all_doctors = Doctor::whereHas('mr', function($query) use ($mrs) {
            $query->whereIn('users.id', $mrs->pluck('id'));
        })->orderBy('id','DESC')->where('status', 'active')->get();
        return view('manager.tour-plans.edit-tour-plan', compact('tour_detail','all_doctors'));
    }

    //Function for update tour plan
    public function update_tour_plan(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'title' => 'required|string|max:255',
        ]);
        //Update tour plan
        $is_update_tour = TaskTourPlan::where('id', $id)->update([ 
            'doctor_id' => $request->doctor_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'pin_code' => $request->pin_code,
            'end_date' => $request->end_date,
        ]);
        //Check if tour plan updated or not
        if ($is_update_tour) {
            return redirect()->route('manager.all.tours')->with('success', 'Tour plan updated successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    //Function for approval tour plan
    public function approve_tour_plan(Request $request, $id) {
        //Get tour plan detail
        $tour_plan = TaskTourPlan::findOrFail($id);
        //Get task detail
        $task = Task::findOrFail($tour_plan->task_id);
        //Update data
        $task->update([
            'title' => $tour_plan->title,
            'description' => $tour_plan->description,
            'location' => $tour_plan->location,
            'pin_code' => $tour_plan->pin_code,
            'doctor_id' => $tour_plan->doctor_id,
            'start_date' => $tour_plan->start_date,
            'end_date' => $tour_plan->end_date,
            'is_approval' => 'Approved',
        ]);
        //Update status
        $tour_plan->update([
            'approval_status' => 'Approved',
        ]);
        //Get MR detail
        $mr = User::find($tour_plan->mr_id);
        if($mr){
            $mr->notify(new TourStatusNotification($tour_plan));
        }

        return back()->with('success', 'Tour plan approval successfully.');
    }

    //Function for reject tour plan
    public function reject_tour_plan(Request $request, $id) {
        //Get tour plan detail
        $tour_plan = TaskTourPlan::findOrFail($id);
        //Get task detail
        $task = Task::findOrFail($tour_plan->task_id);
        //Update tour plan status
        $tour_plan->update([
            'approval_status' => 'Rejected', 
        ]);
        //Update task status
        $task->update([
            'is_approval' => 'Rejected',
        ]);
        //Get MR detail
        $mr = User::find($tour_plan->mr_id);
        if($mr){
            $mr->notify(new TourStatusNotification($tour_plan));
        }
        
        return back()->with('success', 'Tour plan rejected successfully.');
    }
}
