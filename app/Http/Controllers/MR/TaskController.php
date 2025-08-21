<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
   // Function for get tasks
    public function all_tasks() {
        //Get auth login detail
        $is_login_detail = auth()->id();
        //Get tasks
        $all_tasks = Task::with('mr')->where('mr_id', $is_login_detail)->orderBy('id', 'DESC')->paginate(10);
        return view('mr.tasks.all-tasks', compact('all_tasks'));
    }
}
