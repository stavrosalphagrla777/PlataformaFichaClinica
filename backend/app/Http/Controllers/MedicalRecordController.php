<?php

namespace App\Http\Controllers;

use App\Models\MedicalRecord;
use App\Http\Requests\StoreMedicalRecordRequest;
use Illuminate\Http\Request;

class MedicalRecordController extends Controller
{
    /**
     * Obtener el listado de fichas clínicas con su paciente
     */
    public function index()
    {
        $records = MedicalRecord::with('patient')->latest()->get();

        return response()->json($records, 200);
    }

    /**
     * Guardar una nueva ficha clínica
     */
    public function store(StoreMedicalRecordRequest $request)
    {
        $record = MedicalRecord::create($request->validated());

        return response()->json([
            'message' => 'Ficha clínica creada con éxito',
            'data' => $record->load('patient')
        ], 201);
    }

    /**
     * Consultar una ficha clínica específica
     */
    public function show(MedicalRecord $medicalRecord)
    {
        return response()->json($medicalRecord->load('patient'), 200);
    }

    /**
     * Actualizar una ficha clínica
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $medicalRecord->update($request->all());

        return response()->json([
            'message' => 'Ficha clínica actualizada con éxito',
            'data' => $medicalRecord->load('patient')
        ], 200);
    }

    /**
     * Eliminar una ficha clínica
     */
    public function destroy(MedicalRecord $medicalRecord)
    {
        $medicalRecord->delete();

        return response()->json([
            'message' => 'Ficha clínica eliminada con éxito'
        ], 200);
    }
}
