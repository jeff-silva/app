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
    protected $tableFields = [
        'id' => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
        'name' => 'VARCHAR(255) DEFAULT NULL',
        'value' => 'TEXT',
        'created_at' => 'DATETIME DEFAULT NULL',
        'updated_at' => 'DATETIME DEFAULT NULL',
    ];
    protected $tableFks = [];
    protected $fillable = [
        'name',
        'value',
    ];

    public function onSchedule($schedule)
    {
        $schedule->call(function () {
            $set = static::firstOrNew(['name' => 'cron-update']);
            $set->value = date('Y-m-d H:i:s');
            $set->save();
        })->everyMinute();
    }
}
