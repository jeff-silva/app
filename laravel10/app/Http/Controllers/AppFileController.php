<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppFileController extends Controller
{
    public function __construct()
    {
        $this->model = new \App\Models\AppFile;
        $this->middleware('auth:api', ['except' => []]);
        $this->apiResource('app_file');
    }

    // public function web()
    // {
    //     $this->route('get', '/file/{slug}', 'file');
    // }

    public function file($slug)
    {
        $file = \App\Models\AppFile::where('slug', $slug)->first();
        return response($file->content)->withHeaders([
            'Content-Type' => $file->mime,
            // 'Cache-Control' => 'no-store, no-cache',
            // 'Content-Disposition' => 'attachment; filename="logs.txt',
        ]);
    }
}
