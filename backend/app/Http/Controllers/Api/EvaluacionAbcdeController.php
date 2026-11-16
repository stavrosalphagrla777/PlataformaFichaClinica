<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EvaluacionAbcde;
use Illuminate\Http\Request;

class EvaluacionAbcdeController extends Controller
{
    public function store(Request $request, $medicalRecordId)
    {
        // 1. Validar acceso a la ficha (esto ya funciona con el MedicalRecordAccess corregido)
        $medicalRecordId = \App\Support\MedicalRecordAccess::forWrite($request, $medicalRecordId)->id;

        // 2. Validar los datos que envía el frontend (Nombres del HTML)
        $validated = $request->validate([
            'obstruccion' => 'nullable|string',
            'collar' => 'nullable|string',
            'aspiracion' => 'nullable|string',
            'intubacion' => 'nullable|string',
            'murmullo' => 'nullable|string',
            'simetria' => 'nullable|string',
            'ventilacion' => 'nullable|string',
            'oxigenoterapia' => 'nullable|string',
            'flujo' => 'nullable|numeric',
            'esfuerzo' => 'nullable|string',
            'pulso' => 'nullable|numeric',
            'ritmo' => 'nullable|string',
            'calidad' => 'nullable|string',
            'hemorragia' => 'nullable|string',
            'capilar' => 'nullable|string',
            'accesos' => 'nullable|string',
            'ocular' => 'nullable|string',
            'verbal' => 'nullable|string',
            'motora' => 'nullable|string',
            'pupilas' => 'nullable|string',
            'avdi' => 'nullable|string',
            'termica' => 'nullable|string',
            'quemaduras' => 'nullable|string',
            'scq' => 'nullable|numeric',
            'lesiones' => 'nullable|array',
            'observaciones' => 'nullable|string',
            'glasgow' => 'nullable|numeric', // El total calculado por el frontend
        ]);

        // 3. Mapear los datos del frontend a las columnas de la tabla (según el modelo original)
        $evaluacionData = [
            // A
            'obstruccion_via_aerea' => $this->mapearObstruccion($validated['obstruccion'] ?? ''),
            'collar_cervical' => $this->mapearCollar($validated['collar'] ?? ''),
            'aspiracion_secreciones' => $this->mapearAspiracion($validated['aspiracion'] ?? ''),
            'intubacion' => $this->mapearIntubacion($validated['intubacion'] ?? ''),
            'observaciones_a' => $validated['observaciones'] ?? null,
            // B
            'murmullo_pulmonar' => $this->mapearMurmullo($validated['murmullo'] ?? ''),
            'simetria_toracica' => $this->mapearSimetria($validated['simetria'] ?? ''),
            'oxigenoterapia' => $validated['oxigenoterapia'] !== 'sin' ? 1 : 0,
            'tipo_dispositivo_o2' => $validated['oxigenoterapia'] ?? null,
            'flujo_o2_lmin' => $validated['flujo'] ?? null,
            'ventilacion' => $this->mapearVentilacion($validated['ventilacion'] ?? ''),
            'observaciones_b' => null,
            // C
            'pulso_frecuencia' => $validated['pulso'] ?? null,
            'pulso_ritmo' => $this->mapearRitmo($validated['ritmo'] ?? ''),
            'pulso_calidad' => $this->mapearCalidad($validated['calidad'] ?? ''),
            'hemorragias' => $this->mapearHemorragia($validated['hemorragia'] ?? ''),
            'llenado_capilar' => $this->mapearCapilar($validated['capilar'] ?? ''),
            'accesos_venosos_perifericos' => $this->mapearAccesos($validated['accesos'] ?? ''),
            'observaciones_c' => null,
            // D (Glasgow)
            'glasgow_apertura_ocular' => (int)($validated['ocular'] ?? 0),
            'glasgow_respuesta_verbal' => (int)($validated['verbal'] ?? 0),
            'glasgow_respuesta_motora' => (int)($validated['motora'] ?? 0),
            'pupilas' => $this->mapearPupilas($validated['pupilas'] ?? ''),
            'escala_avdi' => $this->mapearAvdi($validated['avdi'] ?? ''),
            'observaciones_d' => null,
            // E
            'lesiones_heridas' => in_array('Heridas', $validated['lesiones'] ?? []),
            'lesiones_fracturas' => in_array('Fracturas', $validated['lesiones'] ?? []),
            'lesiones_contusiones' => in_array('Contusiones', $validated['lesiones'] ?? []),
            'temperatura_corporal' => $this->mapearTemperatura($validated['termica'] ?? ''),
            'quemaduras' => $validated['quemaduras'] !== 'No presenta',
            'porcentaje_scq' => $validated['scq'] ?? 0,
            'observaciones_e' => null,
            'observaciones_generales' => $validated['observaciones'] ?? null,
            
            // Metadatos
            'medical_record_id' => $medicalRecordId,
            'estudiante_id' => $request->user()->id,
            'total_glasgow' => $validated['glasgow'] ?? 0,
        ];

        $evaluacion = EvaluacionAbcde::create($evaluacionData);

        return response()->json([
            'message' => 'Evaluación ABCDE registrada con éxito 🩺',
            'data' => $evaluacion
        ], 201);
    }

