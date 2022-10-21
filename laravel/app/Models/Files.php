<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory, \App\Traits\Model;

    protected $singular = 'Arquivo';
    protected $plural = 'Arquivos';
}
