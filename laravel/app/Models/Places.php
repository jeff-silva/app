<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Places extends Model
{
    use HasFactory, \App\Traits\Model;

    protected $singular = 'Local';
    protected $plural = 'Locais';
}