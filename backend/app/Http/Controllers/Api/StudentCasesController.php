<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Models\StudentCase;
use App\Support\ThirdYear;
use App\Support\FourthYear;
use Illuminate\Http\Request;
/**
 * Consulta API de casos educativos asignados al estudiante autenticado.
 * No modifica ni entrega fichas; el flujo principal se encuentra en Delivery.
 */
class StudentCasesController extends Controller {
    /** Verifica que el estudiante pueda consultar el caso. */
    private function access(Request $request){
        abort_unless($request->user()->hasRole('estudiante'),403);
        return ThirdYear::isThirdYear($request->user()) ? ThirdYear::assignmentsFor($request->user()) : FourthYear::assignmentsFor($request->user());
    }
    /** Lista casos y asignaciones de la cuenta autenticada. */
    public function index(Request $request){
        $assignments=$this->access($request)->orderByDesc('inicio')->get();
        $ids=$assignments->pluck('id');
        $records=StudentCase::where('estudiante_id',$request->user()->id)->whereIn('case_assignment_id',$ids)->with('evaluation')->get()->keyBy('case_assignment_id');
        return response()->json(['data'=>$assignments->map(function($a)use($records){
            $r=$records->get($a->id);
            return ['codigo'=>$a->clinicalCase->codigo,'titulo'=>$a->clinicalCase->titulo,'nivel'=>$a->nivel,
             'curso'=>$a->curso,'inicio'=>$a->inicio,'termino'=>$a->termino,'estado'=>FourthYear::status($r,$a),
             'ficha_id'=>$r?->id];
        })]);
    }
    /** Devuelve información de un caso asignado. */
    public function show(Request $request,string $code){
        $a=$this->access($request)->whereHas('clinicalCase',fn($q)=>$q->where('codigo',$code))->firstOrFail();
        abort_unless(FourthYear::matchesCourse($request->user()->curso,$a->curso),403);
        $record=StudentCase::with('sections','evaluation')->where('case_assignment_id',$a->id)->where('estudiante_id',$request->user()->id)->first();
        return response()->json(['data'=>['caso'=>$a->clinicalCase,'asignacion'=>$a,
           'estado'=>FourthYear::status($record,$a),'ficha'=>$record]]);
    }
}
