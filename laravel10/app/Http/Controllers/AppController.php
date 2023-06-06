<?php

namespace App\Http\Controllers;

use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\AppSettings;
use Illuminate\Http\Request;

class AppController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth:api', ['except' => []]);
        $this->route(['get', 'post'], 'app', 'app');
    }

    public function app(Request $request)
    {
        if ($user = auth()->user()) {
            $user->load('appUserGroup');
        }

        return [
            'user' => $user,
            'settings' => AppSettings::getAll(),
            'permissions' => config('app_permissions.keys'),
            // 'access_token' => substr($access_token, -10),
        ];
    }
}
