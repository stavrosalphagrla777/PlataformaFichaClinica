<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);

        $this->admin = User::factory()->create(['email' => 'admin@liceo.cl']);
        $this->admin->assignRole('administrador');
        Sanctum::actingAs($this->admin);
    }

    private function nuevoUsuario(string $rol, array $extra = []): User
    {
        $user = User::factory()->create($extra);
        $user->assignRole($rol);

        return $user;
    }

    public function test_crea_estudiante_con_rol_nivel_y_curso(): void
    {
        $this->postJson('/api/admin/users', [
            'name' => 'Ana Pérez',
            'email' => 'ana@liceo.cl',
            'password' => 'Clave1234',
            'rol' => 'estudiante',
            'nivel_educativo' => '4medio',
            'curso' => '4° B',
        ])->assertCreated()
            ->assertJsonPath('data.rol', 'estudiante')
            ->assertJsonPath('data.nivel_educativo', '4medio')
            ->assertJsonPath('data.activo', true);

        $this->assertDatabaseHas('users', ['email' => 'ana@liceo.cl', 'curso' => '4° B']);
    }

    public function test_estudiante_sin_nivel_es_rechazado(): void
    {
        $this->postJson('/api/admin/users', [
            'name' => 'Ana', 'email' => 'ana@liceo.cl', 'password' => 'Clave1234', 'rol' => 'estudiante',
        ])->assertStatus(422)->assertJsonValidationErrors('nivel_educativo');

        $this->assertDatabaseMissing('users', ['email' => 'ana@liceo.cl']);
    }

    public function test_docente_se_crea_sin_nivel_ni_curso(): void
    {
        $this->postJson('/api/admin/users', [
            'name' => 'Prof', 'email' => 'prof@liceo.cl', 'password' => 'Clave1234',
            'rol' => 'docente', 'nivel_educativo' => '3medio', 'curso' => '3° A',
        ])->assertCreated()
            ->assertJsonPath('data.nivel_educativo', null)
            ->assertJsonPath('data.curso', null);
    }

    public function test_correo_duplicado_es_rechazado(): void
    {
        $this->postJson('/api/admin/users', [
            'name' => 'Otro', 'email' => 'admin@liceo.cl', 'password' => 'Clave1234', 'rol' => 'docente',
        ])->assertStatus(422)->assertJsonValidationErrors('email');
    }

    public function test_listado_con_busqueda_y_filtros(): void
    {
        $this->nuevoUsuario('estudiante', ['name' => 'Beatriz Soto', 'nivel_educativo' => '3medio', 'curso' => '3° A']);
        $this->nuevoUsuario('estudiante', ['name' => 'Carlos Rojas', 'nivel_educativo' => '4medio', 'curso' => '4° A']);
        $this->nuevoUsuario('docente', ['name' => 'Diana Docente']);

        $this->getJson('/api/admin/users')->assertOk()->assertJsonCount(4, 'data'); // incluye admin

        $this->getJson('/api/admin/users?rol=estudiante')->assertJsonCount(2, 'data');
        $this->getJson('/api/admin/users?rol=estudiante&nivel_educativo=3medio')
            ->assertJsonCount(1, 'data')->assertJsonPath('data.0.name', 'Beatriz Soto');
        $this->getJson('/api/admin/users?search=Carlos')->assertJsonCount(1, 'data');
    }

    public function test_filtro_por_estado(): void
    {
        $this->nuevoUsuario('docente', ['activo' => false]);

        $this->getJson('/api/admin/users?activo=0')->assertJsonCount(1, 'data');
        $this->getJson('/api/admin/users?activo=1')->assertJsonCount(1, 'data'); // solo el admin
    }

    public function test_modifica_usuario(): void
    {
        $user = $this->nuevoUsuario('docente', ['name' => 'Nombre Viejo']);

        $this->putJson("/api/admin/users/{$user->id}", ['name' => 'Nombre Nuevo'])
            ->assertOk()->assertJsonPath('data.name', 'Nombre Nuevo');
    }

    public function test_desactiva_usuario_cierra_sesiones_y_bloquea_login(): void
    {
        $user = $this->nuevoUsuario('docente', ['email' => 'd@liceo.cl']);
        $user->createToken('t');

        $this->patchJson("/api/admin/users/{$user->id}/desactivar")
            ->assertOk()->assertJsonPath('data.activo', false);

        $this->assertDatabaseCount('personal_access_tokens', 0);

        $this->postJson('/api/auth/login', ['email' => 'd@liceo.cl', 'password' => 'password'])
            ->assertStatus(403);
    }

    public function test_reactiva_usuario(): void
    {
        $user = $this->nuevoUsuario('docente', ['activo' => false]);

        $this->patchJson("/api/admin/users/{$user->id}/activar")
            ->assertOk()->assertJsonPath('data.activo', true);
    }

    public function test_admin_no_puede_desactivarse_a_si_mismo(): void
    {
        $this->patchJson("/api/admin/users/{$this->admin->id}/desactivar")->assertStatus(422);
        $this->assertTrue($this->admin->fresh()->activo);
    }

    public function test_asigna_rol_y_nivel(): void
    {
        $user = $this->nuevoUsuario('docente');

        // Pasar a estudiante sin nivel falla
        $this->patchJson("/api/admin/users/{$user->id}/rol-nivel", ['rol' => 'estudiante'])
            ->assertStatus(422);

        $this->patchJson("/api/admin/users/{$user->id}/rol-nivel", [
            'rol' => 'estudiante', 'nivel_educativo' => '3medio', 'curso' => '3° B',
        ])->assertOk()
            ->assertJsonPath('data.rol', 'estudiante')
            ->assertJsonPath('data.nivel_educativo', '3medio');

        $this->assertTrue($user->fresh()->hasRole('estudiante'));
        $this->assertFalse($user->fresh()->hasRole('docente'));
    }

    public function test_admin_no_puede_quitarse_su_propio_rol(): void
    {
        $this->patchJson("/api/admin/users/{$this->admin->id}/rol-nivel", ['rol' => 'docente'])
            ->assertStatus(422);

        $this->assertTrue($this->admin->fresh()->hasRole('administrador'));
    }
}
