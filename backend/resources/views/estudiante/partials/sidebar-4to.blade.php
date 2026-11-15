{{-- Menú compartido para todas las etapas de 4.º medio. --}}
@php
    $sidebarCodigo = $case->codigo ?? ($ficha4toContext['codigo'] ?? request()->query('caso'));
    $sidebarSeccion = request()->segment(2) === 'caso' ? 'resumen' : request()->segment(2);
    $sidebarEtapas = [
        ['resumen', 'Resumen', 'sidebarFicha'],
        ['admision', 'Admisión', 'sidebarAdmision'],
        ['triage', 'Triage', 'sidebarTriage'],
        ['signos-vitales', 'Signos vitales', 'sidebarSignos'],
        ['abcde', 'ABCDE', 'sidebarABCDE'],
        ['anamnesis', 'Anamnesis', 'sidebarAnamnesis'],
        ['examen-fisico', 'Examen físico', 'sidebarExaFisico'],
        ['procedimientos', 'Procedimientos', 'sidebarProcedimientos'],
        ['medicamentos_4to', 'Medicamentos', 'sidebarMedicamentos'],
        ['examenes', 'Exámenes', 'sidebarExamenes'],
        ['acciones-enfermeria', 'Acciones de enfermería', 'sidebarAccEnfermeria'],
        ['destino', 'Destino', 'sidebarDestino'],
    ];
@endphp
<style>
    /* La lista completa debe permanecer accesible sin desplazar la página. */
    .sidebar.sidebar-4to { position: sticky; top: 0; align-self: flex-start; height: 100vh; max-height: 100vh; }
    .sidebar.sidebar-4to nav { overflow-y: auto; overscroll-behavior: contain; flex: 1 1 auto; min-height: 0; scrollbar-width: thin; scrollbar-color: rgba(255,255,255,.45) transparent; }
    .sidebar.sidebar-4to nav .nav-link { white-space: normal; }
    .sidebar.sidebar-4to .nav-logout { display:block; width:100%; border:0; border-radius:0; background:transparent; font-size:14px; font-family:inherit; color:#fff; padding:13px 18px; text-align:left; cursor:pointer; }
    .sidebar.sidebar-4to .nav-logout:hover { background:rgba(255,255,255,.14); }
    @media (max-width:750px) {
        .sidebar.sidebar-4to { position:relative; height:auto; max-height:min(80vh,700px); align-self:stretch; }
        .app.sidebar-hidden .sidebar.sidebar-4to { max-height:0; }
    }
</style>
<aside class="sidebar sidebar-4to" id="sidebar">
    <div class="brand">
        <div class="brand-logo"><img src="{{ asset('images/logo-tp.png') }}" alt="Logo TP Enfermería"></div>
        <span>Ficha Clínica TP</span>
    </div>
    <nav aria-label="Navegación principal">
        <div class="nav-title">Principal</div>
        <a class="nav-link" href="{{ route('preview.estudiante.casos') }}">Mis casos clínicos</a>
        <div class="nav-title">Atención clínica</div>
        @foreach ($sidebarEtapas as [$slug, $etiqueta, $identificador])
            @php
                $enlace = $slug === 'resumen'
                    ? route('preview.estudiante.caso', ['id' => $sidebarCodigo])
                    : route('preview.estudiante.' . $slug, ['caso' => $sidebarCodigo]);
                $seleccionada = $sidebarSeccion === $slug;
            @endphp
            <a class="nav-link {{ $seleccionada ? 'active' : '' }}" id="{{ $identificador }}"
                href="{{ $enlace }}" @if($seleccionada) aria-current="page" @endif>{{ $etiqueta }}</a>
        @endforeach
        <div class="nav-title">Cuenta</div>
        <span class="nav-link nav-disabled" title="Perfil no disponible todavía" aria-disabled="true">Mi perfil</span>
        <form method="POST" action="{{ route('delivery.logout') }}">
            @csrf
            <button class="nav-link nav-logout" type="submit">Cerrar sesión</button>
        </form>
    </nav>
</aside>

{{-- Los enlaces horizontales usan las rutas completas de este menú, sin '#'. --}}
<script src="{{ asset('js/estudiante/navegacion-horizontal-4to.js') }}" defer></script>
