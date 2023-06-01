<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppUserController extends Controller
{
    public function __construct()
    {
        $this->model = new \App\Models\AppUser;
        $this->middleware('auth:api', ['except' => ['index']]);
        $this->apiResource('app_user');
    }
}
