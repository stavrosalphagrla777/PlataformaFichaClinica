<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Revisión CESFAM - Ficha Clínica TP</title>
    <link rel="stylesheet" href="{{ asset('css/docente.css') }}">
    <link rel="stylesheet" href="{{ asset('css/docente-registro-legible.css') }}">
    <style>
        main {max-width:1100px;margin:25px auto;padding:20px}
        .panel2 {border:1px solid #dbe3ed;background:white;border-radius:10px;padding:20px;margin:14px 0}
        .clinical-record {margin-top:12px}
        .feedback-form input,.feedback-form textarea {display:block;padding:10px;border:1px solid #abc;border-radius:5px;width:100%;margin:6px 0 16px}
        .feedback-form button {padding:12px;background:#155fa7;color:white;border:0;border-radius:6px;margin-right:8px;cursor:pointer}
        .feedback-form button:disabled {opacity:.6}
    </style>
</head>
<body>
<main>
    <a href="{{ route('preview.docente.revision-3ro') }}">← Fichas de 3.º medio</a>
    <h1>Ficha de {{ $record->estudiante?->name }}</h1>
    <p>{{ $record->assignment->clinicalCase->codigo }} - {{ $record->assignment->clinicalCase->titulo }} - Estado: {{ $estado }}</p>
    @forelse($record->sections as $section)
        <section class="panel2">
            <h2>{{ ucwords(str_replace(['-','_'], ' ', $section->clave)) }}</h2>
            <div class="clinical-record" data-record="{{ base64_encode($section->payload) }}">
                <p>Cargando registros...</p>
            </div>
        </section>
    @empty
        <p>Aún no existen registros guardados para esta ficha.</p>
    @endforelse
    <section class="panel2">
        <h2>Retroalimentación y evaluación</h2>
        <p>Revisa la información registrada por el estudiante y consigna los resultados de su evaluación.</p>
        @if(session('ok'))<p role="status">{{ session('ok') }}</p>@endif
        @if($errors->any())<p role="alert">{{ $errors->first() }}</p>@endif
        <form class="feedback-form" method="POST" action="{{ route('preview.docente.revision-3ro.evaluar', $record) }}">
            @csrf
            <label for="retroalimentacion">Retroalimentación</label>
            <textarea required rows="5" id="retroalimentacion" name="retroalimentacion" maxlength="10000" @disabled($record->evaluation?->estado === 'finalizada')>{{ old('retroalimentacion', $record->evaluation?->retroalimentacion) }}</textarea>
            <label for="nota">Nota (2,0 a 7,0)</label>
            <input id="nota" type="number" name="nota" step="0.1" min="2" max="7" value="{{ old('nota', $record->evaluation?->nota) }}" @disabled($record->evaluation?->estado === 'finalizada')>
            <label for="porcentaje">Porcentaje de logro (20 a 100)</label>
            <input id="porcentaje" type="number" name="porcentaje" step="0.01" min="20" max="100" value="{{ old('porcentaje', $record->evaluation?->porcentaje) }}" @disabled($record->evaluation?->estado === 'finalizada')>
            @if($record->evaluation?->estado !== 'finalizada')
                <button name="finalizar" value="0">Guardar evaluación</button>
                @if(in_array($estado, ['Entregada', 'Tiempo agotado']))
                    <button name="finalizar" value="1" onclick="return confirm('¿Finalizar la evaluación? No podrá modificarse.')">Finalizar evaluación</button>
                @endif
            @endif
        </form>
    </section>
</main>
<script src="{{ asset('js/docente/registro-legible.js') }}"></script>
<script>
    // La revisión presenta el JSON persistido como campos clínicos legibles,
    // sin insertar datos del estudiante como HTML ni mostrarlos como código.
    document.querySelectorAll('.clinical-record[data-record]').forEach(container => {
        try {
            const bytes = Uint8Array.from(atob(container.dataset.record), value => value.charCodeAt(0));
            const raw = new TextDecoder('utf-8').decode(bytes);
            container.replaceChildren(window.FichaRecordView.render(raw));
        } catch (error) {
            console.error('No fue posible representar la sección clínica:', error);
            container.textContent = 'No fue posible mostrar esta sección. Vuelve a intentarlo.';
        }
    });
</script>
@include('shared.app-extras')
</body>
</html>
