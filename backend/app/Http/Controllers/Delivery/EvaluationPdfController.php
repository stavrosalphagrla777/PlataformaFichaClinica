<?php

namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\StudentCase;
use App\Models\User;
use App\Support\ClinicalRubricPdf;
use Illuminate\Http\Request;

/**
 * Descarga de pautas PDF finalizadas según la relación del usuario con la ficha.
 * Restringe el acceso por rol/autoría/asignación y genera el archivo sin exponer borradores.
 */
class EvaluationPdfController extends Controller
{
    /** Acceso exclusivo a la evaluación finalizada de la ficha autorizada. */
    /** Valida acceso y evaluación finalizada y entrega la pauta en PDF privado. */
    public function download(Request $request, StudentCase $record)
    {
        $record->load(['assignment.clinicalCase', 'estudiante', 'evaluation']);
        $user=$request->user();
        $roleAdmin=$user->hasRole('administrador');
        $roleDocente=$user->hasRole('docente');
        $roleEstudiante=$user->hasRole('estudiante');
        $assigned=$record->assignment;
        abort_unless($roleAdmin || ($roleDocente && (
            (int)$assigned->docente_id === (int)$user->id ||
            (int)$assigned->clinicalCase->docente_id === (int)$user->id
        )) || ($roleEstudiante && (int)$record->estudiante_id === (int)$user->id), 403);
        abort_unless($record->evaluation && $record->evaluation->estado === 'finalizada', 404, 'Esta evaluación aún no está finalizada.');
        $evaluator=User::find($record->evaluation->docente_id)?->name ?: 'No identificado';
        $content=(new ClinicalRubricPdf())->build($record,$evaluator);
        $code=preg_replace('/[^a-zA-Z0-9_-]/','_', $assigned->clinicalCase->codigo);
        $filename='Pauta_Evaluacion_'.$code.'_Ficha_'.$record->id.'.pdf';
        return response($content,200,[
            'Content-Type'=>'application/pdf',
            'Content-Disposition'=>'attachment; filename="'.$filename.'"',
            'Content-Length'=>(string)strlen($content),
            'Cache-Control'=>'private, no-store, max-age=0',
            'X-Content-Type-Options'=>'nosniff',
        ]);
    }
}
