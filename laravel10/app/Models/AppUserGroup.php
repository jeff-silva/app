<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppUserGroup extends Model
{
    use HasFactory, \App\Traits\Model;
    
    protected $table = 'app_user_group';

    protected $fillable = [
        'slug',
        'name',
    ];
}
