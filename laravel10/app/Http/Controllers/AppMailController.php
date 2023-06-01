<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppMailController extends Controller
{
    public function __construct()
    {
        $this->model = new \App\Models\AppMail;
        $this->middleware('auth:api', ['except' => []]);
        $this->apiResource('app_mail');
    }
}
