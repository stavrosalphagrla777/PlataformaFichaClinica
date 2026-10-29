<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    private function crearUsuario(string $rol, array $extra = []): User
    {
        $user = User::factory()->create($extra);
        $user->assignRole($rol);

        return $user;
    }

    public function test_estudiante_3_medio_inicia_sesion(): void
    {
        $this->crearUsuario('estudiante', [
            'email' => 'e3@liceo.cl', 'nivel_educativo' => '3medio', 'curso' => '3° A',
        ]);

        $this->postJson('/api/auth/login', ['email' => 'e3@liceo.cl', 'password' => 'password'])
            ->assertOk()
            ->assertJsonStructure(['access_token', 'token_type', 'user' => ['id', 'rol', 'nivel_educativo', 'curso']])
            ->assertJsonPath('user.rol', 'estudiante')
            ->assertJsonPath('user.nivel_educativo', '3medio');
    }

    public function test_estudiante_4_medio_inicia_sesion(): void
    {
        $this->crearUsuario('estudiante', [
            'email' => 'e4@liceo.cl', 'nivel_educativo' => '4medio', 'curso' => '4° A',
        ]);

        $this->postJson('/api/auth/login', ['email' => 'e4@liceo.cl', 'password' => 'password'])
            ->assertOk()
            ->assertJsonPath('user.nivel_educativo', '4medio');
    }

    public function test_docente_y_administrador_inician_sesion(): void
    {
        $this->crearUsuario('docente', ['email' => 'd@liceo.cl']);
        $this->crearUsuario('administrador', ['email' => 'a@liceo.cl']);

        $this->postJson('/api/auth/login', ['email' => 'd@liceo.cl', 'password' => 'password'])
            ->assertOk()->assertJsonPath('user.rol', 'docente');
        $this->postJson('/api/auth/login', ['email' => 'a@liceo.cl', 'password' => 'password'])
            ->assertOk()->assertJsonPath('user.rol', 'administrador');
    }

    public function test_credenciales_invalidas_son_rechazadas(): void
    {
        $this->crearUsuario('docente', ['email' => 'd@liceo.cl']);

        $this->postJson('/api/auth/login', ['email' => 'd@liceo.cl', 'password' => 'incorrecta'])
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');

        $this->postJson('/api/auth/login', ['email' => 'noexiste@liceo.cl', 'password' => 'password'])
            ->assertStatus(422);
    }

    public function test_usuario_desactivado_no_puede_iniciar_sesion(): void
    {
        $this->crearUsuario('docente', ['email' => 'd@liceo.cl', 'activo' => false]);

        $this->postJson('/api/auth/login', ['email' => 'd@liceo.cl', 'password' => 'password'])
            ->assertStatus(403);
    }

    public function test_usuario_sin_rol_no_puede_iniciar_sesion(): void
    {
        User::factory()->create(['email' => 'sinrol@liceo.cl']);

        $this->postJson('/api/auth/login', ['email' => 'sinrol@liceo.cl', 'password' => 'password'])
            ->assertStatus(403);
    }

    public function test_me_devuelve_el_usuario_autenticado(): void
    {
        $user = $this->crearUsuario('docente');
        Sanctum::actingAs($user);

        $this->getJson('/api/auth/me')
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonPath('data.rol', 'docente');
    }

    public function test_logout_elimina_el_token(): void
    {
        $this->crearUsuario('docente', ['email' => 'd@liceo.cl']);

        $token = $this->postJson('/api/auth/login', ['email' => 'd@liceo.cl', 'password' => 'password'])
            ->json('access_token');

        $this->assertDatabaseCount('personal_access_tokens', 1);

        $this->withToken($token)->postJson('/api/auth/logout')->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }

    public function test_rutas_protegidas_exigen_token(): void
    {
        $this->getJson('/api/admin/users')->assertStatus(401);
        $this->getJson('/api/auth/me')->assertStatus(401);
        $this->postJson('/api/fichas/1/triage', [])->assertStatus(401);
    }

    public function test_estudiante_no_puede_acceder_al_modulo_admin(): void
    {
        Sanctum::actingAs($this->crearUsuario('estudiante', ['nivel_educativo' => '3medio', 'curso' => '3° A']));

        $this->getJson('/api/admin/users')->assertStatus(403);
    }

    public function test_docente_no_puede_acceder_al_modulo_admin(): void
    {
        Sanctum::actingAs($this->crearUsuario('docente'));

        $this->getJson('/api/admin/users')->assertStatus(403);
    }
}
