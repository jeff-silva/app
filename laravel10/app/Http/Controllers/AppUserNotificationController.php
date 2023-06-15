<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppUserNotificationController extends Controller
{
    public function __construct()
    {
        $this->model = new \App\Models\AppUserNotification;
        $this->middleware('auth:api', ['except' => []]);
        $this->apiResource('app_user_notification');
    }
}
