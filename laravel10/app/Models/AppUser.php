<?php

namespace App\Models;

use Tymon\JWTAuth\Contracts\JWTSubject;
// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class AppUser extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;
    use \App\Traits\Model;

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
}
