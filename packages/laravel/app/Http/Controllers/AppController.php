<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AppController extends Controller
{

    public function onInit()
    {
        $this->route('get', 'load', 'load');
        $this->route('get', 'routes', 'routes');
        $this->route('get', 'test', 'test');
    }

    // api/app/load
    public function load()
    {
        return [
            'user' => (object) [],
            'settings' => (object) [],
        ];
    }

    // api/app/test
    public function test(Request $request)
    {
        $return['start'] = rand(0, 99);
        $return['final'] = rand(0, 99);
        $return['items'] = array_map(function($number) {
            $return['number'] = $number;
            return $return;
        }, range($return['start'], $return['final']));
        return $return;
    }
    
    // api/app/roputes
    public function routes()
    {
        $routes = [];
        foreach(Route::getRoutes() as $route) {
            if (\Str::startsWith($route->uri, '_ignition')) continue;
            if (\Str::startsWith($route->uri, 'sanctum')) continue;
            if (!$route->getName()) continue;
            $routes[] = [
                'uri' => $route->uri,
                'name' => $route->getName(),
            ];
        }
        return $routes;
    }
}
