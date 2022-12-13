<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class Ow3dPlacesController extends Controller
{
    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => ['index', 'show'],
        ]);
    }
}
