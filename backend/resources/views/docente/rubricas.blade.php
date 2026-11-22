<!doctype html>
<html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><meta name="csrf-token" content="{{ csrf_token() }}">
<title>Pautas y rúbricas - Ficha Clínica TP</title>
<link rel="stylesheet" href="{{ asset('css/docente.css') }}"><link rel="stylesheet" href="{{ asset('css/nav-compartido.css') }}">
<style>
.rub-wrap{padding:26px;max-width:1180px;margin:0 auto;color:#1f2937}.rub-panel{background:#fff;border:1px solid #d5dce4;border-radius:12px;padding:24px;margin:18px 0}.rub-grid{display:grid;grid-template-columns:1fr 1fr;gap:14px}.rub-grid label{display:block;font-weight:600}.rub-panel input,.rub-panel select{width:100%;padding:10px;border:1px solid #bdcad6;border-radius:6px;margin-top:6px;color:#17273b}.rub-line{display:grid;grid-template-columns:30px minmax(140px,1fr) 1.5fr;gap:10px;margin:12px 0;align-items:center}.rub-btn{background:#155a91;color:white;border:0;padding:10px 17px;border-radius:6px;cursor:pointer;display:inline-block;text-decoration:none;font-weight:600}.rub-btn.secondary{background:#e8f1f8;color:#174e7a;border:1px solid #aec6da}.rub-btn:focus-visible{outline:3px solid #286ba9;outline-offset:2px}.rub-note{color:#475569}.rub-items{display:grid;gap:12px}.rub-item{padding:16px;border:1px solid #dde5ed;border-radius:8px;background:#fbfdff}.rub-item h3{margin:0 0 7px}.rub-item p{margin:0 0 12px}.rub-actions{display:flex;gap:10px;flex-wrap:wrap}.rub-case{border-top:1px solid #e2e8f0;padding:16px 0}.rub-case:first-child{border:0}.rub-case strong{display:block;margin-bottom:10px}@media(max-width:780px){.rub-line,.rub-grid{grid-template-columns:1fr}.rub-wrap{padding:14px}}
</style></head><body><div class="app" id="app">@include('shared.navigation')<div class="main">
<header class="topbar"><div class="topbar-left"><button type="button" class="menu-toggle" id="menuToggle" aria-label="Mostrar u ocultar menú">☰</button><strong>Pautas y rúbricas</strong></div><div class="user-info"><strong>{{ auth()->user()?->name }}</strong></div></header>
<main class="rub-wrap"><h1>Pautas de evaluación</h1><p class="rub-note">Consulta, descarga y asigna pautas de nueve criterios sin modificar las evaluaciones finalizadas.</p>
@if(session('ok'))<p role="status" class="rub-panel">{{ session('ok') }}</p>@endif
@if($errors->any())<div role="alert" class="rub-panel" style="border-color:#cc3650;color:#8b1930">{{ implode(' ', $errors->all()) }}</div>@endif
<section class="rub-panel"><h2>Pautas predeterminadas</h2><p class="rub-note">Criterios institucionales disponibles para ambos niveles.</p><div class="rub-items">
@foreach(['tercero'=>'3.º medio (CESFAM)','cuarto'=>'4.º medio (Urgencias)'] as $slug=>$label)
<article class="rub-item"><h3>Pauta de {{ $label }}</h3><p>Modelo general de nueve criterios.</p><div class="rub-actions"><a class="rub-btn secondary" href="{{ route('preview.docente.rubricas.defaults',$slug) }}">Ver pauta</a><a class="rub-btn" href="{{ route('preview.docente.rubricas.default-pdf',$slug) }}">Descargar PDF</a></div></article>
@endforeach</div></section>
<section class="rub-panel"><h2>Pautas guardadas <small>({{ $rubrics->count() }})</small></h2><div class="rub-items">
@forelse($rubrics as $rubric)
<article class="rub-item"><h3>{{ $rubric->nombre }}</h3><p class="rub-note">{{ $rubric->nivel }} - Creada por {{ $rubric->docente?->name ?? 'Docente' }} - {{ $rubric->created_at?->format('d/m/Y') }}</p><div class="rub-actions"><a class="rub-btn secondary" href="{{ route('preview.docente.rubricas.show',$rubric) }}">Ver criterios</a><a class="rub-btn" href="{{ route('preview.docente.rubricas.pdf',$rubric) }}">Descargar PDF</a></div></article>
@empty<p>No hay pautas personalizadas guardadas todavía. Puedes crear la primera más abajo.</p>@endforelse
</div></section>
<section class="rub-panel"><h2>Crear pauta personalizada</h2><p class="rub-note">Cada pauta contiene nueve criterios. Los criterios de evaluaciones finalizadas no se modifican.</p>
<form method="POST" action="{{ route('preview.docente.rubricas.store') }}">@csrf
<div class="rub-grid"><label>Nombre de la pauta<input name="nombre" maxlength="160" required value="{{ old('nombre') }}" placeholder="Ej.: Evaluación CESFAM - Control de salud"></label><label>Nivel<select name="nivel" required><option value="3.º medio" @selected(old('nivel')==='3.º medio')>3.º medio</option><option value="4.º medio" @selected(old('nivel')==='4.º medio')>4.º medio</option></select></label></div>
@for($i=0;$i<9;$i++)<div class="rub-line"><strong>{{ $i+1 }}.</strong><input name="titulos[]" required maxlength="200" aria-label="Título del criterio {{ $i+1 }}" value="{{ old('titulos.'.$i) }}" placeholder="Título del criterio {{ $i+1 }}"><input name="descripciones[]" maxlength="500" aria-label="Descripción del criterio {{ $i+1 }}" value="{{ old('descripciones.'.$i) }}" placeholder="Descripción / evidencia requerida (opcional)"></div>@endfor
<button class="rub-btn" type="submit">Guardar pauta</button></form></section>
<section class="rub-panel"><h2>Definir la pauta predeterminada de un caso</h2><p class="rub-note">Puedes elegir también una pauta específica para cada asignación desde «Asignación de casos». La selección aquí se aplica al caso general mientras no existan fichas iniciadas.</p>
@forelse($cases as $case)
<form class="rub-case" method="POST" action="{{ route('preview.docente.rubricas.assign',$case) }}">@csrf
<strong>{{ $case->codigo }} - {{ $case->titulo }} ({{ $case->nivel }})</strong>
<div class="rub-grid"><select name="rubric_id" aria-label="Pauta de {{ $case->codigo }}"><option value="">Pauta predeterminada del nivel</option>
@foreach($rubrics->where('nivel',$case->nivel) as $rubric)<option value="{{ $rubric->id }}" @selected((int)$case->rubric_id===(int)$rubric->id)>{{ $rubric->nombre }}</option>@endforeach
</select><button class="rub-btn" type="submit">Guardar asignación de pauta</button></div></form>
@empty<p>No hay casos propios disponibles.</p>@endforelse</section>
</main></div></div><script src="{{ asset('js/sidebar-compartida.js') }}" defer></script>@include('shared.app-extras')
</body></html>
