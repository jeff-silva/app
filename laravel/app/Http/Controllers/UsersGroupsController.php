<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class UsersGroupsController extends Controller
{
    public $namespace = 'users-groups';
    public $model = \App\Models\UsersGroups::class;

    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => [],
        ]);
    }
}
