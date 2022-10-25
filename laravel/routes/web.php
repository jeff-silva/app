<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('index');
// });

Route::get('/api/swagger', function() {
    return view('api-swagger');
});

Route::get('{path}', function() {
    if ($content = realpath(public_path('app.html'))) { return file_get_contents($content); }
    return 'execute o comando "yarn generate" para que a view seja renderizada corretamente';
})->where('path', '(.*)');
