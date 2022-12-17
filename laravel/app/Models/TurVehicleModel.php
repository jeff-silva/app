<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurVehicleModel extends Model
{
	use HasFactory;
	use \App\Traits\Model;

	protected $singular = 'tur_vehicle_model';
	protected $plural = 'tur_vehicle_models';
	protected $table = 'tur_vehicle_model';
	public $fillable = ['id', 'name'];
}
