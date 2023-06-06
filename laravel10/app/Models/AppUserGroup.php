<?php

namespace App\Models;

use App\Traits\ModelTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AppUserGroup extends Model
{
    use HasFactory, ModelTrait;
    
    protected $table = 'app_user_group';

    protected $fillable = [
        'slug',
        'name',
        'permissions',
    ];

    public function mutatorRetrieve()
    {
        if ($this->isRoot()) {
            $this->permissions = array_keys(config('app_permissions.keys'));
        } else {
            $this->permissions = json_decode($this->permissions, true);
            $this->permissions = is_array($this->permissions) ? $this->permissions : [];
        }
    }

    public function mutatorSave()
    {
        if ($this->isRoot()) {
            $this->permissions = array_keys(config('app_permissions.keys'));
        }
    }

    public function getPermissions()
    {
        $return = [];
        foreach(config('app_permissions.keys') as $key_id => $key_name) {
            $return[] = [
                'id' => $key_id,
                'name' => $key_name,
            ];
        }
        return collect($return);
    }

    public function isRoot()
    {
        return $this->id == 1;
    }

    public function appUsers(): HasMany
    {
        return $this->hasMany(AppUser::class, 'group_id', 'id');
    }
}
