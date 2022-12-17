<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class TurVehicleController extends Controller
{
	public $model = \App\Models\TurVehicle::class;

	public function onInit()
	{
		$this->middleware('auth:api', [
			'except' => [],
		]);
	}
}
