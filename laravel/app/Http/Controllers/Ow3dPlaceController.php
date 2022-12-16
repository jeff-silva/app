<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class Ow3dPlaceController extends Controller
{
    public $model = \App\Models\Ow3dPlace::class;

    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => ['index', 'show'],
        ]);
    }
}
