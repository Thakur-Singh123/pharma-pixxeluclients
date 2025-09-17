<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Problem;
use App\Models\Visit;

class ProblemController extends Controller
{

    //Function for all problems
    public function index() {
        //Get problems
        $all_problems = Problem::with('visit_details')->OrderBy('ID', 'DESC')->paginate(5);
        //echo "<pre>"; print_r($all_problems->toArray());exit;
        return view('mr.problems-challenges.all-problems', compact('all_problems'));
    }
    //Function for add problem
    public function create() {
        //Get visits
        $all_visits = Visit::OrderBy('ID', 'DESC')->get();
        return view('mr.problems-challenges.add-problem', compact('all_visits'));
    }

    //Function for store problem
    public function store(Request $request) {
        //Validate input fields
        $request->validate([ 
            'title' =>'required|string|max:255',
            'camp_type' =>'required',
            'visit_name' =>'required',
            'doctor_name' =>'required',
            'description' =>'required',
            'start_date' =>'required|date',
            'end_date' =>'required|date|after_or_equal:start_date',
        ]);
        //Create problem
        $is_create_problem = Problem::create([
            'mr_id' => Auth::id(),
            'title' => $request->title,
            'camp_type' => $request->camp_type,
            'visit_name' => $request->visit_name,
            'doctor_name' => $request->doctor_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
        ]);
        //Check if problem created or not
        if ($is_create_problem) {
            return redirect()->route('mr.problems.index')->with('success', 'Problem challenge created successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    //Function for edit
    public function edit($id) {
        //Get problem detail
        $problem_detail = Problem::find($id);
        //Get visits
        $all_visits = Visit::OrderBy('ID', 'DESC')->get();
        return view('mr.problems-challenges.edit-problem', compact('problem_detail','all_visits'));
    }

    //Function for update
    public function update(Request $request, $id) {
        //Validate input fields
        $request->validate([ 
            'title' =>'required|string|max:255',
            'camp_type' =>'required',
            'visit_name' =>'required', 
            'doctor_name' =>'required',
            'description' =>'required',
            'start_date' =>'required|date',
            'end_date' =>'required|date|after_or_equal:start_date',
        ]);
        //Update problem
        $is_update_problem = Problem::where('id', $id)->update([
            'mr_id' => Auth::id(),
            'title' => $request->title,
            'camp_type' => $request->camp_type,
            'visit_name' => $request->visit_name,
            'doctor_name' => $request->doctor_name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'description' => $request->description,
        ]);
        //Check if problem updated or not
        if ($is_update_problem) {
            return redirect()->route('mr.problems.index')->with('success', 'Problem challenge updated successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }

    //Function for delete
    public function destroy($id) {
        //Delete problem
        $is_delete_problem = Problem::where('id', $id)->delete();
        //Check if problem updated or not
        if ($is_delete_problem) {
            return redirect()->route('mr.problems.index')->with('success', 'Problem challenge deleted successfully.');
        } else {
            return back()->with('error', 'Opps something went wrong!');
        }
    }
}
