<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MangerMR;
use App\Models\Task;
use App\Models\TaskTourPlan;
use Carbon\Carbon;
use App\Notifications\TourPlanNotification;

class TourPlanController extends Controller
{
    //Function for show all tasks
    public function index(Request $request) {
        //Get tasks
        $all_task_tour_plan = Task::with('doctor')->orderBy('ID', 'DESC')
            ->whereDate('start_date', Carbon::now()->addDays(1)) 
            ->where('mr_id', auth()->id())
            ->paginate(5);

        return view('mr.tour-plans.all-tour-plans', compact('all_task_tour_plan'));
    }

    //Function for edit task
    public function edit($id) {
        //Get mrs doctors
        $mr = auth()->user();
        $all_doctors = $mr->doctors()->where('status', 'active')->get();
        //Get task detail
        $task_tour_plan = Task::find($id);

        return view('mr.tour-plans.edit-tour-plan', compact('all_doctors','task_tour_plan'));
    }

    //Function for update tour plan
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'title' => 'required|string|max:255',
        ]);
        //Get task detail
        $task = Task::findOrFail($id);
        //Get auth login detail
        $mrId = auth()->id();
        //Get manager acc mr
        $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');
        //Check if tour plan exists or not
        $tourPlan = TaskTourPlan::where('task_id', $task->id)
            ->where('mr_id', $mrId)
            ->first();
        //Check if tour plan approved or not
        if ($tourPlan && $tourPlan->approval_status === 'Approved') {
            return redirect()->route('mr.update.plan')
            ->with('error', 'This tour plan is already approved. Please delete the plan before modifying it!');
        }
        //Check if tour plan pending or not
        if ($tourPlan && $tourPlan->approval_status === 'Pending') {
            return redirect()->route('mr.update.plan')
            ->with('error', 'This tour plan is already pending for manager approval.');
        }
        //Create or update the tour plan
        $tourPlan = TaskTourPlan::updateOrCreate(
            ['task_id' => $task->id, 'mr_id' => $mrId],
            [
                'manager_id'   => $managerId,
                'doctor_id'    => $request->doctor_id,
                'title'        => $request->title,
                'description'  => $request->description,
                'location'     => $request->location,
                'pin_code'     => $request->pin_code,
                'start_date'   => $request->start_date,
                'end_date'     => $request->end_date,
                'approval_status' => 'Pending',
            ]
        );
        //Get manager detail
        $manager = User::find($managerId);
        //Check if manager exists or not
        if ($manager) {
            //Send notification
            $manager->notify(new TourPlanNotification($tourPlan));
        }
        
        return redirect()->route('mr.update.plan')->with('success', 'Tour plan sent for manager approval.');
    }

    //Function for getn updated tour plan
    public function updated_tour_plans(Request $request) {
        //Get TourPlan detail   
        $all_updated_tour_plan = TaskTourPlan::with('doctor')->orderBy('ID', 'DESC')
            ->whereDate('start_date', Carbon::now()->addDays(1))->where('mr_id', auth()->id())
            ->paginate(5);
        return view('mr.tour-plans.updated-tour-plans', compact('all_updated_tour_plan'));
    }

    //Function for delete tour plan
    public function delete_tour_plan($id) {
        //Get tour plan detail
        $tour_plan = TaskTourPlan::findOrFail($id);
        //Get task detail
        $task = Task::findOrFail($tour_plan->task_id);
        //Delete tour plan
        $is_deleted = $tour_plan->delete();
        //Check if tour plan deleted or not
        if ($is_deleted) {
            //Update task status
            $task->update([
                'is_approval' => 'Pending',
            ]);
            return back()->with('success', 'Tour plan deleted successfully.');
        } else {
            return back()->with('error', 'Oops, something went wrong!');
        }
    }
}
