<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppUserGroup extends Model
{
    use HasFactory, ModelTrait;
    
    protected $table = 'app_user_group';

    protected $fillable = [
        'slug',
        'name',
    ];
}
