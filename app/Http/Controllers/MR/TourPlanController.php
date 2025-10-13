<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\MangerMR;
use App\Models\Task;
use App\Models\TaskTourPlan;
use Carbon\Carbon;

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
        //Get Task
        $task_tour_plan = Task::find($id);
        return view('mr.tour-plans.edit-tour-plan', compact('all_doctors','task_tour_plan'));
    }


    public function update(Request $request, $id)
{
    $request->validate([
        'title' => 'required|string|max:255',
    ]);

    $task = Task::findOrFail($id);
    $mrId = auth()->id();
    $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');

    // Check if a pending edit request already exists
    $existingRequest = TaskTourPlan::where('task_id', $task->id)
        ->where('mr_id', $mrId)
        ->where('approval_status', 'Pending')
        ->first();

    if ($existingRequest) {
        return back()->with('error', 'You have already sent this task for approval.');
    }

    // Create new pending edit request
    TaskTourPlan::create([
        'task_id' => $task->id,
        'mr_id' => $mrId,
        'manager_id' => $managerId,
        'doctor_id' => $request->doctor_id,
        'title' => $request->title,
        'description' => $request->description,
        'location' => $request->location,
        'pin_code' => $request->pin_code,
        'start_date' => $request->start_date,
        'end_date' => $request->end_date,
        'approval_status' => 'Pending',
    ]);

    return back()->with('success', 'Tour plan sent for manager approval.');
}

  //Function for getn updated tour plan
    public function updated_tour_plans(Request $request) {
        //Get tasks     
        $all_task_tour_plan = TaskTourPlan::with('doctor')->orderBy('ID', 'DESC')->where('mr_id', auth()->id())->paginate(5);

        return view('mr.tour-plans.updated-tour-plans', compact('all_task_tour_plan'));
    }


}
