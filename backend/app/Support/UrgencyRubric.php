<?php

namespace App\Support;

final class UrgencyRubric
{
    // Pauta de cotejo del liceo: Urgencias, 9 criterios. La decisión es del docente.
    public const CRITERIA = [
        'Admisión: datos del paciente, previsión, contacto y medicamentos habituales.',
        'Triage: motivo, prioridad ESI y escala de dolor EVA.',
        'Evaluación primaria ABCDE completa.',
        'Anamnesis y examen físico orientados al motivo de urgencia.',
        'Procedimientos TENS con hora y responsable.',
        'Administración de medicamentos: fármaco, dosis, vía, hora y reacciones.',
        'Revisión de exámenes adjuntos, con fecha y hora.',
        'Acciones de enfermería registradas cronológicamente.',
        'Destino y cierre justificados dentro del tiempo establecido.',
    ];

    public static function result(array $answers): array
    {
        $count = count(self::CRITERIA);
        $yes = count(array_filter($answers, fn ($v) => $v === 'si'));
        $pending = $count - count(array_filter($answers, fn ($v) => in_array($v, ['si', 'no'], true)));
        // Escala acordada: el logro comienza en 20% (sin criterios cumplidos)
        // y progresa linealmente hasta 100% (todos los criterios cumplidos).
        // 20%=2,0; 60%=4,0; 100%=7,0. Criterios clínicos: revisión docente.
        $percentage = round(20 + 80 * $yes / $count, 2);
        $grade = $percentage < 60
            ? round(2 + (($percentage - 20) / 40) * 2, 1)
            : round(4 + (($percentage - 60) / 40) * 3, 1);
        return ['total' => $count, 'si' => $yes, 'pendientes' => $pending, 'porcentaje' => $percentage, 'nota' => min(7.0, $grade), 'aprobado' => $percentage >= 60];
    }
}
