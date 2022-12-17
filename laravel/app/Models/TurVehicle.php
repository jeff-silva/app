<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurVehicle extends Model
{
	use HasFactory;
	use \App\Traits\Model;

	protected $singular = 'tur_vehicle';
	protected $plural = 'tur_vehicles';
	protected $table = 'tur_vehicle';
	public $fillable = ['id', 'name', 'type_id', 'brand_id', 'model_id'];
}
