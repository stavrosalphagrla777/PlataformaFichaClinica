<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Triage;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;

class TriageController extends Controller
{
    public function store(Request $request, $medicalRecordId)
    {
        $validated = $request->validate([
            'motivo_consulta' => 'required|string',
            'prioridad_esi' => 'required|integer|min:1|max:5',
            'escala_eva' => 'nullable|integer|min:0|max:10',
            'frecuencia_cardiaca' => 'required|integer',
            'presion_arterial' => 'required|string',
            'frecuencia_respiratoria' => 'required|integer',
            'saturacion_oxigeno' => 'required|integer',
            'temperatura' => 'required|numeric',
            'escala_glasgow' => 'nullable|integer|min:3|max:15',
            'peso' => 'nullable|numeric',
            'talla' => 'nullable|integer',
            'observaciones' => 'nullable|string',
        ]);

        // Cálculo automático según nivel ESI
        $tiemposEsi = [
            1 => 0,    // ESI 1: Inmediato (Reanimación)
            2 => 10,   // ESI 2: 10 minutos (Emergencia)
            3 => 30,   // ESI 3: 30 minutos (Urgencia)
            4 => 60,   // ESI 4: 60 minutos (Menor urgencia)
            5 => 120,  // ESI 5: 120 minutos (No urgente)
        ];

        $nivelesEsi = [
            1 => 'CRÍTICO / ALTA',
            2 => 'EMERGENCIA / ALTA',
            3 => 'URGENCIA / MEDIA',
            4 => 'MENOR URGENCIA / BAJA',
            5 => 'NO URGENTE / BAJA',
        ];

        $prioridadEsi = $validated['prioridad_esi'];

        // 🚀 ASIGNACIÓN AUTOMÁTICA DE BOX
        $boxAsignado = $this->asignarBox($prioridadEsi);

        $triage = Triage::create([
            'medical_record_id' => $medicalRecordId,
            'estudiante_id' => auth()->id() ?? 1,
            'motivo_consulta' => $validated['motivo_consulta'],
            'prioridad_esi' => $prioridadEsi,
            'escala_eva' => $validated['escala_eva'] ?? null,
            'frecuencia_cardiaca' => $validated['frecuencia_cardiaca'],
            'presion_arterial' => $validated['presion_arterial'],
            'frecuencia_respiratoria' => $validated['frecuencia_respiratoria'],
            'saturacion_oxigeno' => $validated['saturacion_oxigeno'],
            'temperatura' => $validated['temperatura'],
            'escala_glasgow' => $validated['escala_glasgow'] ?? 15,
            'peso' => $validated['peso'] ?? null,
            'talla' => $validated['talla'] ?? null,
            'nivel_prioridad_calculado' => $nivelesEsi[$prioridadEsi],
            'tiempo_maximo_espera_min' => $tiemposEsi[$prioridadEsi],
            'box' => $boxAsignado,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        return response()->json([
            'message' => 'Triage registrado exitosamente 🚨',
            'data' => $triage,
            'box_asignado' => $boxAsignado
        ], 201);
    }

    /**
     * Asigna un Box automáticamente según la prioridad ESI.
     * Si el Box preferido está ocupado, busca el siguiente disponible.
     */
    private function asignarBox($prioridadEsi)
    {
        // Mapeo de ESI a Box preferido
        $boxPreferido = [
            1 => 'Box 1',
            2 => 'Box 2',
            3 => 'Box 3',
            4 => 'Box 4',
            5 => 'Box 5',
        ];

        $boxInicial = $boxPreferido[$prioridadEsi];
        
        // Buscar si el Box está ocupado (triages activos)
        $boxesOcupados = Triage::whereNotNull('box')
            ->pluck('box')
            ->toArray();

        // Si el Box preferido está libre, asignarlo
        if (!in_array($boxInicial, $boxesOcupados)) {
            return $boxInicial;
        }

        // Si está ocupado, buscar el siguiente Box disponible (Box 1 a Box 5)
        for ($i = 1; $i <= 5; $i++) {
            $box = 'Box ' . $i;
            if (!in_array($box, $boxesOcupados)) {
                return $box;
            }
        }

        // Si todos los Boxes están ocupados, asignar "Sin Box"
        return 'Sin Box';
    }

    public function show($medicalRecordId)
    {
        $triage = Triage::where('medical_record_id', $medicalRecordId)->firstOrFail();
        return response()->json($triage);
    }
}