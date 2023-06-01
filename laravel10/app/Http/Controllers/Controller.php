<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public $model = false;
    
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

    public function index(Request $request)
    {
        if (! $this->model) {
            return $this->error('Model not defined');
        }

        $data = $this->model->search($request);
        return $this->success($data);
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
