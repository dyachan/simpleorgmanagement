<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;
use App\Http\Controllers\WorklogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/logout', function () {
    return view('auth.logout');
});

Route::get('/addworklog', [WorklogController::class, 'addView']);

Route::get('/viewworklog', [WorklogController::class, 'get'])->middleware(Authenticate::class);;

Route::get('/addproyect', function () {
    return view('addProyect');
});
