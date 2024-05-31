<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

use App\Http\Resources\WorklogResource;
use App\Http\Resources\UserResource;
use App\Models\Worklog;
use App\Models\User;
use App\Models\Proyect;


class WorklogController extends Controller
{
    public function addView(Request $request): view {
        return view('addWorklog');
    }

    public function add(Request $request): RedirectResponse {
        $validator = Validator::make($request->all(), [
            'start' => 'required|date',
            'end' => 'required|date',
            'user_id' => 'required',
            'proyect_id' => 'required|gt:0',
            'description' => 'required'
        ]);

        $validator->after(function ($validator) use ($request) {
            if ($request->start > $request->end) {
                $validator->errors()->add(
                    'end', 'end date must be greater than start date'
                );
            }
        });

        $validator->validate();

        Worklog::create([
            'start' => $request->start,
            'end' => $request->end,
            'fk_user' => $request->user_id,
            'fk_proyect' => $request->proyect_id,
            'description' => $request->description
        ]);

        return redirect()->action([WorklogController::class, 'get']);
        // return WorklogController::get($request);
    }

    public function get(Request $request){
        // return Worklog::all();
        return view('viewWorklog', [
            'worklogs' => Worklog::all(),
            'users' => User::all()
        ]);
    }
}
