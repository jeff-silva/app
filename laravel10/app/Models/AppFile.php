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
    ];

    protected function getIsTextAttribute()
    {
        $text_exts = ['htm', 'html', 'svg', 'txt', 'xml'];
        return str_starts_with('text/', $this->mime) OR in_array($this->ext, $text_exts);
    }

    protected function getIsImageAttribute()
    {
        $text_exts = ['bmp', 'gif', 'jpg', 'jpeg', 'png', 'svg'];
        return str_starts_with('image/', $this->mime) OR in_array($this->ext, $text_exts);
    }

    public function validationRules()
    {
        return [];
    }
}
