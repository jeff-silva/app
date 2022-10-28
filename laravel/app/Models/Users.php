<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Users extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable, \App\Traits\Model;

    protected $singular = 'Usuário';
    protected $plural = 'Usuários';
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'photo_id',
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

    public function migrationSchema()
    {
        return [
            'fields' => [
                'id' => 'BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT',
                'name' => 'VARCHAR(255) DEFAULT NULL',
                'email' => 'VARCHAR(255) DEFAULT NULL',
                'email_verified_at' => 'TIMESTAMP NULL DEFAULT NULL',
                'password' => 'VARCHAR(255) NOT NULL',
                'photo_id' => 'BIGINT(20) UNSIGNED DEFAULT NULL',
                'group_id' => 'BIGINT(20) UNSIGNED DEFAULT NULL',
                'remember_token' => 'VARCHAR(100) NULL DEFAULT NULL',
                'created_at' => 'DATETIME DEFAULT NULL',
                'updated_at' => 'DATETIME DEFAULT NULL',
            ],
            'fks' => [
                'users_photos' => 'FOREIGN KEY (`photo_id`) REFERENCES `files` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION',
                'users_groups' => 'FOREIGN KEY (`group_id`) REFERENCES `users_groups` (`id`) ON UPDATE NO ACTION ON DELETE NO ACTION',
            ],
        ];
    }

    public $seedAfter = 'users_groups';
    public function seed()
    {
        
        $model = self::firstOrNew(['id' => 1], [
            'name' => 'Root User',
            'email' => 'root@grr.la',
            'password' => \Hash::make('321321'),
        ]);

        $model->group_id = 1;
        $model->save();
    }

    public function demo()
    {
        $resp = \Http::get('https://randomuser.me/api/?results=5')->json();
        if (isset($resp['results']) AND is_array($resp['results'])) {
            foreach($resp['results'] as $user) {
                \App\Models\Users::create([
                    'name' => implode(' ', [ $user['name']['first'], $user['name']['last'] ]),
                    'email' => $user['email'],
                    'password' => \Hash::make('321321'),
                ]);
            }
        }
    }
}
