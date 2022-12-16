<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class AppUserGroupController extends Controller
{
    public $model = \App\Models\AppUserGroup::class;

    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => [],
        ]);
    }
}
