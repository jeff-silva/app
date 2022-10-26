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

    // public function onMigrate($table, $columns)
    // {
    //     if (! in_array('slug', $columns)) {
    //         return $table->string('slug')->after('id');
    //     }
    //     if (! in_array('size', $columns)) {
    //         return $table->integer('size')->after('name');
    //     }
    //     if (! in_array('mime', $columns)) {
    //         return $table->string('mime', 10)->after('size');
    //     }
    //     if (! in_array('folder', $columns)) {
    //         return $table->string('folder')->after('mime');
    //     }
    //     if (! in_array('file', $columns)) {
    //         return $table->binary('file')->after('folder');
    //     }
    // }

    // public function onSeed()
    // {
    //     // 
    // }
}
