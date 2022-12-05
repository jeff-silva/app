<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Ow3dPlacesController extends Controller
{
    public function onInit()
    {
        $this->apiResource('ow3d_places');
        $this->middleware('auth:api', [
            'except' => ['index', 'show'],
        ]);
    }
}
