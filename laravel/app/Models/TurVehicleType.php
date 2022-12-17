<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurVehicleType extends Model
{
	use HasFactory;
	use \App\Traits\Model;

	protected $singular = 'tur_vehicle_type';
	protected $plural = 'tur_vehicle_types';
	protected $table = 'tur_vehicle_type';
	public $fillable = ['id', 'name'];
}
