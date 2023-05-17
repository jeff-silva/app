<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Route;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    public function route($methods = [], $path, $method)
    {
        return Route::match($methods, $path, [ get_called_class(), $method ]);
    }

    public function success($data)
    {
        return response()->json($data, 200);
    }

    public function error($code, $message, $fields=[])
    {
        return response()->json([
            'message' => $message,
            'fields' => $fields,
        ], $code);
    }
}
