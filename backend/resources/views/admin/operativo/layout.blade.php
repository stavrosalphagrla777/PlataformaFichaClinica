<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title','Administración') | Ficha Clínica TP</title>
<link rel="stylesheet" href="{{ asset('css/admin-usuarios.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin-operativo-patch.css') }}">
<link rel="stylesheet" href="{{ asset('css/nav-compartido.css') }}">
<link rel="stylesheet" href="{{ asset('css/admin-gestion-complemento.css') }}">
@stack('styles')
</head><body><div class="app" id="app">
@include('shared.navigation')
<div class="main"><header class="topbar">
 <div class="topbar-left"><button class="menu-toggle" type="button" id="menuToggle" aria-label="Ocultar menú lateral" aria-expanded="true" aria-controls="sidebar"><span></span><span></span><span></span></button><strong>@yield('title','Administración')</strong></div>
 <div class="user-info"><div><strong>{{ auth()->user()?->name ?? 'Administrador' }}</strong><small>Panel administrativo</small></div><div class="user-avatar">A</div></div>
</header><main class="content">
@if(session('ok'))<div class="notice" role="status">{{ session('ok') }}</div>@endif
@if($errors->any())<div class="notice error" role="alert"><strong>Revisa los datos:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
@yield('content')
</main></div></div>
<script src="{{ asset('js/sidebar-compartida.js') }}" defer></script></body></html>
