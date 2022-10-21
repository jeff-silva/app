<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PlacesController extends Controller
{
    public function onInit()
    {
        $this->apiResource([
            'namespace' => 'places',
        ]);
    }
}
