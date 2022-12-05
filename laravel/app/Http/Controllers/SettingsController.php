<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class SettingsController extends Controller
{
    public function onInit()
    {
        Route::get('/settings', '\App\Http\Controllers\SettingsController@list')->name('settings.get');
        Route::post('/settings', '\App\Http\Controllers\SettingsController@save')->name('settings.save');
        $this->middleware('auth:api', [
            'except' => [],
        ]);
    }

    public function list()
    {
        return [];
    }
    
    public function save()
    {
        return [];
    }
}
