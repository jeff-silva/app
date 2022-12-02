<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ow3dPlaces extends Model
{
    use HasFactory, \App\Traits\Model;

    protected $singular = 'OW3D Place';
    protected $plural = 'OW3D Places';
    protected $table = 'ow3d_places';
    protected $tableFields = [
        'id' => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
        'name' => 'VARCHAR(255) DEFAULT NULL',
        'x' => 'DECIMAL(10, 8) DEFAULT NULL',
        'y' => 'DECIMAL(11, 8) DEFAULT NULL',
        'created_at' => 'DATETIME DEFAULT NULL',
        'updated_at' => 'DATETIME DEFAULT NULL',
    ];
}
