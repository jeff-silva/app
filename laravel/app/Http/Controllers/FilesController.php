<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class FilesController extends Controller
{
    public function onInit()
    {
        $this->apiResource('files');
        $this->middleware('auth:api', [
            'except' => ['show'],
        ]);
    }
}
