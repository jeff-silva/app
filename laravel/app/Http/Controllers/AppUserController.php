<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class AppUserController extends Controller
{
    public $model = \App\Models\AppUser::class;

    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => [],
        ]);
    }
}
