<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurTripLocations extends Model
{
	use HasFactory;
	use \App\Traits\Model;

	protected $singular = 'tur_trip_locations';
	protected $plural = 'tur_trip_locations';
	protected $table = 'tur_trip_locations';

	public $fillable = [
		'id',
		'name',
		'route',
		'number',
		'district',
		'city',
		'state',
		'state_code',
		'country',
		'country_code',
		'lat',
		'lng',
	];
}
