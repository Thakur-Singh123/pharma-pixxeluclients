<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Task;
use App\Models\MonthlyTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\TaskAssignedNotification;
use Carbon\Carbon;

class TaskController extends Controller
{
    //Function for show all tasks
    public function index(Request $request) {
        //Get tasks
        $query = Task::with('doctor')->orderBy('ID','DESC');
        if($request->filled('created_by')) {
             $query->where('created_by', $request->created_by);
        }
        $tasks = $query->orderBy('ID','DESC')->where('manager_id', auth()->id())->paginate(5);
        return view('manager.tasks.index', compact('tasks'));
    }

    //function for waiting for approval
    public function waitingForApproval() {
        //Get tasks
        $tasks = Task::OrderBy('ID','DESC')->where('manager_id', auth()->id())->where('is_active', 0)->with('mr')->paginate(10);
        return view('manager.tasks.waiting-for-approval', compact('tasks'));
    }
    
    //function for approved tasks
    public function approvedtasks($id) {
        //Get tasks
        $task = Task::find($id);
        $task->is_active = 1;
        $task->save();
        return redirect()->back()->with('success', 'Task approved successfully.');
    }

    //function for rejected tasks
    public function rejectedtasks($id) {
        //Get tasks
        $task = Task::find($id);
        $task->is_active = 0;
        $task->save();
        return redirect()->back()->with('success', 'Task rejected successfully.');
    }
    //Function for create task
    public function create() {
        //Get mrs
        $mrs = User::find(auth()->id())->mrs;
        //Get doctors
        $all_doctors = Doctor::OrderBy('ID','DESC')->get();
        return view('manager.tasks.create', compact('mrs','all_doctors'));
    }

    //Function for submit task
    public function store(Request $request) {
        //Validate input fields
        $request->validate([
            'mr_id' =>'required|exists:users,id',
            'title' =>'required|string|max:255',
            'description' =>'nullable|string', 
        ]);
        //Create task
        $task = Task::create([
            'mr_id' => $request->mr_id,
            'manager_id' => auth()->id(),
            'doctor_id' => $request->doctor_id, 
            'title' => $request->title, 
            'description' => $request->description,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'pin_code' => $request->pin_code,
            'created_by' => 'manager',
            'status' => 'Pending',
            'is_active' => 1,
        ]);
        //Get mr
        $user = User::find($request->mr_id);
        //Check if task assigned or not
        if($user){
            //Assign notification
            $user->notify(new TaskAssignedNotification($task));
        }
        return redirect()->route('manager.tasks.index')->with('success', 'Task assigned successfully.');
    }

    //Function for edit task
    public function edit($id) {
        //Get task
        $task_detail = Task::find($id);
        //get mrs
        $mrs = User::find(auth()->id())->mrs;
        //Get doctors
        $all_doctors = Doctor::OrderBy('ID','DESC')->get();
        return view('manager.tasks.edit-task', compact('task_detail','mrs','all_doctors'));
    }

    //Function for update task
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'mr_id' => 'required|exists:users,id',
            'doctor_id' => 'required|exists:users,id',
            'title' =>'required|string|max:255',
            'description' =>'nullable|string',
        ]);
        //Find task
        $task = Task::findOrFail($id);
        //Store old MR
        $oldMrId = $task->mr_id;
        //Update task
        $task->update([
            'mr_id' => $request->mr_id,
            'manager_id' => auth()->id(),
            'doctor_id'  => $request->doctor_id,
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'pin_code'   => $request->pin_code,
            'status' => 'Pending',
        ]);
        //If MR changed, notify the new MR
        if ($oldMrId != $request->mr_id) {
            $user = User::find($request->mr_id);
            if ($user) {
                $user->notify(new TaskAssignedNotification($task));
            }
        }
        return back()->with('success', 'Task updated successfully.');
    }

    //Function for delete task
    public function destroy($id) {
        //Delete task
        $is_delete_task = Task::where('id', $id)->delete();
        //Check if task deleted or not
        if($is_delete_task) {
            return redirect()->route('manager.tasks.index')->with('success', 'Task deleted successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }
     
    //Function for all tasks
    public function all_tasks()  {
        //Get all tasks 
        $tasks = MonthlyTask::with('task_detail','doctor_detail','mr_detail')->where('manager_id', auth()->id())->get();
        //tasks calendar data
        $events = [];
        foreach ($tasks as $task) {
            $taskDetail = $task->task_detail;
            $status = $task->is_approval;
            $color = match ($status) {
                1 => '#28a745', 
                0 => '#dc3545', 
                default => '#ffc107',
            };
            //Get task details
            $events[] = [
                'id'    => $taskDetail->id,
                'title' => $taskDetail->title ?? 'N/A',
                'start' => $taskDetail->start_date ?? null,
                'end'   => $taskDetail->end_date ?? null,
                'color' => $color,
                'extendedProps' => [
                'doctor_id'   => $taskDetail->doctor_id ?? null,
                'doctor_name' => $task->doctor_detail->doctor_name ?? 'N/A',
                'mr_id'       => $taskDetail->mr_id ?? null,
                'mr_name'     => $task->mr_detail->name ?? 'N/A',
                'pin_code'    => $taskDetail->pin_code ?? null,
                'description' => $taskDetail->description ?? null,
                'location'    => $taskDetail->location ?? null,
                'status'      => 'Pending',
                'is_approval' => $task->is_approval ?? 0, 
                ],
            ];
        }
        return view('manager.tasks.task-approval', compact('events'));
    }
    
    //Function for approve all tasks
    public function approveAll(Request $request) {
        //Get input request
        $current_month = $request->current_month;
        // echo $current_month;exit;
        //Get tasks
        $tasks = MonthlyTask::where('task_month', $current_month)->where('is_approval', 0)->get();
        //Check if tasks already approved or not
        if($tasks->isEmpty()) {
            return back()->with('error', 'Tasks already approved');
        }
        //Approve all tasks
        $tasks->each(function($task) {
            $task->update([
                'is_approval' => 1,  
                'updated_at'  => now(),
            ]);
        });
        return back()->with('success', 'All tasks approved successfully');
    }

    //Function for rejected tasks
    public function rejectAll(Request $request) {
        //Get input request
        $reject_month = $request->reject_month;
        //Get tasks
        $tasks = MonthlyTask::where('task_month', $reject_month)->where('is_approval', 1)->get();
        if ($tasks->isEmpty()) {
            return back()->with('error', 'Tasks already reject!');
        }
        //Check if tasks already rejected or not
        $tasks->each(function($task) {
            $task->update([
                'is_approval' => 0,
                'updated_at'  => now(),
            ]);
        });
        return back()->with('success', 'Tasks rejected successfully.');
    }
}