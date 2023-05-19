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
        return Route::match($methods, $path, [ get_called_class(), $method ]);
    }

    public function success()
    {
        return call_user_func_array(['App\Utils', 'success'], func_get_args());
    }

    public function error()
    {
        return call_user_func_array(['App\Utils', 'error'], func_get_args());
    }
}
