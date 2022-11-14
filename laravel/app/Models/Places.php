<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Places extends Model
{
    use HasFactory, \App\Traits\Model;

    protected $singular = 'Local';
    protected $plural = 'Locais';
    protected $table = 'places';
    protected $tableFields = [
        'id' => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
        'name' => 'VARCHAR(255) DEFAULT NULL',
        'route' => 'VARCHAR(255) DEFAULT NULL',
        'number' => 'VARCHAR(10) DEFAULT NULL',
        'zipcode' => 'VARCHAR(10) DEFAULT NULL',
        'complement' => 'VARCHAR(255) DEFAULT NULL',
        'district' => 'VARCHAR(255) DEFAULT NULL',
        'city' => 'VARCHAR(255) DEFAULT NULL',
        'state' => 'VARCHAR(255) DEFAULT NULL',
        'state_short' => 'VARCHAR(255) DEFAULT NULL',
        'country' => 'VARCHAR(255) DEFAULT NULL',
        'country_short' => 'VARCHAR(255) DEFAULT NULL',
        'lat' => 'DECIMAL(10, 8) DEFAULT NULL',
        'lng' => 'DECIMAL(11, 8) DEFAULT NULL',
        'created_at' => 'DATETIME DEFAULT NULL',
        'updated_at' => 'DATETIME DEFAULT NULL',
    ];
    protected $tableFks = [];
}
