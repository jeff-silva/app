<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\RouteAttributes\Attributes\Get;

class AppController extends Controller
{
    #[Get('app/test', name: "app-test")]
    public function test()
    {
        return [
            'random' => rand(0, 999),
        ];
    }
}
