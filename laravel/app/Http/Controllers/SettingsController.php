<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function onInit()
    {
        $this->routeMatch(['get'], '/settings', 'list')->name('settings.get');
        $this->routeMatch(['post'], '/settings', 'save')->name('settings.save');
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
