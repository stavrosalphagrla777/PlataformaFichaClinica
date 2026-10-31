<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FichaClinica;
use Illuminate\Http\Request;

class AdmisionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'paciente_id' => 'required|integer',
            'motivo_consulta' => 'required|string',
        ]);

        $ficha = FichaClinica::create([
            'user_id' => $request->user()->id,
            'paciente_id' => $request->paciente_id,
            'motivo_consulta' => $request->motivo_consulta,
            'estado' => 'en_espera',
        ]);

        return response()->json(['data' => $ficha], 201);
    }
}
