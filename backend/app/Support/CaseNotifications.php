<?php
namespace App\Support;

use App\Models\CaseAssignment;
use App\Models\StudentCase;
use App\Models\User;
use App\Notifications\ClinicalAlert;

/** Avisos por eventos académicos: sin datos clínicos ni enlaces externos. */
final class CaseNotifications {
    public static function assigned(CaseAssignment $assignment): void {
        if ($assignment->estado === 'Inactiva') return;
        $assignment->loadMissing('clinicalCase');
        User::role('estudiante')->where('activo',true)->whereNotNull('curso')->get()
            ->filter(fn(User $student)=>
                ($assignment->nivel === '3.º medio' ? ThirdYear::isThirdYear($student) : FourthYear::isFourthYear($student))
                && CourseAccess::matches($student->curso,$assignment->curso))
            ->each(fn(User $student)=>$student->notify(new ClinicalAlert(
                'Caso clínico asignado',
                'Se asignó el caso '.$assignment->clinicalCase->codigo.' a tu curso.',
                route('preview.estudiante.caso', ['id'=>$assignment->clinicalCase->codigo])
            )));
    }
    public static function submitted(StudentCase $record): void {
        $record->loadMissing('assignment.clinicalCase','estudiante');
        $users=User::whereIn('id',array_unique(array_merge([$record->assignment->docente_id,$record->assignment->clinicalCase->docente_id],User::role('administrador')->pluck('id')->all())))->get();
        foreach($users as $user) $user->notify(new ClinicalAlert(
            'Ficha entregada para revisión',
            $record->estudiante->name.' entregó el caso '.$record->assignment->clinicalCase->codigo.'.',
            route('preview.docente.evaluaciones',['ficha'=>$record->id])
        ));
    }
    public static function evaluated(StudentCase $record): void {
        $record->loadMissing('assignment.clinicalCase','estudiante');
        $record->estudiante->notify(new ClinicalAlert(
            'Ficha evaluada',
            'Tu ficha del caso '.$record->assignment->clinicalCase->codigo.' ya tiene una evaluación disponible.',
            route('preview.estudiante.caso',['id'=>$record->assignment->clinicalCase->codigo])
        ));
    }
}
