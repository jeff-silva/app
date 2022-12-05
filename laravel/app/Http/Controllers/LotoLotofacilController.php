<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class LotoLotofacilController extends Controller
{
    public function onInit()
    {
        Route::post('loto-lotofacil/import', '\App\Http\Controllers\LotoLotofacilController@import')->name('loto_lotofacil.import');
        $this->apiResource('loto-lotofacil', [
            'only' => ['index', 'show'],
        ]);
        $this->middleware('auth:api', [
            'except' => ['index', 'show'],
        ]);
    }

    public function import()
    {
        return (new \App\Models\LotoLotofacil)->lotoImport();
    }
}
