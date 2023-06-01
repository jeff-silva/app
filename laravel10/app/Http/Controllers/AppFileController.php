<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppFileController extends Controller
{
    public function __construct()
    {
        $this->model = new \App\Models\AppFile;
        $this->middleware('auth:api', ['except' => []]);
        $this->apiResource('app_file');
    }
}
