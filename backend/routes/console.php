<?php


use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');



/** Crea el primer administrador real SIN contraseña expuesta por CLI ni seeders QA. */
Artisan::command('ficha:admin-inicial', function () {
    if (\App\Models\User::role('administrador')->exists()) {
        $this->error('Ya existe un administrador. No se creó otra cuenta.');
        return 1;
    }
    $nombre = trim((string)$this->ask('Nombre completo'));
    $usuario = trim((string)$this->ask('Nombre de usuario'));
    $correo = trim((string)$this->ask('Correo electrónico real'));
    $password = (string)$this->secret('Contraseña segura (mínimo 12 caracteres)');
    $confirmacion = (string)$this->secret('Repite la contraseña');
    $data=compact('nombre','usuario','correo','password','confirmacion');
    $validator=\Illuminate\Support\Facades\Validator::make($data, [
      'nombre'=>'required|string|max:150',
      'usuario'=>'required|alpha_dash|max:60|unique:users,username',
      'correo'=>'required|email|max:255|unique:users,email',
      'password'=>'required|string|min:12',
      'confirmacion'=>'required|same:password',
    ]);
    if($validator->fails()){
       foreach($validator->errors()->all() as $error)$this->error($error);
       return 1;
    }
    \Illuminate\Support\Facades\DB::transaction(function()use($nombre,$usuario,$correo,$password){
        \Spatie\Permission\Models\Role::firstOrCreate(['name'=>'administrador','guard_name'=>'web']);
        $user=\App\Models\User::create(['name'=>$nombre,'username'=>$usuario,'email'=>$correo,
           'password'=>$password,'activo'=>true]);
        $user->assignRole('administrador');
    });
    $this->info('Cuenta inicial creada. Cambia la contraseña periódicamente y activa protección adicional en la infraestructura.');
    return 0;
})->purpose('Crear de forma interactiva y segura el primer administrador real');
