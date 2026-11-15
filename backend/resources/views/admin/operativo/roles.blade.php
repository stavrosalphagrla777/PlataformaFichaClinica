@extends('admin.operativo.layout')
@section('title','Roles y niveles')
@section('content')
<div class="heading"><div><h1>Asignación de roles y niveles</h1><p class="subtitle">Cambia el perfil y el curso de uno o varios usuarios.</p></div><a class="primary-btn" href="{{ route('admin.usuarios') }}">← Volver a usuarios</a></div>
<div class="roles-mode-bar"><button type="button" class="roles-mode active" id="modeIndividual" aria-pressed="true">Asignación individual</button><button type="button" class="roles-mode" id="modeMultiple" aria-pressed="false">Asignación múltiple</button></div>
<div class="create-layout">
<section class="panel">
<form id="rolesForm" method="POST" action="{{ route('admin.roles-niveles.lote') }}">
@csrf
<div id="individualArea">
    <h2>Seleccionar usuario</h2>
    <p class="form-description">Elige la cuenta que deseas actualizar.</p>
    <div class="form-field"><label for="usuario">Usuario</label><select id="usuario"><option value="">Selecciona un usuario...</option>
    @foreach($users as $usuario)<option value="{{ $usuario->id }}" data-rol="{{ $usuario->getRoleNames()->first() ?? '' }}" data-nivel="{{ $usuario->nivel_educativo ?? '' }}" data-curso="{{ $usuario->curso ?? '' }}">{{ $usuario->name }} · {{ $usuario->email }}</option>@endforeach
    </select></div>
</div>
<div id="multipleArea" hidden>
    <h2>Seleccionar usuarios</h2>
    <p class="form-description">Selecciona cuentas para aplicar una configuración común. Solo se muestran las cuentas de esta página.</p>
    <div class="roles-selection-tools"><input id="searchUsers" type="search" placeholder="Buscar usuario..." aria-label="Buscar usuarios"><button type="button" id="clearSelection" class="admin-clean-button">Deseleccionar</button></div>
    <div class="roles-filter-grid">
        <div><label for="filterRole">Rol</label><select id="filterRole"><option value="">Todos los roles</option><option value="estudiante">Estudiante</option><option value="docente">Docente</option><option value="administrador">Administrador</option></select></div>
        <div><label for="filterLevel">Nivel</label><select id="filterLevel"><option value="">Todos los niveles</option><option value="3_medio">3.º medio</option><option value="4_medio">4.º medio</option></select></div>
        <div><label for="filterLetter">Letra del curso</label><select id="filterLetter"><option value="">Todas las letras</option>@foreach(['A','B','C','D'] as $letra)<option value="{{ $letra }}">{{ $letra }}</option>@endforeach</select></div>
    </div>
    <button id="clearUserFilters" type="button" class="admin-clean-button">Limpiar filtros</button>
    <div class="roles-users-list"><label class="roles-user-row roles-select-all"><input type="checkbox" id="selectRolesAll"><strong>Seleccionar todos los visibles</strong></label>
      <div id="usersCheckboxes">
        @foreach($users as $usuario)
        <label class="roles-user-row" data-search="{{ mb_strtolower($usuario->name . ' ' . $usuario->email . ' ' . ($usuario->username ?? '')) }}" data-rol="{{ $usuario->getRoleNames()->first() ?? '' }}" data-nivel="{{ $usuario->nivel_educativo ?? '' }}" data-curso="{{ $usuario->curso ?? '' }}">
            <input class="role-user-check" type="checkbox" value="{{ $usuario->id }}"><span><strong>{{ $usuario->name }}</strong><small>{{ $usuario->email }} · {{ ucfirst($usuario->getRoleNames()->first() ?? 'sin rol') }} · {{ $usuario->curso ?: 'Sin curso' }}</small></span>
        </label>
        @endforeach
      </div></div>
    <p id="visibleUserCount" class="roles-filter-count"></p><p id="selectedCount" class="roles-selection-count">0 usuarios seleccionados</p>
</div>
<hr class="form-divider">
<h2>Configuración de acceso</h2>
<div class="form-grid">
    <div class="form-field"><label for="rol">Rol asignado</label><select id="rol" name="rol" required><option value="estudiante">Estudiante</option><option value="docente">Docente</option><option value="administrador">Administrador</option></select></div>
    <div class="form-field" id="levelField"><label for="nivel_educativo">Nivel educativo</label><select id="nivel_educativo" name="nivel_educativo"><option value="">Seleccionar nivel</option><option value="3_medio">3.º medio</option><option value="4_medio">4.º medio</option></select></div>
    <div class="form-field" id="courseField"><label for="curso">Curso</label><select id="curso" name="curso"><option value="">Seleccionar curso</option></select></div>
</div>
<p class="security-note">Los cambios se guardan en MySQL y modifican los permisos reales. No puedes quitarte tu propio acceso de administrador.</p>
<div id="roleIds"></div>
<div class="form-actions"><button type="button" class="admin-clean-button" id="resetChanges">Restablecer</button><button type="submit" class="primary-btn" id="saveChanges">Guardar asignación</button></div>
<p role="status" id="rolesNotice" class="form-message"></p>
</form>
</section>
<aside class="panel account-summary"><h2>Resumen de la asignación</h2><p class="form-description">Comprueba la configuración antes de confirmar.</p><div class="summary-highlight"><span>Usuarios seleccionados</span><strong id="summaryName">Ningún usuario seleccionado</strong></div><div class="summary-row"><span>Rol</span><strong id="summaryRole">—</strong></div><div class="summary-row"><span>Nivel educativo</span><strong id="summaryLevel">—</strong></div><div class="summary-row"><span>Curso</span><strong id="summaryCourse">—</strong></div></aside>
</div>
<p class="footnote">La selección múltiple se limita a {{ $users->count() }} cuentas visibles en esta página. {{ $users->total() }} cuentas en total.</p><div class="pager">{{ $users->links() }}</div>
<div class="modal-overlay" id="rolesModal" hidden><div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="rolesModalTitle"><div class="modal-icon activate">✓</div><h2 id="rolesModalTitle">Confirmar asignación</h2><p id="rolesModalDescription"></p><div class="modal-actions"><button type="button" id="cancelRoles" class="modal-cancel">Cancelar</button><button type="button" id="confirmRoles" class="modal-confirm">Confirmar cambios</button></div></div></div>
<script src="{{ asset('js/admin-roles-reales.js') }}" defer></script>
@endsection
