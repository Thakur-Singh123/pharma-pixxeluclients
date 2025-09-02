<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Notifications\TaskAssignedNotification;

class TaskController extends Controller
{
    //Function for show all tasks
    public function index() {
        //Get tasks
        $tasks = Task::OrderBy('ID','DESC')->where('manager_id', auth()->id())->with('mr')->paginate(10);
        return view('manager.tasks.index', compact('tasks'));
    }

    //Function for create task
    public function create() {
        //Get mrs
        $mrs = User::find(auth()->id())->mrs;
        return view('manager.tasks.create', compact('mrs'));
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
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
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
        return view('manager.tasks.edit-task', compact('task_detail','mrs'));
    }

    //Function for update task
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([
            'mr_id' =>'required|exists:users,id',
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
            'title' => $request->title,
            'description' => $request->description,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);
        //If MR changed, notify the new MR
        if ($oldMrId != $request->mr_id) {
            $user = User::find($request->mr_id);
            if ($user) {
                $user->notify(new TaskAssignedNotification($task));
            }
        }
        return redirect()->route('manager.tasks.index')->with('success', 'Task updated successfully.');
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
}
