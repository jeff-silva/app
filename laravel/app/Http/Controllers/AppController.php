<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppController extends Controller
{
    public function onInit()
    {
        $this->route('get', 'app/load', 'load')->name('app-load');
        $this->route('get', 'app/test', 'test')->name('app-test');
    }

    public function load()
    {
        return [
            'user' => false,
            'settings' => false,
        ];
    }
    
    public function test()
    {
        return [
            'random' => rand(0, 999),
        ];
    }
}
