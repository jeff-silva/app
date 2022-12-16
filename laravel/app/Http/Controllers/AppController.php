<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AppController extends Controller
{
    public $namespace = 'app';

    public function onInit()
    {
        // 
    }

    // https://editor.swagger.io/
    // https://editor-next.swagger.io/
    #[\route('get', 'app/openapi')]
    public function openapi()
    {
        return \App\Helpers\Openapi::get();
    }
}
