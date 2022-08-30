<?php

/**
 * Gerando rotas com PHP8 metadata
 * https://samirmhsnv.medium.com/laravel-routing-with-controller-only-using-php-8-attributes-on-laravel-routing-d6c22cd43140
 */

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function __construct()
    {
        $this->onInit();
    }

    public function onInit()
    {
        $this->autoRoutes();
    }

    public function route($methods, $path, $callback)
    {
        // $methods = is_array($methods)? $methods: [ $methods ];

        // $prefix = (new \ReflectionClass($this))->getShortName();
        // $prefix = (string) \Str::of(str_replace('Controller', '', $prefix))->studly()->kebab();
        // if (! $prefix) return;
        // $path = $prefix .'/'. trim($path, '/');

        // $call = [get_class($this), $callback];

        // $name = $prefix .'-'. (string) \Str::of($callback)->kebab();

        // return Route::match($methods, $path, $call)->name($name);
    }

    public function autoRoutes($params = [])
    {
        $params = array_merge([
            'except' => [],
        ], $params);

        if (!in_array('find', $params['except'])) {
            $this->route('get', '/find/:id', 'find');
        }
        
        if (!in_array('search', $params['except'])) {
            $this->route('get', '/search', 'search');
        }
        
        if (!in_array('save', $params['except'])) {
            $this->route('post', '/save', 'save');
        }
        
        if (!in_array('delete', $params['except'])) {
            $this->route('post', '/delete', 'delete');
        }
    }

    public function find($id)
    {
        return func_get_args();
    }
    
    public function search()
    {
        return func_get_args();
    }
    
    public function save()
    {
        return func_get_args();
    }
    
    public function delete()
    {
        return func_get_args();
    }
}
