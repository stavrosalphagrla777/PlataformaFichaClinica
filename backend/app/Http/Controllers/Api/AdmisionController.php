<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\FichaClinica;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
/**
 * API clínica heredada: registro de admisión en ficha con comprobaciones de acceso.
 * No confundir esta ruta con las secciones JSON del flujo educativo web.
 */
class AdmisionController extends Controller {
    /** Valida y registra datos iniciales de ficha clínica. */
    public function store(Request $request){
        abort_unless($request->user()->hasRole('estudiante'),403);
        $data=$request->validate([
           'paciente_id'=>'nullable|integer|min:1',
           'motivo_consulta'=>'required|string|max:4000',
        ]);
        $ficha=DB::transaction(function()use($request,$data){
            // medical_records.patient_id tiene FK hacia users en el esquema heredado.
            // El paciente de simulación NO es una cuenta de usuario real; asociamos
            // esta relación técnica al estudiante propietario y guardamos el ID ficticio
            // del paciente separadamente en fichas_clinicas.paciente_id.
            $record=MedicalRecord::create([
                'user_id'=>$request->user()->id,
                'patient_id'=>$request->user()->id,
                'esi_triage'=>5,
            ]);
            return FichaClinica::create([
                'user_id'=>$request->user()->id,
                'medical_record_id'=>$record->id,
                'paciente_id'=>$data['paciente_id']??null,
                'motivo_consulta'=>$data['motivo_consulta'],
                'estado'=>'en_espera',
            ]);
        });
        return response()->json(['data'=>$ficha],201);
    }
}
