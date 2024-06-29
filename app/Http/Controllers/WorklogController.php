<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

use App\Http\Resources\WorklogResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserWorklogsResource;
use App\Models\Worklog;
use App\Models\User;
use App\Models\Proyect;

class WorklogController extends Controller
{
    /**
     * view controllers
     */

    public function addView(Request $request): view {
        return view('addWorklog');
    }
    
    public function get(Request $request){
        return view('viewWorklog');
    }


    /**
     * api controllers
     */

    public function add(Request $request) {
        Log::info("enter");
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

        Log::info("valid");
        $worklog = null;
        if($request->worklog_id){
            $worklog = Worklog::get('id', $request->worklog_id)->first();
            if(!$worklog){
                return response('Worklog id not found', 404);
            }
        }

        if(!$worklog){
            $worklog = Worklog::create([
                'start' => $request->start,
                'end' => $request->end,
                'fk_user' => $request->user_id,
                'fk_proyect' => $request->proyect_id,
                'description' => $request->description
            ]);
        } else {
            Log::info("edit");
            $mustSave = false;
            if((new Carbon($request->start)) != $worklog->start){
                $worklog->start = $request->start;
                $mustSave = true;
            }

            if((new Carbon($request->end)) != $worklog->end){
                $worklog->end = $request->end;
                $mustSave = true;
            }

            if($request->user_id != $worklog->fk_user){
                $worklog->fk_user = $request->user_id;
                $mustSave = true;
            }

            if($request->proyect_id != $worklog->fk_proyect){
                $worklog->fk_proyect = $request->proyect_id;
                $mustSave = true;
            }

            if($request->description != $worklog->description){
                $worklog->description = $request->description;
                $mustSave = true;
            }

            if($mustSave){
                $worklog->save();
            }
        }

        return response('ACK', 200);

        // return redirect()->action([WorklogController::class, 'get']);
        // return WorklogController::get($request);
    }

    public function getUserWorklog(Request $request){
        return new UserWorklogsResource(User::where("id", $request->userID)->first());
    }
}
