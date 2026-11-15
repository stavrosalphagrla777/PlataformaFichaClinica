<?php
namespace App\Support;
use App\Models\MedicalRecord;
use App\Models\FichaClinica;
use Illuminate\Http\Request;
final class MedicalRecordAccess {
  public static function forWrite(Request $request, int|string $id): MedicalRecord {
    // Los IDs en /api/fichas/{id} representan fichas_clinicas. Esta tabla se enlaza
    // ahora explícitamente al registro legado medical_records requerido por triage,
    // signos vitales y ABCDE. No se asume coincidencia entre IDs de ambas tablas.
    $ficha=FichaClinica::whereKey($id)->first();
    if($ficha){
       abort_unless($ficha->user_id === $request->user()->id,403);
       abort_unless($ficha->medical_record_id,409,'Ficha anterior sin registro clínico vinculado; requiere migración de datos.');
       return MedicalRecord::where('user_id',$request->user()->id)->findOrFail($ficha->medical_record_id);
    }
    abort(404,'No existe una ficha clínica con ese identificador.');
  }
}
