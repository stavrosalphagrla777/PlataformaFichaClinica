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
        $estudiante = User::factory()->create();
        $estudiante->assignRole('estudiante');

        $response = $this->actingAs($estudiante, 'sanctum')->postJson('/api/fichas', [
            'paciente_id' => 1,
            'motivo_consulta' => 'Dolor abdominal agudo',
        ]);

        $response->assertStatus(201)
                 ->assertJsonPath('data.motivo_consulta', 'Dolor abdominal agudo')
                 ->assertJsonPath('data.estado', 'en_espera');

        $this->assertDatabaseHas('fichas_clinicas', [
            'user_id' => $estudiante->id,
            'paciente_id' => 1,
            'motivo_consulta' => 'Dolor abdominal agudo',
        ]);
    }

    public function test_usuario_no_autenticado_no_puede_crear_ficha(): void
    {
        $response = $this->postJson('/api/fichas', [
            'paciente_id' => 1,
            'motivo_consulta' => 'Dolor abdominal agudo',
        ]);

        $response->assertStatus(401);
    }
}
