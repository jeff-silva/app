<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppMailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
        $this->apiResource('app_mail');
        $this->route(['post'], '/app_mail/test', 'test')->name('app_mail.test');
    }

    public function test()
    {
        // 
    }
}
