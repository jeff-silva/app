<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $model = false;
    public $namespace = false;
    public $route = false;

    public function __construct()
    {
        $model = '\App\Models\\'. str_replace('Controller', '', \Arr::last(explode('\\', get_called_class())));
        try {
            $this->model = app($model);
            if ($this->model) {
                $this->namespace = $this->model->getTable();
            }
        } catch(\Exception $e) {}
        $this->onInit();
        $this->route = app('\Illuminate\Support\Facades\Route');
    }

    public function onInit()
    {
        // 
    }

    public function apiResource($path, $params = [])
    {
        $params = array_merge([
            'except' => [],
        ], $params);

        $routes = [
            'index' => (object) [
                'method' => 'get',
                'path' => "{$path}",
                'function' => 'index',
            ],
            'store' => (object) [
                'method' => 'post',
                'path' => "{$path}",
                'function' => 'store',
            ],
            'show' => (object) [
                'method' => 'get',
                'path' => "{$path}/{id}",
                'function' => 'show',
            ],
            'update' => (object) [
                'method' => 'put',
                'path' => "{$path}/{id}",
                'function' => 'update',
            ],
            'destroy' => (object) [
                'method' => 'delete',
                'path' => "{$path}/{id}",
                'function' => 'destroy',
            ],
            // 'valid' => (object) [
            //     'method' => 'post',
            //     'path' => "{$path}/valid",
            //     'function' => 'valid',
            // ],
            // 'import' => (object) [
            //     'method' => 'post',
            //     'path' => "{$path}/import",
            //     'function' => 'import',
            // ],
            // 'export' => (object) [
            //     'method' => 'get',
            //     'path' => "{$path}/export",
            //     'function' => 'export',
            // ],
        ];

        foreach($routes as $name => $data) {
            if (in_array($name, $params['except'])) continue;
            Route::match([$data->method], $data->path, [get_called_class(), $data->function])
                ->name("{$data->path}.{$name}");
        }
    }

    public function error($status, $message, $fields=[])
    {
        return \App\Utils::error($status, $message, $fields);
    }

    public function index(Request $request)
    {
        return $this->model->search($request);
    }

    public function store(Request $request)
    {
        $model = $this->model->firstOrNew(['id' => $request->id ], $request->all());
        $model->save();
        return $model;
    }

    public function show($id)
    {
        return $this->model->find($id);
    }

    public function update()
    {
        // 
    }

    public function destroy()
    {
        // 
    }

    public function import()
    {
        // 
    }

    public function export()
    {
        // 
    }

    // public function save()
    // {
    //     $model = $this->model->firstOrNew([
    //         'id' => request()->input('id', null),
    //     ], request()->all());
        
    //     $model->save();
    //     return $model;
    // }
    
    // public function delete()
    // {
    //     return request()->all();
    // }
}
