<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function route($methods, $path, $method)
    {
        $methods = is_array($methods) ? $methods : [ $methods ];
        return Route::match($methods, $path, [ get_called_class(), $method ]);
    }

    public function apiResource($prefix)
    {
        return Route::apiResource($prefix, get_called_class());
    }

    public function success()
    {
        return call_user_func_array(['App\Utils', 'success'], func_get_args());
    }

    public function error()
    {
        return call_user_func_array(['App\Utils', 'error'], func_get_args());
    }

    public function index()
    {
        return [ 'index' ];
    }

    public function store()
    {
        return [ 'store' ];
    }

    public function show($id)
    {
        return [ 'show', $id ];
    }

    public function update($id)
    {
        return [ 'update', $id ];
    }

    public function destroy($id)
    {
        return [ 'destroy', $id ];
    }
}
