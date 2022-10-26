<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory, \App\Traits\Model;

    protected $singular = 'Arquivo';
    protected $plural = 'Arquivos';
    protected $fillable = [
        'id',
        'slug',
        'name',
        'size',
        'mime',
        'folder',
        'file',
    ];

    public function migrationSchema()
    {
        return [
            'fields' => [
                'id' => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
                'slug' => 'VARCHAR(255) DEFAULT NULL',
                'name' => 'VARCHAR(255) DEFAULT NULL',
                'size' => 'INT(10) DEFAULT NULL',
                'mime' => 'VARCHAR(10) DEFAULT NULL',
                'folder' => 'VARCHAR(255) DEFAULT NULL',
                'file' => 'BLOB DEFAULT NULL',
                'created_at' => 'DATETIME DEFAULT NULL',
                'updated_at' => 'DATETIME DEFAULT NULL',
            ],
            'fks' => [],
        ];
    }

    public function onSeed()
    {
        // 
    }
}
