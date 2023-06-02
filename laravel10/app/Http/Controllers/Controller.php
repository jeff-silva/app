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

    // public function __construct($context=null)
    // {
    //     $this->model = $this->model ? new $this->model : false;

    //     // if ($context=='api') $this->api();
    //     // if ($context=='web') $this->web();

    //     // dump($this);
    //     // dump(get_class_methods($this->route));
    // }
    
    // public static function api() {}
    // public static function web() {}

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
        return call_user_func_array(['\App\Utils', 'error'], func_get_args());
    }

    public function index(Request $request)
    {
        if (! $this->model) {
            return $this->error('Model not defined');
        }

        $data = $this->model->searchPaginate($request);
        return $this->success($data);
    }

    public function store(Request $request)
    {
        $data = $this->model->store($request);
        return $this->success($data);
    }

    public function show($id, Request $request)
    {
        // $request->merge(['find' => $id]);
        // $data = $this->model->search($request)->first();
        // if (!$data) return $this->error(404, 'Not found');
        // return $this->success($data);

        $request->merge(['find' => $id, 'limit' => 1]);
        $data = $this->model->searchPaginate($request);
        $data['data'] = isset($data['data'][0]) ? $data['data'][0] : null;
        if (!$data['data']) return $this->error(404, 'Not found');
        unset($data['pagination'], $data['params']);
        return $this->success($data);
    }

    public function update($id, Request $request)
    {
        $request->merge(['id' => $id]);
        $data = $this->model->store($request);
        return $this->success($data);
    }

    public function destroy($id)
    {
        return [ 'destroy', $id ];
    }
}
