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

    public function onMigrate()
    {
        $columns['slug'] = function($table) {
            return $table->string('slug')->after('id');
        };
        $columns['size'] = function($table) {
            return $table->integer('size')->after('name');
        };
        $columns['mime'] = function($table) {
            return $table->string('mime', 10)->after('size');
        };
        $columns['folder'] = function($table) {
            return $table->string('folder')->after('mime');
        };
        $columns['file'] = function($table) {
            return $table->binary('file')->after('folder');
        };
        return $columns;
    }

    public function onSeed()
    {
        // 
    }
}
