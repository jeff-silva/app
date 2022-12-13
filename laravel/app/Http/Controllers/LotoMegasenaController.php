<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource([
    'only' => ['index', 'show'],
])]
class LotoMegasenaController extends Controller
{
    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => ['index', 'show'],
        ]);
    }

    #[\route('post', 'loto-megasena/import')]
    public function import()
    {
        return (new \App\Models\LotoMegasena)->lotoImport();
    }
}
