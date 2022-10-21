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

    public function __construct()
    {
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
                'methods' => ['post'],
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

    public function search()
    {
        return ['?'];
    }

    public function find()
    {
        return ['?'];
    }

    public function save()
    {
        return ['?'];
    }
    
    public function delete()
    {
        return ['?'];
    }
}
