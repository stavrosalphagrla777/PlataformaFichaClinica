@extends('admin.operativo.layout')
@section('title',$user ? 'Editar usuario' : 'Crear usuario')
@section('content')<h1>{{ $user ? 'Editar usuario' : 'Crear usuario' }}</h1><div class="panel"><p class="muted">Los datos se guardan en MySQL. Para crear una cuenta nueva se requiere contraseña. Al editar, déjala vacía para conservar la actual.</p>
<form method="POST" action="{{ $user ? route('admin.usuarios.update',$user) : route('admin.usuarios.store') }}">@csrf @if($user)@method('PUT')@endif
<div class="form-grid"><div><label for="name">Nombre completo</label><input id="name" name="name" required maxlength="150" value="{{ old('name',$user?->name) }}"></div>
<div><label for="username">Usuario</label><input id="username" name="username" required maxlength="60" pattern="[A-Za-z0-9_-]+" value="{{ old('username',$user?->username) }}"></div>
<div><label for="email">Correo electrónico</label><input id="email" type="email" name="email" required value="{{ old('email',$user?->email) }}"></div>
<div><label for="password">{{ $user ? 'Cambiar contraseña (opcional)' : 'Contraseña inicial (mín. 8 caracteres)' }}</label><input id="password" type="password" name="password" autocomplete="new-password" @required(!$user) minlength="8"></div>
<div><label for="rol">Rol</label><select name="rol" id="rol" required>@foreach(['administrador','docente','estudiante'] as $rol)<option value="{{ $rol }}" @selected(old('rol',$user?->getRoleNames()->first())===$rol)>{{ ucfirst($rol) }}</option>@endforeach</select></div>
<div><label for="nivel_educativo">Nivel del estudiante</label><select id="nivel_educativo" name="nivel_educativo"><option value="">No aplica</option><option value="3_medio" @selected(old('nivel_educativo',$user?->nivel_educativo)==='3_medio')>3.º medio</option><option value="4_medio" @selected(old('nivel_educativo',$user?->nivel_educativo)==='4_medio')>4.º medio</option></select></div>
<div><label for="curso">Curso del estudiante</label><select name="curso" id="curso" data-selected="{{ old('curso',$user?->curso) }}"><option value="">Selecciona primero un nivel</option></select><small>Solo se muestran los cursos confirmados por el liceo, incluidas las jornadas vespertinas.</small></div>
<div><label for="activo">Estado de cuenta</label><select name="activo" id="activo"><option value="1" @selected(old('activo',$user?->activo ?? true)==1)>Activa</option><option value="0" @selected(old('activo',$user?->activo ?? true)==0)>Inactiva</option></select></div></div>
<div class="actions"><button type="submit">{{ $user ? 'Guardar cambios' : 'Crear cuenta' }}</button><a href="{{ route('admin.usuarios') }}" class="btn secondary">Volver</a></div>
</form></div><script>
(()=>{
 const role=document.getElementById('rol'), level=document.getElementById('nivel_educativo'), course=document.getElementById('curso');
 const options={'3_medio':['3.º Medio D','3.º Medio F','V3ro'],'4_medio':['4.º Medio C','4.º Medio G','V4to']};
 function syncCourse(preferred=''){const available=options[level.value]||[];course.replaceChildren(new Option(available.length?'Seleccionar curso':'Selecciona primero un nivel',''));available.forEach(value=>course.add(new Option(value,value)));if(available.includes(preferred))course.value=preferred;}
 function adjust(){const student=role.value==='estudiante';level.required=course.required=student;level.disabled=course.disabled=!student;}
 level.addEventListener('change',()=>syncCourse());role.addEventListener('change',adjust);
 syncCourse(course.dataset.selected||'');adjust();
})();
</script>
@if(!$user)
<section class="panel" style="margin-top:22px">
    <h2>Importación masiva de usuarios</h2>
    <p class="muted">Puedes importar hasta 100 cuentas por archivo CSV. Todos los registros se validan antes de guardarse; si una fila contiene errores, no se importa ninguna. No sobrescribe usuarios existentes.</p>
    <form method="POST" action="{{ route('admin.usuarios.importar') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-grid"><div><label for="archivo">Archivo CSV (máximo 256 KB)</label><input type="file" name="archivo" id="archivo" accept=".csv,text/csv" required></div>
        <div><label>Formato</label><p class="muted">Cabeceras: nombre, usuario, correo, contrasena, rol, nivel, curso, activo. Separador punto y coma o coma.</p></div></div>
        <div class="actions"><button type="submit">Importar cuentas</button><button type="button" id="downloadTemplate" class="secondary">Descargar plantilla CSV</button></div>
    </form>
    <p class="muted">Para estudiantes, usa nivel <code>3_medio</code> o <code>4_medio</code> y un curso. El valor de activo es 1 o 0. En producción, las contraseñas deben tener al menos 12 caracteres. Protege y elimina el CSV después de importarlo.</p>
</section>
<script>
document.getElementById('downloadTemplate')?.addEventListener('click', () => {
    const csv = 'nombre;usuario;correo;contrasena;rol;nivel;curso;activo\n';
    const blob = new Blob(['\uFEFF' + csv], {type:'text/csv;charset=utf-8'});
    const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download='plantilla-usuarios.csv';a.click();URL.revokeObjectURL(url);
});
</script>
@endif
@endsection
