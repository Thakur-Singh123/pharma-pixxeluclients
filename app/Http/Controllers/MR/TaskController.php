<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\MonthlyTask;
use App\Models\MangerMR;

class TaskController extends Controller
{
    //Function for all tasks
    public function index(Request $request) {
        $all_tasks = Task::orderBy('ID','DESC')->where('mr_id', auth()->id())->paginate(5);
        
        return view('mr.tasks.all-tasks', compact('all_tasks'));
    }

    //Function for create task
    public function create() {
        //Get tasks
        $all_tasks = Task::where('mr_id', auth()->id())->orderBy('id','DESC')->get();
        //Get tasks events
        $events = [];
        foreach ($all_tasks as $task) {
            $events[] = [
                'id'    => $task->id,
                'title' => $task->title,
                'description' => $task->description,
                'location' => $task->location,
                'status' => $task->status,
                'start' => $task->start_date,
                'end'   => $task->end_date,
                'color' => $task->status == 'completed' ? '#28a745' : 
                        ($task->status == 'in_progress' ? '#ffc107' : '#dc3545')
            ];
        }

        return view('mr.tasks.create', compact('events'));
    }

    //Function for submnit task
    public function store(Request $request) {
        //Validate input fields
        $request->validate([
            'title' =>'required|string|max:255',
        ]);
        //Get current MR ID
        $mrId = auth()->id();
        //Get manager ID linked to this MR
        $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');
        //Create task
        $is_create_task = Task::create([
            'mr_id' => $mrId,
            'manager_id' => $managerId,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => 'mr',
            'status' => $request->status,
            'is_active' => 0,
        ]);
        //Check if task created or not
        if ($is_create_task) {
            return back()->with('success', 'Task created successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    //Function for edit task
    public function edit($id) {
        //Get task detail
        $task_detail = Task::find($id);
        return view('mr.tasks.edit-task', compact('task_detail'));
    }

    //Function for update task
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'title' =>'required|string|max:255',
        ]);
        //Get current MR ID
        $mrId = auth()->id();
        //Get manager ID linked to this MR
        $managerId = MangerMR::where('mr_id', $mrId)->value('manager_id');
        //Update task
        $is_update_task = Task::where('id', $id)->update([
            'mr_id' => $mrId,
            'manager_id' => $managerId,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => 'mr',
            'status' => $request->status,
        ]);
        //Check if task updated or not
        if ($is_update_task) {
            return back()->with('success', 'Task updated successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    //Function for destroy task
    public function destroy($id) {
        //Delete task
        $is_delete_task = Task::where('id', $id)->delete();
        //Check if task deleted or not
        if ($is_delete_task) {
            return redirect()->route('mr.tasks.index')->with('success', 'Task deleted successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    //Function for manager assgin tasks
    public function assign_manger() {
        //Get manager tasks
        $manager_tasks = Task::where('created_by', 'Manager')->paginate(5);
        return view('mr.tasks.all-tasks-manager', compact('manager_tasks'));
    }

    //Function for himself tasks
    public function himself() {
        //Get himself tasks
        $himself_tasks = Task::where('created_by', 'mr')->paginate(5);
        return view('mr.tasks.all-tasks-mr', compact('himself_tasks'));
    }

    //function for pending approval
    public function pending_approval() {
        //Get pending tasks
        $pending_tasks = Task::where('created_by', 'mr')->where('is_active', 0)->paginate(5);
        return view('mr.tasks.pending-approval', compact('pending_tasks'));
    }


    //Send current month tasks to manager
    public function sendMonthlyTasksToManager(Request $request)
{

  
  
    $mrId = auth()->id();
    $currentMonth = now()->month;
    $currentYear = now()->year;

    $tasks = Task::where('mr_id', $mrId)
                 ->whereYear('start_date', $currentYear)
                 ->whereMonth('start_date', $currentMonth)
                 ->get();



    if($tasks->isEmpty()){
        return back()->with('error', 'No tasks found for current month!');
    
    }
    


    foreach ($tasks as $task) {
        //MonthlyTask
        MonthlyTask::updateOrCreate(
            ['task_id' => $task->id, 'mr_id' => $mrId],
            [
                'manager_id' => $task->manager_id,
                'is_approval' => 0
            ]
        );
    }

    return back()->with('error', 'All monthly tasks sent to manager for approval');

}

}
