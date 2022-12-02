<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Ow3dPlacesController extends Controller
{
    public function onInit()
    {
        $this->apiResource('ow3d_places');
    }
}
