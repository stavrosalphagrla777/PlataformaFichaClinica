<?php

namespace App\Http\Controllers;

use App\Models\SignosVitales;
use Illuminate\Http\Request;

class SignosVitalesController extends Controller
{
    public function store(Request $request, $medicalRecordId)
    {
        $validated = $request->validate([
            'frecuencia_cardiaca' => 'required|integer',
            'presion_arterial' => 'required|string',
            'frecuencia_respiratoria' => 'required|integer',
            'temperatura' => 'required|numeric',
            'saturacion_oxigeno' => 'required|integer',
            'glicemia_hgt' => 'nullable|integer',
            'peso' => 'nullable|numeric',
            'talla' => 'nullable|numeric', // en metros, ej: 1.70
            'observaciones' => 'nullable|string',
        ]);

        // 1. Cálculo de IMC si hay peso y talla
        $imc = null;
        $clasificacionImc = null;
        if (!empty($validated['peso']) && !empty($validated['talla']) && $validated['talla'] > 0) {
            $imc = round($validated['peso'] / ($validated['talla'] * $validated['talla']), 1);
            
            if ($imc < 18.5) {
                $clasificacionImc = 'Bajo peso';
            } elseif ($imc < 25.0) {
                $clasificacionImc = 'Normopeso';
            } elseif ($imc < 30.0) {
                $clasificacionImc = 'Sobrepeso';
            } else {
                $clasificacionImc = 'Obesidad';
            }
        }

        // 2. Determinación de estado hemodinámico básico
        $estado = 'normal';
        if ($validated['saturacion_oxigeno'] < 90 || $validated['frecuencia_cardiaca'] > 130 || $validated['frecuencia_cardiaca'] < 40) {
            $estado = 'critico';
        } elseif ($validated['saturacion_oxigeno'] < 95 || $validated['frecuencia_cardiaca'] > 100 || $validated['frecuencia_cardiaca'] < 60) {
            $estado = 'alerta';
        }

        $signo = SignosVitales::create([
            'medical_record_id' => $medicalRecordId,
<<<<<<< HEAD
            'registrado_por_id' => auth()->id(),
=======
            'registrado_por_id' => auth()->id() ?? 1,
>>>>>>> 6b61ff51a6ef315dbc9e8dc7c1fb0ae7e86644ff
            'fecha_hora_registro' => now(),
            'frecuencia_cardiaca' => $validated['frecuencia_cardiaca'],
            'presion_arterial' => $validated['presion_arterial'],
            'frecuencia_respiratoria' => $validated['frecuencia_respiratoria'],
            'temperatura' => $validated['temperatura'],
            'saturacion_oxigeno' => $validated['saturacion_oxigeno'],
            'glicemia_hgt' => $validated['glicemia_hgt'] ?? null,
            'peso' => $validated['peso'] ?? null,
            'talla' => $validated['talla'] ?? null,
            'imc' => $imc,
            'clasificacion_imc' => $clasificacionImc,
            'estado_hemodinamico' => $estado,
            'observaciones' => $validated['observaciones'] ?? null,
        ]);

        return response()->json([
            'message' => 'Signos vitales registrados con éxito 📊',
            'data' => $signo
        ], 201);
    }

    public function index($medicalRecordId)
    {
        $signos = SignosVitales::where('medical_record_id', $medicalRecordId)
            ->orderBy('fecha_hora_registro', 'desc')
            ->get();

        return response()->json($signos);
    }
}