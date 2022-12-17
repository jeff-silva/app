<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurVehicleModel extends Model
{
	use HasFactory;
	use \App\Traits\Model;

	public $fillable = ['id', 'name'];
}
