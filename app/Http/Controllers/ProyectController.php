<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\ProyectInputResource;
use App\Models\Proyect;

class ProyectController extends Controller
{
    /**
     * view controllers
     */



     /**
     * api controllers
     */

     public function getNames(Request $request){
        return ProyectInputResource::collection(Proyect::all());
    }

    public function addOrUpdate(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required'
        ]);

        $validator->validate();

        $proyect = null;
        if($request->proyect_id){
            $proyect = Proyect::get('id', $request->proyect_id)->first();
            if(!$proyect){
                return response('Proyect id not found', 404);
            }
        }

        $mustSave = false;
        if(!$proyect){
            $proyect = Proyect::create(["name" => $request->name]);
        } else {
            $proyect->name = $request->name;
            $mustSave = true;
        }

        if($request->color){
            $proyect->color = $request->color;
            $mustSave = true;
        }

        if($mustSave){
            $proyect->save();
        }

        return response('ACK', 200);

    }
}
