<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function onInit()
    {
        $this->apiResource('users');
    }
}
