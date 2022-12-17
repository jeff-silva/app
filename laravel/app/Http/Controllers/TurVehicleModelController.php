<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class TurVehicleModelController extends Controller
{
	public $model = \App\Models\TurVehicleModel::class;

	public function onInit()
	{
		$this->middleware('auth:api', [
			'except' => [],
		]);
	}
}
