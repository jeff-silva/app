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

    protected $hidden = [
	    'file',
	];

    protected $appends = ['url'];

    public function getUrlAttribute()
    {
        return \URL::to("/file/{$this->slug}");
    }

    public function mutatorSave()
    {
        if ($file = request()->file('file')) {
            $this->slug = implode('', [
                \Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)),
                '-',
                uniqid(),
                '.',
                $file->getClientOriginalExtension(),
            ]);
            $this->mime = $file->getClientMimeType();
            $this->ext = $file->getClientOriginalExtension();
            $this->size = $file->getSize();
        }
    }

    public function migrationSchema()
    {
        return [
            'fields' => [
                'id' => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
                'slug' => 'VARCHAR(255) DEFAULT NULL',
                'name' => 'VARCHAR(255) DEFAULT NULL',
                'mime' => 'VARCHAR(20) DEFAULT NULL',
                'ext' => 'VARCHAR(5) DEFAULT NULL',
                'size' => 'INT(10) DEFAULT NULL',
                'folder' => 'VARCHAR(255) DEFAULT NULL',
                'file' => 'LONGBLOB NULL DEFAULT NULL',
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
