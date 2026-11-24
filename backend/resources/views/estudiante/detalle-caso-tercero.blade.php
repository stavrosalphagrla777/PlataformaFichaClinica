<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $case->codigo }} · Ficha Clínica TP</title>
<link rel="stylesheet" href="{{ asset('css/estudiante-casos.css') }}">
<link rel="stylesheet" href="{{ asset('css/estudiante-3ro-detalle.css') }}">
<link rel="stylesheet" href="{{ asset('css/evaluacion-descarga.css') }}"></head>
<body><div class="app app-shell" id="appShell">
<aside class="sidebar" id="sidebar"><div class="brand"><div class="brand-logo"><img src="{{ asset('images/logo-tp.png') }}" alt="Logo TP"></div><span>Ficha Clínica TP</span></div>
<nav aria-label="Navegación principal"><div class="nav-title">Principal</div><a class="nav-link active" href="{{ route('preview.estudiante.casos') }}">Mis casos clínicos</a>
<div class="nav-title">Atención clínica · 3.º medio</div>
@foreach($sections as $slug => $info)<a class="nav-link" href="{{ route('preview.estudiante.3ro.'.$slug,['caso'=>$case->codigo]) }}">{{ $info[0] }}</a>@endforeach
<div class="nav-title">Cuenta</div><form action="{{ route('delivery.logout') }}" method="POST">@csrf<button type="submit" class="nav-link" style="width:100%;border:0;background:transparent;text-align:left;cursor:pointer">Cerrar sesión</button></form></nav></aside>
<div class="main"><header class="topbar"><div class="topbar-left"><button type="button" class="menu-toggle" id="menuToggle" aria-label="Mostrar u ocultar menú" aria-controls="sidebar" aria-expanded="true"><span></span><span></span><span></span></button><strong>Detalle del caso · 3.º medio</strong></div><div class="user-info"><strong>{{ auth()->user()?->name }}</strong><small>Estudiante · CESFAM</small></div></header>
<main class="content"><div class="page-heading"><div><p class="case-eyebrow">FICHA CLÍNICA EDUCATIVA · CESFAM</p><h1>{{ $case->titulo }}</h1><p>{{ $case->codigo }} · {{ $case->tipo }} · Atención primaria</p></div><a class="btn-secondary-link" href="{{ route('preview.estudiante.casos') }}">← Volver a mis casos</a></div>
<div class="case-overview-grid"><section class="case-panel case-description"><h2>Descripción del caso clínico</h2><div class="case-richtext">{!! nl2br(e(strip_tags(str_replace(['</p>','</li>','<br>','<br/>'],["\n","\n","\n","\n"],$case->descripcion)))) !!}</div>
@if($case->antecedentes)<h3>Antecedentes del escenario</h3><div class="case-richtext">{!! nl2br(e(strip_tags(str_replace(['</p>','</li>','<br>'],["\n","\n","\n"],$case->antecedentes)))) !!}</div>@endif
@if($assignment->observaciones)<h3>Indicaciones del docente</h3><p class="case-richtext">{{ $assignment->observaciones }}</p>@endif</section>
<section class="case-panel"><h2>Datos de la actividad</h2><dl class="case-facts"><dt>Código</dt><dd>{{ $case->codigo }}</dd><dt>Paciente simulado</dt><dd>{{ $case->paciente ?: 'Sin identificar' }}</dd><dt>Curso</dt><dd>3.º medio</dd><dt>Inicio</dt><dd>{{ $assignment->inicio->format('d/m/Y H:i') }}</dd><dt>Término</dt><dd>{{ $assignment->termino->format('d/m/Y H:i') }}</dd><dt>Duración máxima</dt><dd>{{ $assignment->effectiveDuration() }} minutos</dd><dt>Estado</dt><dd><span class="case-state">{{ $status }}</span></dd></dl></section></div>
<section class="case-panel"><h2>Etapas de la ficha CESFAM</h2><p class="case-help">Completa los registros de cada sección. Puedes guardar borradores. La entrega definitiva se realiza desde la última etapa: <strong>Citaciones y próximos controles</strong>.</p>
<div class="case-stage-grid">@foreach($sections as $slug=>$info)<a class="case-stage {{ !$canStart && !$record ? 'is-disabled' : '' }}" href="{{ route('preview.estudiante.3ro.'.$slug,['caso'=>$case->codigo]) }}"><span class="case-stage-index">{{ $loop->iteration }}</span><span><strong>{{ $info[0] }}</strong><small>{{ $record?->sections?->contains('clave',$slug) ? 'Registro guardado' : 'Pendiente de registro' }}</small></span><span class="case-stage-arrow">›</span></a>@endforeach</div>
</section>
@if($record?->evaluation?->estado === 'finalizada')
<section class="case-panel case-feedback" id="retroalimentacion"><h2>Evaluación y retroalimentación docente</h2><p>Estado: <strong>{{ $record->evaluation->estado === 'finalizada' ? 'Evaluación finalizada' : 'Evaluación en borrador' }}</strong></p>
@if($record->evaluation->estado === 'finalizada')<div class="case-result"><div><span>Porcentaje de logro</span><strong>{{ $record->evaluation->porcentaje !== null ? number_format($record->evaluation->porcentaje,1,',','.') . '%' : 'Sin porcentaje' }}</strong></div><div><span>Nota</span><strong>{{ $record->evaluation->nota !== null ? number_format($record->evaluation->nota,1,',','.') : 'Sin nota' }}</strong></div></div>@endif
@if($record->evaluation->retroalimentacion)<h3>Comentarios generales del docente</h3><p class="case-richtext">{{ $record->evaluation->retroalimentacion }}</p>@endif
@include('estudiante.partials.resultado-pauta')
</section>
@endif
@if($status === 'Entregada')<section class="case-panel"><h2>Ficha entregada</h2><p>La ficha ya fue entregada. Puedes consultar tus respuestas y regresar para ver la retroalimentación docente cuando esté disponible.</p></section>@endif
</main></div></div><script>document.getElementById('menuToggle')?.addEventListener('click',function(){const shell=document.getElementById('appShell');const hidden=shell.classList.toggle('sidebar-hidden');this.setAttribute('aria-expanded',String(!hidden));});</script>@include('shared.app-extras')
</body></html>
