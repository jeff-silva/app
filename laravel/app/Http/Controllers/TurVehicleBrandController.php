<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class TurVehicleBrandController extends Controller
{
	public $model = \App\Models\TurVehicleBrand::class;

	public function onInit()
	{
		$this->middleware('auth:api', [
			'except' => [],
		]);
	}
}
