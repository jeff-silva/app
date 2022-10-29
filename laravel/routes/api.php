<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

new \App\Http\Controllers\AppController;
new \App\Http\Controllers\AuthController;
new \App\Http\Controllers\FilesController;
new \App\Http\Controllers\LotoLotofacilController;
new \App\Http\Controllers\LotoMegasenaController;
new \App\Http\Controllers\PlacesController;
new \App\Http\Controllers\SettingsController;
new \App\Http\Controllers\UsersController;