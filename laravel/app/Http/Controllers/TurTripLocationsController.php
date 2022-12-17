<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class TurTripLocationsController extends Controller
{
	public $model = \App\Models\TurTripLocations::class;

	public function onInit()
	{
		$this->middleware('auth:api', [
			'except' => [],
		]);
	}
}
