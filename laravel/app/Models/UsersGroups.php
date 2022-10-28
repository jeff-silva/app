<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersGroups extends Model
{
    use HasFactory, \App\Traits\Model;

    protected $singular = 'Grupo de usuário';
    protected $plural = 'Grupos de usuários';
    protected $table = 'users_groups';

    public function migrationSchema()
    {
        return [
            'fields' => [
                'id' => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
                'name' => 'VARCHAR(255) DEFAULT NULL',
                'permissions' => 'TEXT',
                'created_at' => 'DATETIME DEFAULT NULL',
                'updated_at' => 'DATETIME DEFAULT NULL',
            ],
            'fks' => [],
        ];
    }
}
