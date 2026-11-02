<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FichaClinica;
use App\Models\Triage;
use Illuminate\Http\Request;

class TriageController extends Controller
{
    public function store(Request $request, $fichaId)
    {
        // Verificar que la ficha existe
        $ficha = FichaClinica::findOrFail($fichaId);

        $validated = $request->validate([
            'motivo_consulta' => 'required|string',
            'prioridad_esi' => 'required|integer|min:1|max:5',
            'escala_eva' => 'required|integer|min:0|max:10',
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

        $tiemposEsi = [1 => 0, 2 => 10, 3 => 30, 4 => 60, 5 => 120];
        $nivelesEsi = [
            1 => 'CRÍTICO / ALTA', 2 => 'EMERGENCIA / ALTA', 3 => 'URGENCIA / MEDIA',
            4 => 'MENOR URGENCIA / BAJA', 5 => 'NO URGENTE / BAJA',
        ];

        $prioridadEsi = $validated['prioridad_esi'];

        $triage = Triage::create([
            'ficha_clinica_id' => $ficha->id, // Usamos la nueva relación
            'estudiante_id' => $request->user()->id,
            'motivo_consulta' => $validated['motivo_consulta'],
            'prioridad_esi' => $prioridadEsi,
            'escala_eva' => $validated['escala_eva'],
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
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        return response()->json([
            'message' => 'Triage registrado exitosamente 🚨',
            'data' => $triage
        ], 201);
    }

    public function show($fichaId)
    {
        $triage = Triage::where('ficha_clinica_id', $fichaId)->firstOrFail();
        return response()->json($triage);
    }
}