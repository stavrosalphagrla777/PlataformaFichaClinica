<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ClinicalCase;
use App\Models\CaseAssignment;
use Illuminate\Database\Seeder;

/** Ejecutar explícitamente SOLO en local/testing. Contraseñas inseguras pedidas para QA. */
class CredencialesPruebaSeeder extends Seeder
{
    public function run(): void
    {
        if (!app()->environment(['local','testing'])) {
            throw new \RuntimeException('Cuentas admin/admin y similares prohibidas en producción.');
        }
        $this->call(RoleSeeder::class);
        $usuarios = [
            ['username' => 'admin', 'email' => 'admin@ficha.test', 'name' => 'Rosa Vega (Prueba)', 'rol' => 'administrador', 'nivel' => null, 'curso' => null],
            ['username' => 'docente', 'email' => 'docente@ficha.test', 'name' => 'Daniela Fuentes (Prueba)', 'rol' => 'docente', 'nivel' => null, 'curso' => null],
            ['username' => 'estudiante', 'email' => 'estudiante@ficha.test', 'name' => 'Vicente Paredes (Prueba)', 'rol' => 'estudiante', 'nivel' => '4_medio', 'curso' => '4° A'],
            ['username' => 'estudiante3', 'email' => 'estudiante3@ficha.test', 'name' => 'Antonia Rojas (Prueba)', 'rol' => 'estudiante', 'nivel' => '3_medio', 'curso' => '3° A'],
        ];
        foreach ($usuarios as $info) {
            $user = User::firstOrCreate(
                ['username' => $info['username']],
                [
                    'email' => $info['email'],
                    'name' => $info['name'],
                    'password' => $info['username'], // cast hashed del modelo; NO en producción
                    'activo' => true,
                    'nivel_educativo' => $info['nivel'],
                    'curso' => $info['curso'],
                ]
            );
            if ($user->email !== $info['email']) {
                throw new \RuntimeException('El username '.$info['username'].' ya existe para otra cuenta; no se sobrescribe.');
            }
            $user->syncRoles([$info['rol']]);
        }
        // Caso académico ficticio para probar docente/estudiante sin datos clínicos reales.
        $docente = User::where('username', 'docente')->firstOrFail();
        $caso = ClinicalCase::firstOrCreate(['codigo' => 'CAS-QA-4A'], [
            'docente_id' => $docente->id,
            'titulo' => 'Simulación de atención inicial',
            'descripcion' => 'Caso ficticio de evaluación de urgencias para pruebas del sistema.',
            'nivel' => '4.º medio', 'tipo' => 'Tarea', 'estado' => 'Publicado',
            'servicio' => 'Servicio de urgencias', 'duracion' => 120,
            'paciente' => 'Paciente Simulado',
        ]);
        CaseAssignment::firstOrCreate(
            ['clinical_case_id' => $caso->id, 'docente_id' => $docente->id, 'curso' => '4.º Medio A'],
            ['nivel' => '4.º medio', 'inicio' => now()->subDay(),
             'termino' => now()->addDays(30), 'estado' => 'Activa',
             'observaciones' => 'Ejercicio ficticio para control de calidad.']
        );

        // Caso ficticio CESFAM para pruebas persistentes de 3.º medio.
        $caseThird = ClinicalCase::firstOrCreate(['codigo' => 'CAS-QA-3A'], [
           'docente_id' => $docente->id,'titulo'=>'Simulación de atención primaria',
           'descripcion'=>'Caso ficticio CESFAM para pruebas de 3.º medio.',
           'nivel'=>'3.º medio','tipo'=>'Tarea','estado'=>'Publicado',
           'servicio'=>'Atención primaria (CESFAM)','duracion'=>120,'paciente'=>'Paciente CESFAM Simulado',
        ]);
        CaseAssignment::firstOrCreate(
           ['clinical_case_id'=>$caseThird->id,'docente_id'=>$docente->id,'curso'=>'3.º Medio A'],
           ['nivel'=>'3.º medio','inicio'=>now()->subDay(),'termino'=>now()->addDays(30),
            'estado'=>'Activa','observaciones'=>'Caso ficticio CESFAM para QA.']
        );
    }
}
