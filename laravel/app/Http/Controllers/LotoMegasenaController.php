<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LotoMegasenaController extends Controller
{
    public function onInit()
    {
        $this->apiResource('loto-megasena', [
            'only' => ['index', 'show'],
        ]);

        $this->routeMatch(['post'], 'loto_megasena/import', 'import')->name('loto_megasena.import');
    }

    public function import()
    {
        return (new \App\Models\LotoMegasena)->lotoImport();
    }
}
