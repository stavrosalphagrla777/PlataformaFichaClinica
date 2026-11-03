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
            'interpretacion_clinica' => 'nullable|string',
        ]);

                // Verificamos el nivel del estudiante
        $estudiante = $request->user();
        $esTerceroMedio = $estudiante && $estudiante->nivel_educativo === '3_medio';

        $imc = null;
        $clasificacionImc = null;
        $estado = null;
        $clasificacionFinal = null;

        if ($esTerceroMedio) {
            // Para 3.º medio: El estudiante escribe su propia interpretación clínica
            $clasificacionFinal = $validated['interpretacion_clinica'] ?? 'Sin interpretación registrada';
        } else {
            // Para 4.º medio: Se mantiene el cálculo automático (como ya lo tenías)
            
            // 1. Cálculo de IMC
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

            // 2. Determinación de estado hemodinámico
            $estado = 'normal';
            if ($validated['saturacion_oxigeno'] < 90 || $validated['frecuencia_cardiaca'] > 130 || $validated['frecuencia_cardiaca'] < 40) {
                $estado = 'critico';
            } elseif ($validated['saturacion_oxigeno'] < 95 || $validated['frecuencia_cardiaca'] > 100 || $validated['frecuencia_cardiaca'] < 60) {
                $estado = 'alerta';
            }
        }

        // Guardamos en la base de datos
        $signo = SignosVitales::create([
            'medical_record_id' => $medicalRecordId,
            'registrado_por_id' => auth()->id() ?? 1,
            'fecha_hora_registro' => now(),
            'frecuencia_cardiaca' => $validated['frecuencia_cardiaca'],
            'presion_arterial' => $validated['presion_arterial'],
            'frecuencia_respiratoria' => $validated['frecuencia_respiratoria'],
            'temperatura' => $validated['temperatura'],
            'saturacion_oxigeno' => $validated['saturacion_oxigeno'],
            'glicemia_hgt' => $validated['glicemia_hgt'] ?? null,
            'peso' => $validated['peso'] ?? null,
            'talla' => $validated['talla'] ?? null,
            'imc' => $esTerceroMedio ? null : $imc, // En 3.º medio no se calcula
            'clasificacion_imc' => $esTerceroMedio ? $clasificacionFinal : $clasificacionImc, // Usa el texto en 3.º, el cálculo en 4.º
            'estado_hemodinamico' => $esTerceroMedio ? null : $estado, // En 3.º medio no se calcula
            'observaciones' => $validated['observaciones'] ?? null,
            'interpretacion_clinica' => $validated['interpretacion_clinica'] ?? null,
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
