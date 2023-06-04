<?php

namespace App\Http\Controllers;

use App\Models\AppUserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppUserGroupController extends Controller
{
    public function __construct()
    {
        $this->model = new \App\Models\AppUserGroup;
        $this->middleware('auth:api', ['except' => []]);
        $this->route('get', 'app_user_group/permissions', 'permissions')->name('app_user_group.permissions');
        $this->apiResource('app_user_group');
    }

    public function permissions()
    {
        return (new AppUserGroup)->getPermissions();
    }
}
