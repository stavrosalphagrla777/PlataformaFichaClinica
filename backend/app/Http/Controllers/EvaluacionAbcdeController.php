<?php

namespace App\Http\Controllers;

use App\Models\EvaluacionAbcde;
use Illuminate\Http\Request;

class EvaluacionAbcdeController extends Controller
{
    public function store(Request $request, $medicalRecordId)
    {
        $validated = $request->validate([
            // A
            'obstruccion_via_aerea' => 'required|in:no,parcial,total',
            'collar_cervical' => 'required|in:no_indicado,indicado,colocado',
            'aspiracion_secreciones' => 'required|in:no_realizada,realizada',
            'intubacion' => 'required|in:no_realizada,orotraqueal,nasotraqueal',
            'observaciones_a' => 'nullable|string',
            // B
            'murmullo_pulmonar' => 'required|in:normal,disminuido,ausente',
            'simetria_toracica' => 'required|in:simetrica,asimetrica',
            'oxigenoterapia' => 'required|boolean',
            'tipo_dispositivo_o2' => 'nullable|string',
            'flujo_o2_lmin' => 'nullable|integer',
            'ventilacion' => 'required|in:espontanea,asistida,mecanica',
            'observaciones_b' => 'nullable|string',
            // C
            'pulso_frecuencia' => 'nullable|integer',
            'pulso_ritmo' => 'required|in:regular,irregular',
            'pulso_calidad' => 'required|in:fuerte,debil,filiforme',
            'hemorragias' => 'required|in:no_presenta,externas,internas,controladas',
            'llenado_capilar' => 'required|in:menor_2seg,2_4seg,mayor_4seg',
            'accesos_venosos_perifericos' => 'required|integer|min:0',
            'observaciones_c' => 'nullable|string',
            // D
            'glasgow_apertura_ocular' => 'required|integer|min:1|max:4',
            'glasgow_respuesta_verbal' => 'required|integer|min:1|max:5',
            'glasgow_respuesta_motora' => 'required|integer|min:1|max:6',
            'pupilas' => 'required|in:iguales_reactivas,iguales_no_reactivas,anisocoricas,midriaticas,mioticas',
            'escala_avdi' => 'required|in:alerta,verbal,dolor,no_responde',
            'observaciones_d' => 'nullable|string',
            // E
            'lesiones_heridas' => 'required|boolean',
            'lesiones_fracturas' => 'required|boolean',
            'lesiones_contusiones' => 'required|boolean',
            'temperatura_corporal' => 'required|in:normal,hipotermia_leve,hipotermia_severa,hipertermia',
            'quemaduras' => 'required|boolean',
            'porcentaje_scq' => 'nullable|integer|min:0|max:100',
            'observaciones_e' => 'nullable|string',
            'observaciones_generales' => 'nullable|string',
        ]);

        // Cálculo automático del total de la escala de Glasgow
        $totalGlasgow = $validated['glasgow_apertura_ocular'] 
                      + $validated['glasgow_respuesta_verbal'] 
                      + $validated['glasgow_respuesta_motora'];

        $evaluacionData = array_merge($validated, [
            'medical_record_id' => $medicalRecordId,
            'estudiante_id' => auth()->id() ?? 1,
            'total_glasgow' => $totalGlasgow,
            'porcentaje_scq' => $validated['porcentaje_scq'] ?? 0,
        ]);

        $evaluacion = EvaluacionAbcde::create($evaluacionData);

        return response()->json([
            'message' => 'Evaluación ABCDE registrada con éxito 🩺',
            'data' => $evaluacion
        ], 201);
    }

    public function show($medicalRecordId)
    {
        $evaluacion = EvaluacionAbcde::where('medical_record_id', $medicalRecordId)
            ->latest()
            ->firstOrFail();

        return response()->json($evaluacion);
    }
}
