<?php

namespace App\Http\Controllers\MR;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Events;
use App\Models\User;

class EventController extends Controller
{
    //functions for event management can be added here
    public function index()
    {
        $events = Events::where('mr_id', auth()->id())->with('mr')->paginate(10);
        return view('mr.events.index', compact('events'));
    }
}
