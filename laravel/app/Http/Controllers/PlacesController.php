<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class PlacesController extends Controller
{
    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => [],
        ]);
    }
}
