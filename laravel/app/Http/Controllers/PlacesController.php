<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class PlacesController extends Controller
{
    public function onInit()
    {
        $this->apiResource('places');
        $this->middleware('auth:api', [
            'except' => [],
        ]);
    }
}