    // --- Funciones auxiliares para mapear los textos del frontend a los valores de la BD ---
    private function mapearObstruccion($v) { return str_contains($v, 'No') ? 'no' : (str_contains($v, 'Parcial') ? 'parcial' : 'total'); }
    private function mapearCollar($v) { return str_contains($v, 'No indicado') ? 'no_indicado' : (str_contains($v, 'no colocado') ? 'indicado' : 'colocado'); }
    private function mapearAspiracion($v) { return str_contains($v, 'No requerida') ? 'no_realizada' : 'realizada'; }
    private function mapearIntubacion($v) { return str_contains($v, 'No requerido') ? 'no_realizada' : (str_contains($v, 'orotraqueal') ? 'orotraqueal' : 'nasotraqueal'); }
    private function mapearMurmullo($v) { return str_contains($v, 'Conservado') ? 'normal' : (str_contains($v, 'Disminuido') ? 'disminuido' : 'ausente'); }
    private function mapearSimetria($v) { return str_contains($v, 'Simétrica') ? 'simetrica' : 'asimetrica'; }
    private function mapearVentilacion($v) { return str_contains($v, 'Espontánea') ? 'espontanea' : (str_contains($v, 'Asistida') ? 'asistida' : 'mecanica'); }
    private function mapearRitmo($v) { return str_contains($v, 'Regular') ? 'regular' : 'irregular'; }
    private function mapearCalidad($v) { return str_contains($v, 'Normal') ? 'fuerte' : (str_contains($v, 'Débil') ? 'debil' : 'filiforme'); }
    private function mapearHemorragia($v) { return str_contains($v, 'No presenta') ? 'no_presenta' : (str_contains($v, 'Externa') ? 'externas' : (str_contains($v, 'Interna') ? 'internas' : 'controladas')); }
    private function mapearCapilar($v) { return str_contains($v, 'Menor') ? 'menor_2seg' : (str_contains($v, 'Entre') ? '2_4seg' : 'mayor_4seg'); }
    private function mapearAccesos($v) { return str_contains($v, 'No tiene') ? 0 : (str_contains($v, '1 acceso') ? 1 : 2); }
    private function mapearPupilas($v) { return str_contains($v, 'reactivas') ? 'iguales_reactivas' : (str_contains($v, 'no reactivas') ? 'iguales_no_reactivas' : (str_contains($v, 'Anisocóricas') ? 'anisocoricas' : (str_contains($v, 'Midriáticas') ? 'midriaticas' : 'mioticas'))); }
    private function mapearAvdi($v) { return str_contains($v, 'Alerta') ? 'alerta' : (str_contains($v, 'voz') ? 'verbal' : (str_contains($v, 'dolor') ? 'dolor' : 'no_responde')); }
    private function mapearTemperatura($v) { return str_contains($v, 'Hipotermia') ? 'hipotermia_leve' : (str_contains($v, 'Normotermia') ? 'normal' : 'hipertermia'); }
}