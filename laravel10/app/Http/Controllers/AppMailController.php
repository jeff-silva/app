<?php

namespace App\Http\Controllers;

use App\Models\AppMail;
use Illuminate\Http\Request;

class AppMailController extends Controller
{
    public function __construct()
    {
        $this->model = new \App\Models\AppMail;
        $this->middleware('auth:api', ['except' => []]);
        $this->apiResource('app_mail');
        $this->route('post', 'app_mail/test', 'test');
    }

    public function test(Request $request)
    {
        return [
            'sent' => AppMail::send($request->all()),
        ];
    }
}
