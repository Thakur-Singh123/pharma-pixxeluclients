<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\MangerMR;

class TaskController extends Controller
{
    //Function for all tasks
    public function index() {
        //Get tasks
        $all_tasks = Task::orderBy('ID','DESC')->paginate(5);
        return view('mr.tasks.all-tasks', compact('all_tasks'));
    }

    //Function for create task
    public function create() {
        return view('mr.tasks.create');
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
            'status' => $request->status,
        ]);
        //Check if task created or not
        if ($is_create_task) {
            return redirect()->route('mr.tasks.index')->with('success', 'Task created successfully.');
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
            'status' => $request->status,
        ]);
        //Check if task updated or not
        if ($is_update_task) {
            return redirect()->route('mr.tasks.index')->with('success', 'Task updated successfully.');
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
}
