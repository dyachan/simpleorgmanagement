<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\ProyectInputResource;
use App\Models\Proyect;

class ProyectController extends Controller
{
    public function getNames(Request $request){
        return ProyectInputResource::collection(Proyect::all());
    }

    public function add(Request $request){
        return $request;
    }
}
