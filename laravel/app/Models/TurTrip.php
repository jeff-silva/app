<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurTrip extends Model
{
	use HasFactory;
	use \App\Traits\Model;

	protected $singular = 'tur_trip';
	protected $plural = 'tur_trips';
	protected $table = 'tur_trip';
	public $fillable = ['id', 'name', 'vehicle_id', 'driver_id'];
}
