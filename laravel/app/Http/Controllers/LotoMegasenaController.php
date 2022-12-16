<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource([
    'only' => ['index', 'show'],
])]
class LotoMegasenaController extends Controller
{
    public $model = \App\Models\LotoMegasena::class;

    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => ['index', 'show'],
        ]);
    }

    #[\route('post', 'loto_megasena/import')]
    public function import()
    {
        return (new \App\Models\LotoMegasena)->lotoImport();
    }
}
