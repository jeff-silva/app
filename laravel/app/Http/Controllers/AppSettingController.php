<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class AppSettingController extends Controller
{
    public $model = \App\Models\AppSetting::class;

    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => [],
        ]);
    }

    #[\route('get', 'settings')]
    public function list()
    {
        return [];
    }
    
    #[\route('post', 'settings', [
        'body' => [
            'setting1' => 'value1',
            'setting2' => 'value2',
        ],
    ])]
    public function save()
    {
        return [];
    }
}
