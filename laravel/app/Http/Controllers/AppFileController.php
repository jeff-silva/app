<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class AppFileController extends Controller
{
    public $model = \App\Models\AppFile::class;

    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => ['show'],
        ]);
    }
}
