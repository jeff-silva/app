<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurDriver extends Model
{
	use HasFactory;
	use \App\Traits\Model;

	protected $singular = 'tur_driver';
	protected $plural = 'tur_drivers';
	protected $table = 'tur_driver';
	public $fillable = ['id', 'name'];
}
