<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ClinicalCase;
use App\Models\CaseAssignment;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * Cuentas ficticias para QA.
 * Ejecutar MANUALMENTE solo en local/testing.
 */
class CredencialesPruebaSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local', 'testing'])) {
            throw new \RuntimeException(
                'Las credenciales públicas de pruebas están prohibidas en producción.'
            );
        }

        $this->call(RoleSeeder::class);

        $accounts = [
            // Cuentas requeridas por las pruebas de GitHub.
            ['admin', 'admin', 'administrador', null, null, 'Administrador QA'],
            ['docente', 'docente', 'docente', null, null, 'Docente QA'],
            ['estudiante', 'estudiante', 'estudiante', '4_medio', '4.º Medio A', 'Estudiante QA'],

            // Cuentas locales para pruebas diurnas y vespertinas.
            ['estudiante3', 'password', 'estudiante', '3_medio', '3.º Medio D', 'Estudiante diurno 3.º QA'],
            ['estudiante4', 'password', 'estudiante', '4_medio', '4.º Medio C', 'Estudiante diurno 4.º QA'],
            ['estudiante3v', 'password', 'estudiante', '3_medio', 'V3ro', 'Estudiante vespertino 3.º QA'],
            ['estudiante4v', 'password', 'estudiante', '4_medio', 'V4to', 'Estudiante vespertino 4.º QA'],
        ];

        DB::transaction(function () use ($accounts) {
            foreach ($accounts as [$username, $password, $role, $level, $course, $name]) {
                $email = $username . '@ficha.test';

                $user = User::firstOrNew(['username' => $username]);

                // Nunca convertir una cuenta real en una cuenta de prueba.
                if (
                    $user->exists &&
                    !str_ends_with(mb_strtolower((string) $user->email), '@ficha.test')
                ) {
                    throw new \RuntimeException(
                        'Existe una cuenta real con el usuario ' . $username .
                        '. No se sobrescribe.'
                    );
                }

                $user->name = $name;
                $user->email = $email;
                $user->password = $password;
                $user->nivel_educativo = $level;
                $user->curso = $course;
                $user->activo = true;
                $user->save();

                $user->syncRoles([$role]);
            }

            $teacher = User::where('username', 'docente')->firstOrFail();

            $cases = [
                [
                    'CAS-QA-3D',
                    '3.º medio',
                    'Atención primaria (CESFAM)',
                    'Simulación CESFAM de control de salud',
                    ['3.º Medio D', 'V3ro'],
                ],
                [
                    'CAS-QA-4C',
                    '4.º medio',
                    'Servicio de urgencias',
                    'Simulación de atención inicial',
                    ['4.º Medio C', 'V4to'],
                ],
                [
                    'CAS-QA-4A',
                    '4.º medio',
                    'Servicio de urgencias',
                    'Simulación de atención inicial',
                    ['4.º Medio A'],
                ],
                [
                    'CAS-QA-3A',
                    '3.º medio',
                    'Atención primaria (CESFAM)',
                    'Simulación de atención primaria',
                    ['3.º Medio A'],
                ],
            ];

            foreach ($cases as [$code, $level, $service, $title, $courses]) {
                $case = ClinicalCase::firstOrCreate(
                    ['codigo' => $code],
                    [
                        'docente_id' => $teacher->id,
                        'titulo' => $title,
                        'descripcion' => 'Caso ficticio para pruebas funcionales sin datos clínicos reales.',
                        'nivel' => $level,
                        'tipo' => 'Tarea',
                        'estado' => 'Publicado',
                        'servicio' => $service,
                        'duracion' => 120,
                        'paciente' => 'Paciente Simulado QA',
                    ]
                );

                foreach ($courses as $course) {
                    CaseAssignment::firstOrCreate(
                        [
                            'clinical_case_id' => $case->id,
                            'docente_id' => $teacher->id,
                            'curso' => $course,
                        ],
                        [
                            'nivel' => $level,
                            'inicio' => now()->subDay(),
                            'termino' => now()->addDays(30),
                            'estado' => 'Activa',
                            'observaciones' => 'Prueba ficticia para control de calidad.',
                        ]
                    );
                }
            }
        });
    }
}
