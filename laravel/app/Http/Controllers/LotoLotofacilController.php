<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LotoLotofacilController extends Controller
{
    public function onInit()
    {
        $this->apiResource('loto-lotofacil', [
            'only' => ['index', 'show'],
        ]);

        $this->routeMatch(['post'], 'loto_lotofacil/import', 'import')->name('loto_lotofacil.import');
    }

    public function import()
    {
        return (new \App\Models\LotoLotofacil)->lotoImport();
    }
}
