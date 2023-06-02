<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppMailController extends Controller
{
    public $model = \App\Models\AppMail::class;

    public function api()
    {
        $this->middleware('auth:api', ['except' => []]);
        $this->apiResource('app_mail');
    }
}
