<?php

namespace App\Models;

use App\Models\AppMail;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\ModelTrait;
use App\Models\AppUserGroup;
use Laravel\Sanctum\HasApiTokens;
use App\Models\AppUserNotification;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AppUser extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, ModelTrait;

    protected $table = 'app_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'group_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function validationRules()
    {
        $rules = [
            'name' => ['required'],
            'email' => ['required', 'email:rfc,dns', 'unique:app_user,id'],
        ];

        if (!$this->id) {
            $rules['password'] = ['required'];
        }

        return $rules;
    }

    public function mutatorSave()
    {
        $this->name = ucwords($this->name);
    }

    public function passwordRecover($email=null, $code=null, $password=null)
    {
        $user = false;
        $user_found = false;
        $password_changed = false;

        if ($email) {
            if ($user = $this->where('email', $email)->first()) {
                $user_found = true;
                if (!$user->remember_token) {
                    $user->remember_token = uniqid();
                    $user->save();
                    AppMail::sendTemplate($user->email, 'app-user-password-recover', [
                        'user' => $user,
                    ]);
                }
            }
        }

        if (!$user_found) {
            \App\Utils::throwError(400, 'User not found');
        }

        if ($code AND $password) {
            if ($user->remember_token==$code) {
                $user->password = \Hash::make($password);
                $user->remember_token = null;
                if ($user->save()) {
                    $password_changed = true;
                }
            } else {
                \App\Utils::throwError(400, 'Wrong token');
            }
        }

        return [
            'user_found' => $user_found,
            'password_changed' => $password_changed,
        ];
    }

    public function getRememberToken()
    {
        return $this;
    }

    public function scopeSendNotification($query, $data=[])
    {
        $return = [];
        foreach($query->get() as $user) {
            $data['user_id'] = $user->id;
            $notification = new AppUserNotification($data);
            $notification->save();
            $return[] = $notification;
        }
        return $return;
    }

    public function isRoot()
    {
        return $this->id==1 OR $this->group_id==1;
    }

    public function appUserGroup(): HasOne
    {
        return $this->hasOne(AppUserGroup::class, 'id', 'group_id');
    }

    public function appUserNotifications(): HasMany
    {
        return $this->hasMany(AppUserNotification::class, 'user_id', 'id');
    }
}
