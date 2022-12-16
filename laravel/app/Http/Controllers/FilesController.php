<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class FilesController extends Controller
{
    public $namespace = 'files';
    public $model = \App\Models\Files::class;

    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => ['show'],
        ]);
    }
}
