@php
    $esAdmin = auth()->user()?->hasRole('administrador');
    $en = fn (string $ruta) => request()->routeIs($ruta) ? ' active' : '';
@endphp
<aside class="sidebar" id="sidebar" aria-label="Menú principal">
    <div class="brand"><div class="brand-logo"><img src="{{ asset('images/logo-tp.png') }}" alt="TP Enfermería"></div><span>Ficha Clínica TP</span></div>
    <div class="nav-title">Principal</div>
    <a class="nav-link{{ $esAdmin ? $en('admin.dashboard') : $en('preview.docente.dashboard') }}" href="{{ $esAdmin ? route('admin.dashboard') : route('preview.docente.dashboard') }}">Dashboard</a>
    @if ($esAdmin)
        <div class="nav-title">Administración</div>
        <a class="nav-link{{ $en('admin.usuarios') }}" href="{{ route('admin.usuarios') }}">Gestión de usuarios</a>
        <a class="nav-link{{ $en('admin.usuarios.crear') }}" href="{{ route('admin.usuarios.crear') }}">Crear usuario</a>
        <a class="nav-link{{ $en('admin.roles-niveles') }}" href="{{ route('admin.roles-niveles') }}">Asignar roles y niveles</a>
        <a class="nav-link{{ $en('admin.historial') }}" href="{{ route('admin.historial') }}">Historial de actividad</a>
    @endif
    <div class="nav-title">{{ $esAdmin ? 'Funciones docentes' : 'Acciones docentes' }}</div>
    @if ($esAdmin)
        <a class="nav-link{{ $en('preview.docente.dashboard') }}" href="{{ route('preview.docente.dashboard') }}">Panel docente</a>
    @endif
    <a class="nav-link{{ $en('preview.docente.casos') }}" href="{{ route('preview.docente.casos') }}">Casos clínicos</a>
    <a class="nav-link{{ $en('preview.docente.crear-caso') }}" href="{{ route('preview.docente.crear-caso') }}">Crear caso</a>
    <a class="nav-link{{ $en('preview.docente.asignaciones') }}" href="{{ route('preview.docente.asignaciones') }}">Asignación de casos</a>
    <a class="nav-link{{ $en('preview.docente.revision-fichas') }}" href="{{ route('preview.docente.revision-fichas') }}">Revisión de fichas</a>
    <a class="nav-link{{ $en('preview.docente.evaluaciones') }}" href="{{ route('preview.docente.evaluaciones') }}">Evaluaciones</a>
    <div class="nav-title">Cuenta</div>
    <form method="POST" action="{{ route('delivery.logout') }}">@csrf<button class="nav-link nav-logout" type="submit">Cerrar sesión</button></form>
</aside>
