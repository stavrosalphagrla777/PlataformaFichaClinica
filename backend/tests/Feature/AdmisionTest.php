<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdmisionTest extends TestCase
{
    use RefreshDatabase;

    public function test_estudiante_puede_crear_una_ficha_de_admision(): void
    {
        // Crear estudiante activo y asignarle el rol correspondiente.
        $estudiante = User::factory()->create();
        $estudiante->assignRole('estudiante');

        // Generar un token de Sanctum para autenticar la solicitud.
        $token = $estudiante->createToken('test-token')->plainTextToken;

        // Enviar solicitud autenticada para crear la ficha de admisión.
        $response = $this->withToken($token)->postJson('/api/fichas', [
            'paciente_id' => 1,
            'motivo_consulta' => 'Dolor abdominal agudo',
        ]);

        // Comprobar que la ficha se creó correctamente.
        $response->assertStatus(201)
            ->assertJsonPath('data.motivo_consulta', 'Dolor abdominal agudo')
            ->assertJsonPath('data.estado', 'en_espera');

        // Verificar que la ficha quedó registrada en la base de datos.
        $this->assertDatabaseHas('fichas_clinicas', [
            'user_id' => $estudiante->id,
            'paciente_id' => 1,
            'motivo_consulta' => 'Dolor abdominal agudo',
        ]);
    }

    public function test_usuario_no_autenticado_no_puede_crear_ficha(): void
    {
        // Intentar crear una ficha sin proporcionar un token.
        $response = $this->postJson('/api/fichas', [
            'paciente_id' => 1,
            'motivo_consulta' => 'Dolor abdominal agudo',
        ]);

        // Comprobar que el acceso sea rechazado.
        $response->assertStatus(401);
    }
}