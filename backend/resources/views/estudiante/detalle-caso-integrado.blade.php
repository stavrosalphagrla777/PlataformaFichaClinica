<!DOCTYPE html>
<html lang="es"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>{{ $case->codigo }} · Ficha Clínica TP</title>
<link rel="stylesheet" href="{{ asset('css/estudiante-casos.css') }}">
<link rel="stylesheet" href="{{ asset('css/estudiante-3ro-detalle.css') }}">
<link rel="stylesheet" href="{{ asset('css/evaluacion-descarga.css') }}"></head>
<body><div class="app app-shell" id="appShell">
@include('estudiante.partials.sidebar-4to')
<div class="main"><header class="topbar"><div class="topbar-left"><button type="button" class="menu-toggle" id="menuToggle" aria-label="Mostrar u ocultar menú" aria-controls="sidebar" aria-expanded="true"><span></span><span></span><span></span></button><strong>Detalle del caso · 4.º medio</strong></div><div class="user-info"><strong>{{ auth()->user()?->name }}</strong><small>Estudiante · Urgencias</small></div></header>
<main class="content"><div class="page-heading"><div><p class="case-eyebrow">FICHA CLÍNICA EDUCATIVA · URGENCIAS</p><h1>{{ $case->titulo }}</h1><p>{{ $case->codigo }} · {{ $case->tipo }} · Servicio de urgencias</p></div><a class="btn-secondary-link" href="{{ route('preview.estudiante.casos') }}">← Volver a mis casos</a></div>
<div class="case-overview-grid"><section class="case-panel case-description"><h2>Descripción del caso clínico</h2>
<!-- CORRECCIÓN AQUÍ: Renderizar HTML directamente -->
<div class="case-richtext">{!! $case->descripcion !!}</div>
@if($case->antecedentes)<h3>Antecedentes del escenario</h3>
<div class="case-richtext">{!! $case->antecedentes !!}</div>@endif
@if($assignment->observaciones)<h3>Indicaciones del docente</h3><p class="case-richtext">{{ $assignment->observaciones }}</p>@endif</section>
<section class="case-panel"><h2>Datos de la actividad</h2><dl class="case-facts"><dt>Código</dt><dd>{{ $case->codigo }}</dd><dt>Paciente simulado</dt><dd>{{ $case->paciente ?: 'Sin identificar' }}</dd><dt>Edad</dt><dd>{{ $case->edad ?? 'No informada' }}</dd><dt>Clasificación ESI</dt><dd>{{ $case->esi ?? 'No asignada' }}</dd><dt>Curso</dt><dd>4.º medio</dd><dt>Inicio</dt><dd>{{ $assignment->inicio->format('d/m/Y H:i') }}</dd><dt>Término</dt><dd>{{ $assignment->termino->format('d/m/Y H:i') }}</dd><dt>Duración máxima</dt><dd>{{ $assignment->effectiveDuration() }} minutos</dd><dt>Estado</dt><dd><span class="case-state">{{ $status }}</span></dd></dl></section></div>
@if(collect($case->segmentario ?? [])->filter(fn($v)=>trim((string)$v)!=='')->isNotEmpty())
<section class="case-panel"><h2>Hallazgos del examen físico segmentario</h2><p class="case-help">Datos del escenario aportados por el docente. No sustituyen tu propia valoración.</p><div class="case-segmentario-grid">
@foreach(['cabeza_cuello'=>'Cabeza y cuello','torax_abdomen'=>'Tórax y abdomen','extremidades_piel'=>'Extremidades y piel','neurologico'=>'Neurológico'] as $field=>$name)
<!-- CORRECCIÓN AQUÍ: Renderizar HTML en segmentario -->
@if(filled($case->segmentario[$field] ?? null))<div class="case-segmentario-item"><strong>{{ $name }}</strong><p class="case-richtext">{!! $case->segmentario[$field] !!}</p></div>@endif
@endforeach
</div></section>
@endif
<section class="case-panel"><h2>Etapas de la ficha clínica</h2><p class="case-help">Completa los registros de cada sección. Puedes guardar borradores. La entrega definitiva se realiza desde la última etapa: <strong>Destino y cierre</strong>.</p>
<div class="case-stage-grid case-stage-grid-4to">@foreach($sections as $slug=>$info)<a class="case-stage {{ !$canStart && !$record ? 'is-disabled' : '' }}" href="{{ route('preview.estudiante.'.$slug,['caso'=>$case->codigo]) }}"><span class="case-stage-index">{{ $loop->iteration }}</span><span><strong>{{ $info[0] }}</strong><small>{{ $record?->sections?->contains('clave',$slug) ? 'Registro guardado' : 'Pendiente de registro' }}</small></span><span class="case-stage-arrow">›</span></a>@endforeach</div>
</section>
@if($record?->evaluation?->estado === 'finalizada')
<section class="case-panel case-feedback" id="retroalimentacion"><h2>Evaluación y retroalimentación docente</h2><p>Estado: <strong>{{ $record->evaluation->estado === 'finalizada' ? 'Evaluación finalizada' : 'Evaluación en borrador' }}</strong></p>
@if($record->evaluation->estado === 'finalizada')<div class="case-result"><div><span>Porcentaje de logro</span><strong>{{ $record->evaluation->porcentaje !== null ? number_format($record->evaluation->porcentaje,1,',','.') . '%' : 'Sin porcentaje' }}</strong></div><div><span>Nota</span><strong>{{ $record->evaluation->nota !== null ? number_format($record->evaluation->nota,1,',','.') : 'Sin nota' }}</strong></div></div>@endif
@if($record->evaluation->retroalimentacion)<h3>Comentarios generales del docente</h3><p class="case-richtext">{{ $record->evaluation->retroalimentacion }}</p>@endif
@include('estudiante.partials.resultado-pauta')
</section>
@endif
@if($status === 'Entregada')<section class="case-panel"><h2>Ficha entregada</h2><p>Tu ficha ya fue entregada y está disponible para revisión docente. Puedes consultar tus respuestas desde las etapas y regresar para ver la retroalimentación cuando esté publicada.</p></section>@endif
</main></div></div><script>document.getElementById('menuToggle')?.addEventListener('click',function(){const shell=document.getElementById('appShell');const hidden=shell.classList.toggle('sidebar-hidden');this.setAttribute('aria-expanded',String(!hidden));});</script>@include('shared.app-extras')
</body></html>