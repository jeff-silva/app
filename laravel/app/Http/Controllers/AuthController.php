<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AuthController extends Controller
{
    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => ['login'],
        ]);
    }

    /**
     * @body email = user@mail.com
     * @body password = password
     */
    #[\route('post', 'auth/login', [
        'description' => 'Login',
        'body' => [
            'email' => 'example@mail.com',
            'password' => '123456',
        ],
    ])]
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

    #[\route('post', 'auth/logout', [
        'description' => 'Logout',
    ])]
    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    #[\route('post', 'auth/refresh', [
        'description' => 'Refresh authentication',
    ])]
    public function refresh()
    {
        return [
            'access_token' => auth()->refresh(),
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ];
    }

    #[\route('post', 'auth/me', [
        'description' => 'Authenticated user info',
    ])]
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
