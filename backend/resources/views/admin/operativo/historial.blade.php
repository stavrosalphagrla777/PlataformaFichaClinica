@extends('admin.operativo.layout')
@section('title', 'Historial')
@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-historial-filtros.css') }}">
@endpush
@section('content')
<div class="heading">
    <div>
        <h1>Historial de actividad del sistema</h1>
        <p class="subtitle">Seguimiento de usuarios, casos, asignaciones, entregas de estudiantes y evaluaciones con sus notas.</p>
    </div>
</div>
<section class="panel historial-panel" aria-labelledby="historialTitle">
    <h2 id="historialTitle">Registro de actividades</h2>
    <p class="muted">Se registran las actividades auditadas a partir de su implementación. Las actividades anteriores no se incorporan retroactivamente. Las entregas y notas solo se registran al finalizar.</p>

    <form method="GET" action="{{ route('admin.historial') }}" class="historial-filtros" aria-label="Filtrar historial">
        <div class="historial-campo historial-busqueda">
            <label for="histBuscar">Buscar actividad o usuario</label>
            <input id="histBuscar" name="buscar" type="search" value="{{ request('buscar') }}" maxlength="120" placeholder="Nombre, correo, acción o detalle">
        </div>
        <div class="historial-campo">
            <label for="histAdmin">Responsable de la actividad</label>
            <select id="histAdmin" name="responsable">
                <option value="">Todos</option>
                @foreach($responsables as $responsable)
                    <option value="{{ $responsable->id }}" @selected((string) request('responsable', request('administrador')) === (string) $responsable->id)>{{ $responsable->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="historial-campo">
            <label for="histAccion">Acción</label>
            <select id="histAccion" name="accion">
                <option value="">Todas las acciones</option>
                @foreach($acciones as $accion)
                    <option value="{{ $accion }}" @selected(request('accion') === $accion)>{{ ucfirst(str_replace('_', ' ', $accion)) }}</option>
                @endforeach
            </select>
        </div>
        <div class="historial-campo">
            <label for="histDesde">Desde</label>
            <input id="histDesde" name="desde" type="date" value="{{ request('desde') }}">
        </div>
        <div class="historial-campo">
            <label for="histHasta">Hasta</label>
            <input id="histHasta" name="hasta" type="date" value="{{ request('hasta') }}" min="{{ request('desde') }}">
        </div>
        <div class="historial-acciones">
            <button type="submit">Aplicar filtros</button>
            <a href="{{ route('admin.historial') }}" class="historial-limpiar">Limpiar filtros</a>
        </div>
    </form>

    <div class="historial-resumen" role="status">
        <span>{{ $events->total() }} {{ $events->total() === 1 ? 'actividad encontrada' : 'actividades encontradas' }}</span>
        <span>15 registros por página</span>
    </div>
    <div class="table-wrap historial-tabla">
        <table>
            <thead><tr>
                <th scope="col">Fecha y hora</th>
                <th scope="col">Responsable</th>
                <th scope="col">Acción</th>
                <th scope="col">Usuario afectado</th>
                <th scope="col">Detalle</th>
            </tr></thead>
            <tbody>
                @forelse($events as $event)
                    <tr>
                        <td class="historial-fecha">{{ $event->created_at?->format('d/m/Y H:i') ?? '—' }}</td>
                        <td><strong>{{ $event->actor?->name ?? 'Cuenta no disponible' }}</strong></td>
                        <td><span class="historial-accion">{{ ucfirst(str_replace('_', ' ', $event->action)) }}</span></td>
                        <td>{{ $event->target?->username ?? '—' }}</td>
                        <td class="historial-detalle">{{ $event->description ?: 'Sin descripción' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="historial-vacio">No se encontraron actividades con los filtros seleccionados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="historial-pie">
        <p>Mostrando {{ $events->count() ? $events->firstItem() . '–' . $events->lastItem() : 0 }} de {{ $events->total() }} registros</p>
        @if($events->lastPage() > 1)
            <nav class="historial-paginas" aria-label="Paginación del historial">
                @if($events->onFirstPage())
                    <span class="historial-desactivada">Anterior</span>
                @else
                    <a href="{{ $events->previousPageUrl() }}" rel="prev">Anterior</a>
                @endif
                @php
                    $pagInicio = max(1, $events->currentPage() - 2);
                    $pagFin = min($events->lastPage(), $events->currentPage() + 2);
                @endphp
                @if($pagInicio > 1)
                    <a href="{{ $events->url(1) }}" aria-label="Página 1">1</a>
                    @if($pagInicio > 2)<span aria-hidden="true">…</span>@endif
                @endif
                @for($pagina = $pagInicio; $pagina <= $pagFin; $pagina++)
                    @if($pagina === $events->currentPage())
                        <span class="historial-actual" aria-current="page">{{ $pagina }}</span>
                    @else
                        <a href="{{ $events->url($pagina) }}" aria-label="Página {{ $pagina }}">{{ $pagina }}</a>
                    @endif
                @endfor
                @if($pagFin < $events->lastPage())
                    @if($pagFin < $events->lastPage() - 1)<span aria-hidden="true">…</span>@endif
                    <a href="{{ $events->url($events->lastPage()) }}" aria-label="Página {{ $events->lastPage() }}">{{ $events->lastPage() }}</a>
                @endif
                @if($events->hasMorePages())
                    <a href="{{ $events->nextPageUrl() }}" rel="next">Siguiente</a>
                @else
                    <span class="historial-desactivada">Siguiente</span>
                @endif
            </nav>
        @endif
    </div>
</section>
@endsection
