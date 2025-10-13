<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MangerMR;
use App\Models\Task;
use App\Models\TaskTourPlan;
use Carbon\Carbon;

class TourPlanController extends Controller
{
    //Function for all tour plans
    public function tour_plans() {
        //Get tasks     
        $all_tour_plan = TaskTourPlan::with('doctor','mr')->orderBy('ID', 'DESC')
            ->whereDate('start_date', Carbon::now()->addDays(1)) 
            ->where('manager_id', auth()->id())
            ->paginate(5);
        return view('manager.tour-plans.all-tour-plans', compact('all_tour_plan'));
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

        return back()->with('success', 'Tour plan rejected successfully.');
    }
}
