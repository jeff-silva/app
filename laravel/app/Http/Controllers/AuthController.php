<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    public function onInit()
    {
        Route::post('auth/login', '\App\Http\Controllers\AuthController@login')->name('auth.login');
        Route::post('auth/logout', '\App\Http\Controllers\AuthController@logout')->name('auth.logout');
        Route::post('auth/refresh', '\App\Http\Controllers\AuthController@refresh')->name('auth.refresh');
        Route::post('auth/me', '\App\Http\Controllers\AuthController@me')->name('auth.me');
        $this->middleware('auth:api', [
            'except' => ['login'],
        ]);
    }

    /**
     * @body email = user@mail.com
     * @body password = password
     */
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
