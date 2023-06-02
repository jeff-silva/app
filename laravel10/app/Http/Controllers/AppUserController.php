<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppUserController extends Controller
{
    public $model = \App\Models\AppUser::class;

    public function api()
    {
        $this->middleware('auth:api', ['except' => []]);
        $this->apiResource('app_user');
    }
}
