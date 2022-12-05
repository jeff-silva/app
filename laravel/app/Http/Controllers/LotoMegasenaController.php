<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class LotoMegasenaController extends Controller
{
    public function onInit()
    {
        Route::post('loto_megasena/import', '\App\Http\Controllers\LotoMegasenaController@import')->name('loto_megasena.import');
        $this->apiResource('loto-megasena', [
            'only' => ['index', 'show'],
        ]);
        $this->middleware('auth:api', [
            'except' => ['index', 'show'],
        ]);
    }

    public function import()
    {
        return (new \App\Models\LotoMegasena)->lotoImport();
    }
}
