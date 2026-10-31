<?php

namespace App\Models;

<<<<<<< HEAD
=======
// use Illuminate\Contracts\Auth\MustVerifyEmail;
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

<<<<<<< HEAD
    public const ROL_ADMIN = 'administrador';
    public const ROL_DOCENTE = 'docente';
    public const ROL_ESTUDIANTE = 'estudiante';
    public const ROLES = [self::ROL_ADMIN, self::ROL_DOCENTE, self::ROL_ESTUDIANTE];

    public const NIVEL_3 = '3medio';
    public const NIVEL_4 = '4medio';
    public const NIVELES = [self::NIVEL_3, self::NIVEL_4];

=======
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
    protected $fillable = [
        'name',
        'email',
        'password',
<<<<<<< HEAD
        'nivel_educativo',
        'curso',
        'activo',
    ];

=======
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
    protected $hidden = [
        'password',
        'remember_token',
    ];

<<<<<<< HEAD
=======
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
<<<<<<< HEAD
            'activo' => 'boolean',
        ];
    }

    public function esEstudiante(): bool
    {
        return $this->hasRole(self::ROL_ESTUDIANTE);
    }
}
=======
        ];
    }
}
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
