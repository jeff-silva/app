<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class AppPlaceController extends Controller
{
    public $model = \App\Models\AppPlace::class;

    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => [],
        ]);
    }
}
