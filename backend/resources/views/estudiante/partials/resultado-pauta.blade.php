@php
    $pautaTercero = $assignment->nivel === '3.º medio';
    $criteriosPauta = $pautaTercero
        ? ['Registro y antecedentes', 'Signos vitales y antropometría', 'Interpretación de signos vitales', 'Examen físico general', 'Prestaciones / Acciones TENS', 'Educación al paciente', 'Confirmación de educación', 'Citaciones y próximos controles', 'Cierre y cumplimiento']
        : ['Admisión', 'Categorización Triage', 'Evaluación ABCDE', 'Anamnesis y examen físico', 'Procedimientos TENS', 'Administración de medicamentos', 'Revisión de exámenes', 'Acciones de enfermería', 'Destino y cierre'];
    if (is_array($record->evaluation->rubrica_criterios ?? null) && count($record->evaluation->rubrica_criterios) === 9) {
        $criteriosPauta = array_map(fn($row) => (string)($row[0] ?? ''), $record->evaluation->rubrica_criterios);
    }
    $respuestasPauta = $record->evaluation->criterios ?? [];
    $observacionesPauta = $record->evaluation->notas ?? [];
@endphp
@if(is_array($respuestasPauta) && array_is_list($respuestasPauta) && count($respuestasPauta) === 9)
<h3>Resultados y observaciones por criterio</h3>
<p class="case-help">Consulta lo señalado por el docente en cada punto de la pauta de cotejo.</p>
<div class="case-rubric-scroll"><table class="case-rubric-table"><thead><tr><th>N.º</th><th>Criterio</th><th>Resultado</th><th>Comentario del docente</th></tr></thead><tbody>
@foreach($criteriosPauta as $i => $tituloPauta)
<tr><td>{{ $i+1 }}</td><td><strong>{{ $tituloPauta }}</strong></td><td>
@switch($respuestasPauta[$i] ?? 'pendiente')
@case('si') <span class="case-rubric-badge is-yes">Sí cumple</span> @break
@case('no') <span class="case-rubric-badge is-no">No cumple</span> @break
@default <span class="case-rubric-badge is-pending">Sin revisión</span>
@endswitch
</td><td>{{ trim((string)($observacionesPauta[$i] ?? '')) ?: 'Sin comentarios específicos.' }}</td></tr>
@endforeach
</tbody></table></div>
@else
<p class="case-help">La evaluación se registró en una modalidad anterior sin desglose de criterios. Los comentarios generales se mantienen disponibles.</p>
@endif
<div class="case-rubric-download"><a class="case-pdf-button" href="{{ route('delivery.evaluacion.pdf', ['record' => $record->id]) }}"><span aria-hidden="true">↓</span> Descargar pauta de evaluación en PDF</a><small>Incluye los criterios, las observaciones, la nota y el porcentaje de logro.</small></div>
