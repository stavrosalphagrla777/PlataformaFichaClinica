@extends('admin.operativo.layout')
@section('title','Gestión de usuarios')
@section('content')
<div class="heading"><div><h1>Usuarios de la plataforma</h1><p class="subtitle">Consulta, busca y administra una o varias cuentas educativas.</p></div><a class="primary-btn" href="{{ route('admin.usuarios.crear') }}">+ Crear usuario</a></div>
<section class="stats" aria-label="Resumen de usuarios">
    <div class="stat"><span class="stat-label">Total de usuarios</span><strong class="stat-value">{{ $totales['usuarios'] }}</strong></div>
    <div class="stat"><span class="stat-label">Estudiantes</span><strong class="stat-value">{{ $totales['estudiantes'] }}</strong></div>
    <div class="stat"><span class="stat-label">Docentes</span><strong class="stat-value">{{ $totales['docentes'] }}</strong></div>
    <div class="stat"><span class="stat-label">Administradores</span><strong class="stat-value">{{ $totales['administradores'] }}</strong></div>
</section>
<section class="panel">
    <h2>Listado de usuarios</h2>
    <form method="GET" class="filters" aria-label="Filtrar usuarios">
        <input name="buscar" type="search" placeholder="Buscar por nombre, usuario o correo" value="{{ request('buscar') }}" aria-label="Buscar usuarios">
        <select name="rol" aria-label="Filtrar por rol"><option value="">Todos los roles</option>@foreach(['estudiante'=>'Estudiante','docente'=>'Docente','administrador'=>'Administrador'] as $v=>$label)<option value="{{ $v }}" @selected(request('rol')===$v)>{{ $label }}</option>@endforeach</select>
        <select name="nivel" aria-label="Filtrar por nivel"><option value="">Todos los niveles</option><option value="3_medio" @selected(request('nivel')==='3_medio')>3.º medio</option><option value="4_medio" @selected(request('nivel')==='4_medio')>4.º medio</option></select>
        <select name="curso" aria-label="Filtrar por curso">
            <option value="">Todos los cursos</option>
            @foreach(($cursos ?? []) as $curso)
                <option value="{{ $curso }}" @selected(request('curso') === $curso)>{{ $curso }}</option>
            @endforeach
        </select>
        <select name="estado" aria-label="Filtrar por estado"><option value="">Todos los estados</option><option value="activo" @selected(request('estado')==='activo')>Activos</option><option value="inactivo" @selected(request('estado')==='inactivo')>Inactivos</option></select>
        <div class="filter-actions"><button type="submit" class="admin-clean-button">Buscar</button><a class="admin-clean-button" href="{{ route('admin.usuarios') }}">Limpiar filtros</a></div>
    </form>
    <form id="bulkUsersForm" method="POST" action="{{ route('admin.usuarios.lote') }}">
        @csrf
        <input type="hidden" name="activo" id="bulkEstado" value="1">
        <div class="bulk-toolbar"><strong id="selectionCount">0 usuarios seleccionados</strong><div class="bulk-buttons">
            <button type="button" id="bulkActivate" disabled>Activar seleccionados</button>
            <button type="button" id="bulkDeactivate" disabled>Desactivar seleccionados</button>
            <button type="button" id="clearSelection" disabled>Deseleccionar</button>
        </div></div>
        <div class="table-wrap"><table><thead><tr>
            <th class="check-col"><input type="checkbox" id="selectAll" aria-label="Seleccionar todos los usuarios visibles"></th>
            <th>Nombre / correo</th><th>Usuario</th><th>Rol</th><th>Nivel</th><th>Curso</th><th>Estado</th><th>Acciones</th>
        </tr></thead><tbody id="userTable">
        @forelse($users as $usuario)
            <tr>
                <td class="check-col"><input class="user-check" type="checkbox" name="ids[]" value="{{ $usuario->id }}" aria-label="Seleccionar {{ $usuario->name }}"></td>
                <td><strong>{{ $usuario->name }}</strong><span class="email">{{ $usuario->email }}</span></td>
                <td>{{ $usuario->username ?? '—' }}</td>
                <td>{{ ucfirst($usuario->getRoleNames()->first() ?? 'sin rol') }}</td>
                <td>{{ $usuario->nivel_educativo==='3_medio' ? '3.º medio' : ($usuario->nivel_educativo==='4_medio' ? '4.º medio' : '—') }}</td>
                <td>{{ $usuario->curso ?: '—' }}</td>
                <td><span class="status {{ $usuario->activo ? 'active-status' : 'inactive-status' }}">{{ $usuario->activo ? 'Activo' : 'Inactivo' }}</span></td>
                <td class="actions"><a class="action" href="{{ route('admin.usuarios.editar',$usuario) }}">Editar</a>
                    @unless(auth()->user()->is($usuario))
                        <button class="action {{ $usuario->activo ? 'danger' : '' }}" type="button" data-individual-form="status-{{ $usuario->id }}" data-target-name="{{ $usuario->name }}" data-next-status="{{ $usuario->activo ? 'desactivar' : 'activar' }}">{{ $usuario->activo ? 'Desactivar' : 'Reactivar' }}</button>
                    @endunless
                </td>
            </tr>
        @empty <tr><td colspan="8" class="empty">No se encontraron cuentas para los filtros seleccionados.</td></tr>
        @endforelse
        </tbody></table></div>
    </form>
    @foreach($users as $usuario)
        @unless(auth()->user()->is($usuario))<form id="status-{{ $usuario->id }}" method="POST" action="{{ route('admin.usuarios.estado',$usuario) }}" hidden>@csrf @method('PATCH')</form>@endunless
    @endforeach
    <p class="footnote">Mostrando {{ $users->count() }} de {{ $users->total() }} usuario(s). La selección múltiple se aplica únicamente a esta página.</p>
    <div class="pager">{{ $users->links() }}</div>
</section>
<div class="modal-overlay" id="statusModal" hidden>
    <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="modalTitle" aria-describedby="modalDescription">
        <div class="modal-icon" id="modalIcon">!</div><h2 id="modalTitle">Confirmar cambios</h2><p id="modalDescription"></p>
        <div class="modal-actions"><button class="modal-cancel" type="button" id="cancelStatus">Cancelar</button><button class="modal-confirm" type="button" id="confirmStatus">Confirmar</button></div>
    </div>
</div>
<script src="{{ asset('js/admin-usuarios-reales.js') }}" defer></script>
@endsection
