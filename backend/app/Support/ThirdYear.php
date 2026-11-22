<?php
namespace App\Support;
use App\Models\CaseAssignment;
use App\Models\User;
use App\Models\StudentCase;
use App\Support\CourseAccess;
final class ThirdYear {
    public const SECTIONS=[
      'antecedentes-3ro'=>['Antecedentes CESFAM'],
      'signos-vitales-3ro'=>['Signos vitales CESFAM'],
      'examen-fisico-3ro'=>['Examen físico CESFAM'],
      'citaciones-3ro'=>['Citaciones y seguimiento CESFAM'],
    ];
    public static function isThirdYear(User $user):bool {return preg_match('/3/',(string)$user->nivel_educativo)===1;}
    public static function assignmentsFor(User $user){
        if(!self::isThirdYear($user))return CaseAssignment::query()->whereRaw('1=0');
        return CaseAssignment::with('clinicalCase')->where('nivel','3.º medio')
           ->whereIn('estado',['Activa','Programada'])
           ->whereHas('clinicalCase',fn($q)=>$q->where('nivel','3.º medio')->where('estado','Publicado'))
           ->where(function($q)use($user){
               $q->whereIn('curso',CourseAccess::assignmentCourseCandidates($user->curso));
               if(!CourseAccess::isEvening($user->curso))$q->orWhere('curso','Todos los cursos');
           });
    }
    public static function assignment(User $user,string $code):CaseAssignment{
        $a=self::assignmentsFor($user)->whereHas('clinicalCase',fn($q)=>$q->where('codigo',$code))->orderByDesc('inicio')->firstOrFail();
        abort_unless(FourthYear::matchesCourse($user->curso,$a->curso),403);
        return $a;
    }
}
