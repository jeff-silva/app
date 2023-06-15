<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AppFileController extends Controller
{
    public function __construct()
    {
        $this->model = new \App\Models\AppFile;
        $this->middleware('auth:api', ['except' => ['file', 'store']]);
        $this->apiResource('app_file');
        $this->route('get', 'file/{slug}', 'file');
    }

    // public function web()
    // {
    //     $this->route('get', '/file/{slug}', 'file');
    // }

    public function file($slug)
    {
        $file = \App\Models\AppFile::where('slug', $slug)->first();
        $headers = [
            'Content-Type' => $file->mime,
            // 'Cache-Control' => 'no-store, no-cache',
            // 'Content-Disposition' => "attachment; filename={$file->slug}",
        ];

        // dd($headers);

        return response($file->content)->withHeaders($headers);

        // return response()->stream(function() use($file) {
        //     return $file->content;
        // }, 200, $headers);
    }
}
