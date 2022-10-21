<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function onInit()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->routeMatch(['post'], 'auth/login', 'login')->name('auth.login');
        $this->routeMatch(['post'], 'auth/logout', 'logout')->name('auth.logout');
        $this->routeMatch(['post'], 'auth/refresh', 'refresh')->name('auth.refresh');
        $this->routeMatch(['post'], 'auth/me', 'me')->name('auth.me');
    }

    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    public function refresh()
    {
        return [
            'access_token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    public function me()
    {
        $user = auth()->user();
        
        $settings = [];
        foreach(config('app-models-settings.public') as $key) {
            $settings[ $key ] = config($key);
        }

        return [
            'user' => $user,
            'settings' => $settings,
        ];
    }
}
