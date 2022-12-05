<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class UsersController extends Controller
{
    public function onInit()
    {
        $this->apiResource('users');
        $this->middleware('auth:api', [
            'except' => [],
        ]);
    }
}
