<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurVehicleBrand extends Model
{
	use HasFactory;
	use \App\Traits\Model;

	protected $singular = 'tur_vehicle_brand';
	protected $plural = 'tur_vehicle_brands';
	protected $table = 'tur_vehicle_brand';
	public $fillable = ['id', 'name'];
}
