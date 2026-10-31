<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Usuarios de prueba para desarrollo (NO usar en producción).
 * Contraseña de todos: password123
 */
class DemoUserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            ['admin@liceo.cl', 'Administrador Demo', User::ROL_ADMIN, null, null],
            ['docente@liceo.cl', 'Docente Demo', User::ROL_DOCENTE, null, null],
            ['estudiante@liceo.cl', 'Estudiante 4° Demo', User::ROL_ESTUDIANTE, User::NIVEL_4, '4° A'],
            ['estudiante3@liceo.cl', 'Estudiante 3° Demo', User::ROL_ESTUDIANTE, User::NIVEL_3, '3° A'],
        ];

        foreach ($usuarios as [$email, $nombre, $rol, $nivel, $curso]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $nombre,
                    'password' => 'password123',
                    'nivel_educativo' => $nivel,
                    'curso' => $curso,
                ]
            );
            $user->syncRoles([$rol]);
        }
    }
}
