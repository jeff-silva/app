<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory, \App\Traits\Model;

    protected $singular = 'Configuração';
    protected $plural = 'Configurações';
}
