<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppPlaceController extends Controller
{
    public function __construct()
    {
        $this->model = new \App\Models\AppPlace;
        $this->middleware('auth:api', ['except' => []]);
        $this->apiResource('app_place');
    }
}
