<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory, \App\Traits\Model;

    protected $singular = 'Configuração';
    protected $plural = 'Configurações';
    protected $table = 'settings';

    public function migrationSchema()
    {
        return [
            'fields' => [
                'id' => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
                'name' => 'VARCHAR(255) DEFAULT NULL',
                'value' => 'TEXT',
                'created_at' => 'DATETIME DEFAULT NULL',
                'updated_at' => 'DATETIME DEFAULT NULL',
            ],
            'fks' => [],
        ];
    }

    public function updateCronTime()
    {
        $set = self::firstOrNew(['name' => 'cron-update']);
        $set->value = \Carbon\Carbon::now();
        $set->save();
    }
}
