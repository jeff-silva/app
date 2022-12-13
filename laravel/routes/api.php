<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Symfony\Component\Finder\Finder;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

collect((new Finder)->files()->name('*.php')->in(app_path('Http/Controllers')))->each(function(SplFileInfo $file) {
  if ('Controller.php' == $file->getFilename()) return;
  app('\\App\Http\Controllers\\' . str_replace('.php', '', $file->getFilename()));
});

// dd( \App\Helpers\Openapi::get() );