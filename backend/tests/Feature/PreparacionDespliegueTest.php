<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\CredencialesPruebaSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Tests\TestCase;

class PreparacionDespliegueTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeder_qa_usuarios_y_contrasenas(): void
    {
        $this->seed(CredencialesPruebaSeeder::class);
        foreach (['admin','docente','estudiante'] as $login) {
            $user = User::where('username', $login)->firstOrFail();
            $this->assertTrue(Hash::check($login, $user->password));
            $this->assertTrue($user->hasRole($login === 'admin' ? 'administrador' : $login));
        }
        $this->assertDatabaseHas('clinical_cases', ['codigo' => 'CAS-QA-4A']);
    }

    public function test_login_web_por_usuario_redirecciona_por_rol(): void
    {
        $this->seed(CredencialesPruebaSeeder::class);
        $this->post('/login', ['login' => 'admin', 'password' => 'admin'])->assertRedirect(route('admin.dashboard'));
        $this->post('/logout');
        $this->post('/login', ['login' => 'docente', 'password' => 'docente'])->assertRedirect(route('preview.docente.dashboard'));
        $this->post('/logout');
        $this->post('/login', ['login' => 'estudiante', 'password' => 'estudiante'])->assertRedirect(route('preview.estudiante.casos'));
    }

    public function test_recuperacion_web_envia_notificacion_y_redirige(): void
    {
        $this->seed(CredencialesPruebaSeeder::class);
        Notification::fake();
        $this->post('/recuperar', ['email' => 'docente@ficha.test'])->assertRedirect(route('preview.confirmacion'));
        Notification::assertSentTo(User::where('username','docente')->firstOrFail(), ResetPassword::class);
    }
}
