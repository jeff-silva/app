<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Helpers\Openapi;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public $model = false;
    public $namespace = false;
    public $route = false;

    public function __construct()
    {
        $this->model = $this->model? app($this->model): false;
        if ($this->model AND !$this->namespace) {
            $this->namespace = $this->model->getTable();
        }
        $this->metadataRoutes();
    }

    public function onInit()
    {
        // 
    }

    public function success($data)
    {
        return response()->json($data);
    }

    public function error($status, $message, $fields=[])
    {
        return \App\Utils::error($status, $message, $fields);
        // return response()->json([
        //     'status' => $status,
        //     'message' => $message,
        //     'fields' => $fields,
        // ]);
    }

    public function index(Request $request)
    {
        if (!$this->model) return $this->error(400, 'No model');
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
        if (!$this->model) return $this->error(400, 'No model');
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

    public function metadataRoutes()
    {
        $ref = new \ReflectionClass(get_called_class());
        
        foreach($ref->getAttributes() as $attr) {
            if ($attr->getName() != 'apiResource') return;
            list($params) = $attr->getArguments() + [[]];

            $params = array_merge([
                'except' => [],
                'only' => [],
            ], $params);

            $routes = [
                'index' => [
                    'methods' => ['get'],
                    'route' => "{$this->namespace}",
                    'params' => [
                        'as' => "{$this->namespace}.index",
                        'uses' => join('@', [ get_called_class(), 'index']),
                    ],
                    'description' => "list {$this->namespace}",
                    'header' => [],
                    'path' => [],
                    'query' => ['q' => ''],
                    'body' => [],
                ],
                'store' => [
                    'methods' => ['post'],
                    'route' => "{$this->namespace}",
                    'params' => [
                        'as' => "{$this->namespace}.store",
                        'uses' => join('@', [ get_called_class(), 'store']),
                    ],
                    'description' => "create {$this->namespace}",
                    'header' => [],
                    'path' => [],
                    'query' => [],
                    'body' => [],
                ],
                'show' => [
                    'methods' => ['get'],
                    'route' => "{$this->namespace}/{id}",
                    'params' => [
                        'as' => "{$this->namespace}.show",
                        'uses' => join('@', [ get_called_class(), 'show']),
                    ],
                    'description' => "view {$this->namespace}",
                    'header' => [],
                    'path' => ['id' => 123],
                    'query' => [],
                    'body' => [],
                ],
                'update' => [
                    'methods' => ['put'],
                    'route' => "{$this->namespace}/{id}",
                    'params' => [
                        'as' => "{$this->namespace}.update",
                        'uses' => join('@', [ get_called_class(), 'update']),
                    ],
                    'description' => "update {$this->namespace}",
                    'header' => [],
                    'path' => ['id' => 123],
                    'query' => [],
                    'body' => [],
                ],
                'destroy' => [
                    'methods' => ['delete'],
                    'route' => "{$this->namespace}/{id}",
                    'params' => [
                        'as' => "{$this->namespace}.destroy",
                        'uses' => join('@', [ get_called_class(), 'destroy']),
                    ],
                    'description' => "delete {$this->namespace}",
                    'header' => [],
                    'path' => ['id' => 123],
                    'query' => [],
                    'body' => [],
                ],
            ];

            foreach($routes as $name => $route) {
                if (in_array($name, $params['except'])) continue;
                if (!empty($params['only']) AND !in_array($name, $params['only'])) continue;
                Route::match($route['methods'], $route['route'], $route['params']);
                Openapi::path($route['methods'], $route['route'], [
                    'description' => $route['description'],
                    'tags' => [ $this->namespace ],
                    'header' => $route['header'],
                    'path' => $route['path'],
                    'query' => $route['query'],
                    'body' => $route['body'],
                ]);
            }
        }

        foreach($ref->getMethods() as $method) {
            foreach($method->getAttributes() as $attr) {
                if ($attr->getName() != 'route') return;
                list($methods, $route, $params) = $attr->getArguments() + ['get', '', []];
                $methods = is_array($methods)? $methods: [$methods];

                $params = (object) array_merge([
                    'as' => join('.', [ $this->namespace, $method->getName() ]),
                    'uses' => join('@', [ get_called_class(), $method->getName() ]),
                    'description' => '',
                    'header' => [],
                    'path' => [],
                    'query' => [],
                    'body' => [],
                ], $params);

                Route::match($methods, $route, [
                    'as' => $params->as,
                    'uses' => $params->uses,
                ]);
                Openapi::path($methods, $route, [
                    'description' => $params->description,
                    'tags' => [ $this->namespace ],
                    'header' => $params->header,
                    'path' => $params->path,
                    'query' => $params->query,
                    'body' => $params->body,
                ]);
            }
        }
    }
}
