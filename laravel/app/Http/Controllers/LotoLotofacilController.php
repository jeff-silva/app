<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LotoLotofacilController extends Controller
{
    public function onInit()
    {
        $this->apiResource([
            'except' => ['save', 'delete'],
        ]);

        $this->routeMatch(['post'], 'loto_lotofacil/import', 'import')->name('loto_lotofacil.import');
    }

    public function import()
    {
        return (new \App\Models\LotoLotofacil)->lotoImport();
    }
}
