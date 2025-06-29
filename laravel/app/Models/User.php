<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;           // ← interface do pacote

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /* -----------------------------------------------------------------
     |  Mass-assignment
     | -----------------------------------------------------------------*/
    /**
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /* -----------------------------------------------------------------
     |  Hidden attributes
     | -----------------------------------------------------------------*/
    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /* -----------------------------------------------------------------
     |  Casts
     | -----------------------------------------------------------------*/
    /**
     * @return array<string,string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'         => 'hashed',
        ];
    }

    /* -----------------------------------------------------------------
     |  Métodos exigidos por JWTSubject
     | -----------------------------------------------------------------*/
    /**
     * Identificador que vai dentro do token (normalmente a PK).
     */
    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    /**
     * Claims extras que você queira adicionar (retorne [] se não precisar).
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }
}
