<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource([
    'only' => ['index', 'show'],
])]
class LotoLotofacilController extends Controller
{
    public $namespace = 'loto-lotofacil';
    public $model = \App\Models\LotoLotofacil::class;

    public function onInit()
    {
        $this->middleware('auth:api', [
            'except' => ['index', 'show'],
        ]);
    }

    #[\route('post', 'loto-lotofacil/import')]
    public function import()
    {
        return (new \App\Models\LotoLotofacil)->lotoImport();
    }
}
