<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppPlaceController extends Controller
{
    public $model = \App\Models\AppPlace::class;

    public function api()
    {
        $this->middleware('auth:api', ['except' => []]);
        $this->apiResource('app_place');
    }
}
