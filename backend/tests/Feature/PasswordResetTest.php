<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleSeeder;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RoleSeeder::class);
    }

    public function test_solicita_enlace_de_recuperacion(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'd@liceo.cl']);
        $user->assignRole('docente');

        $this->postJson('/api/auth/forgot-password', ['email' => 'd@liceo.cl'])->assertOk();

        Notification::assertSentTo($user, ResetPassword::class);
    }

    public function test_correo_inexistente_responde_igual_y_no_envia_nada(): void
    {
        Notification::fake();

        $this->postJson('/api/auth/forgot-password', ['email' => 'nadie@liceo.cl'])->assertOk();

        Notification::assertNothingSent();
    }

    public function test_restablece_la_contrasena_con_token_valido(): void
    {
        $user = User::factory()->create(['email' => 'd@liceo.cl']);
        $user->assignRole('docente');
        $user->createToken('t');
        $token = Password::createToken($user);

        $this->postJson('/api/auth/reset-password', [
            'token' => $token,
            'email' => 'd@liceo.cl',
            'password' => 'NuevaClave123',
            'password_confirmation' => 'NuevaClave123',
        ])->assertOk();

        $this->assertTrue(Hash::check('NuevaClave123', $user->fresh()->password));
        $this->assertDatabaseCount('personal_access_tokens', 0);

        $this->postJson('/api/auth/login', ['email' => 'd@liceo.cl', 'password' => 'NuevaClave123'])->assertOk();
    }

    public function test_token_invalido_es_rechazado(): void
    {
        User::factory()->create(['email' => 'd@liceo.cl']);

        $this->postJson('/api/auth/reset-password', [
            'token' => 'token-falso',
            'email' => 'd@liceo.cl',
            'password' => 'NuevaClave123',
            'password_confirmation' => 'NuevaClave123',
        ])->assertStatus(422)->assertJsonValidationErrors('email');
    }

    public function test_contrasena_debil_o_sin_confirmar_es_rechazada(): void
    {
        $user = User::factory()->create(['email' => 'd@liceo.cl']);
        $token = Password::createToken($user);

        $this->postJson('/api/auth/reset-password', [
            'token' => $token, 'email' => 'd@liceo.cl',
            'password' => '123', 'password_confirmation' => '123',
        ])->assertStatus(422)->assertJsonValidationErrors('password');

        $this->postJson('/api/auth/reset-password', [
            'token' => $token, 'email' => 'd@liceo.cl',
            'password' => 'NuevaClave123', 'password_confirmation' => 'otra',
        ])->assertStatus(422)->assertJsonValidationErrors('password');
    }
}

