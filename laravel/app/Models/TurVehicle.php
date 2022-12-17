<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurVehicle extends Model
{
	use HasFactory;
	use \App\Traits\Model;

	public $fillable = ['id', 'name', 'type_id', 'brand_id', 'model_id'];
}
