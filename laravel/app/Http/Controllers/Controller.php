<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $model = false;

    public function __construct()
    {
        $model = '\App\Models\\'. str_replace('Controller', '', \Arr::last(explode('\\', get_called_class())));
        try { $this->model = app($model); } catch(\Exception $e) {}
        $this->onInit();
    }

    public function onInit()
    {
        // 
    }

    public function routeMatch($methods, $path, $callback)
    {
        $methods = is_array($methods)? $methods: [ $methods ];
        return Route::match($methods, $path, [get_called_class(), $callback]);
    }

    public function apiResource($params=[])
    {
        $params = (object) array_merge([
            'namespace' => 'app',
            'except' => [],
        ], $params);

        $routes = [
            (object) [
                'id' => 'search',
                'methods' => ['get'],
                'path' => "/{$params->namespace}/search",
                'callback' => 'search',
            ],
            (object) [
                'id' => 'find',
                'methods' => ['get'],
                'path' => "/{$params->namespace}/find/{id}",
                'callback' => 'find',
            ],
            (object) [
                'id' => 'save',
                'methods' => ['post'],
                'path' => "/{$params->namespace}/save",
                'callback' => 'save',
            ],
            (object) [
                'id' => 'delete',
                'methods' => ['delete'],
                'path' => "/{$params->namespace}/delete",
                'callback' => 'delete',
            ],
        ];

        foreach($routes as $route) {
            if (in_array($route->id, $params->except)) return;
            $this->routeMatch($route->methods, $route->path, $route->callback)
                ->name("{$params->namespace}.{$route->id}");
        }
    }

    public function error($status, $message, $fields=[])
    {
        throw new \Exception(json_encode([
            'status' => $status,
            'message' => $message,
            'fields' => $fields,
        ]));
    }

    public function search()
    {
        return request()->all();
    }

    public function find($id)
    {
        return request()->all();
    }

    public function save()
    {
        $id = request()->input('id', null);
        $model = $this->model->firstOrNew(['id' => $id], request()->all());
        return $model;
    }
    
    public function delete()
    {
        return request()->all();
    }
}
