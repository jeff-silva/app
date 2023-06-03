<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Mail\Message;
use App\Models\AppMailTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppFile extends Model
{
    use HasFactory, ModelTrait;

    protected $table = 'app_file';

    protected $fillable = [
        'slug',
        'name',
        'size',
        'mime',
        'ext',
        'folder',
        'content',
    ];

    protected $hidden = [
        'content',
    ];

    protected $appends = [
        'is_text',
        'is_image',
        'url',
    ];

    protected function getIsTextAttribute()
    {
        $text_exts = ['htm', 'html', 'svg', 'txt', 'xml'];
        return str_starts_with($this->mime, 'text/') OR in_array($this->ext, $text_exts);
    }

    protected function getIsImageAttribute()
    {
        $text_exts = ['bmp', 'gif', 'jpg', 'jpeg', 'png', 'svg'];
        return str_starts_with($this->mime, 'image/') OR in_array($this->ext, $text_exts);
    }

    protected function getUrlAttribute()
    {
        return \URL::to("/api/file/{$this->slug}");
    }

    public function validationRules()
    {
        return [];
    }

    public function searchOptions($query, $params)
    {
        return [
            'total_size' => intval($this->sum('size')),
            'result_size' => intval($query->sum('size')),
        ];
    }
}
