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
}
