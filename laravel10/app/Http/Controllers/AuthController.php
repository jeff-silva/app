<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
        $this->route(['post'], '/auth/login', 'login');
        $this->route(['post'], '/auth/logout', 'logout');
        $this->route(['post'], '/auth/refresh', 'refresh');
        $this->route(['post'], '/auth/me', 'me');
        $this->route(['post'], '/auth/register', 'register');
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        // return $this->success(['123']);
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            // return response()->json(['error' => 'Unauthorized'], 401);
            return $this->error(401, 'Unauthorized');
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return $this->success(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();
        return $this->success(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return $this->success([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function register()
    {
        // dump(123);
        // return $this->success(request()->all());
        // return $this->success(\App\Models\AppUser::create(request()->all()));
        try {
            return $this->success(\App\Models\AppUser::create(request()->all()));
        } catch(\Exception $e) {
            return [ $e->getMessage() ];
        }
    }
}
