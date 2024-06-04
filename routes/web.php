<?php

use Illuminate\Support\Facades\Route;
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
Route::post('/addworklog', [WorklogController::class, 'add']);

// Route::get('/viewworklog', function () {
//     return view('viewWorklog');
// });
Route::get('/viewworklog', [WorklogController::class, 'get']);

Route::get('/addproyect', function () {
    return view('addProyect');
});
