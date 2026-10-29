<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    public const ROL_ADMIN = 'administrador';
    public const ROL_DOCENTE = 'docente';
    public const ROL_ESTUDIANTE = 'estudiante';
    public const ROLES = [self::ROL_ADMIN, self::ROL_DOCENTE, self::ROL_ESTUDIANTE];

    public const NIVEL_3 = '3medio';
    public const NIVEL_4 = '4medio';
    public const NIVELES = [self::NIVEL_3, self::NIVEL_4];

    protected $fillable = [
        'name',
        'email',
        'password',
        'nivel_educativo',
        'curso',
        'activo',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'activo' => 'boolean',
        ];
    }

    public function esEstudiante(): bool
    {
        return $this->hasRole(self::ROL_ESTUDIANTE);
    }
}
