<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppMailTemplateController extends Controller
{
    public function __construct()
    {
        $this->model = new \App\Models\AppMailTemplate;
        $this->middleware('auth:api', ['except' => []]);
        $this->apiResource('app_mail_template');
        $this->route(['post'], '/app_mail_template/test', 'test')->name('app_mail_template.test');
    }

    public function test()
    {
        // 
    }
}
