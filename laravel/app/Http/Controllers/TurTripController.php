<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

#[\apiResource()]
class TurTripController extends Controller
{
	public $model = \App\Models\TurTrip::class;

	public function onInit()
	{
		$this->middleware('auth:api', [
			'except' => [],
		]);
	}
}
