<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorklogController;
use App\Http\Controllers\ProyectController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::post('/addworklog', [WorklogController::class, 'add']);
// Route::post('/addproyect', [ProyectController::class, 'add']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
