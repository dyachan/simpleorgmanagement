<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\WorklogResource;
use App\Http\Resources\UserResource;
use App\Models\Worklog;
use App\Models\User;
use App\Models\Proyect;
use Illuminate\Support\Facades\Auth;


class WorklogController extends Controller
{
    public function addView(Request $request, $errors = []){
        return view('addWorklog', ['user_id' => Auth::user()->id, 'proyects' => Proyect::all(), 'errors' => $errors]);
    }

    public function add(Request $request){
        $validator = $request->validate([
            'start' => 'required|date',
            'end' => 'required|date',
            'fk_user' => 'required',
            'fk_proyect' => 'required',
            'description' => 'required'
        ]);
 
        if ($validator->fails()) {
            return WorklogController::addView($request, $validator->errors());
        }
        
        Worklog::create([
            'start' => $request->start,
            'end' => $request->end,
            'fk_user' => $request->user_id,
            'fk_proyect' => $request->proyect_id,
            'description' => $request->$description
        ]);

        return WorklogController::get($request);
    }

    public function get(Request $request){
        // return Worklog::all();
        return view('viewWorklog', [
            'worklogs' => Worklog::all(),
            'users' => User::all()
        ]);
    }
}
