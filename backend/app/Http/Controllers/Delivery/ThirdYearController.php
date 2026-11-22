<?php
namespace App\Http\Controllers\Delivery;
use App\Http\Controllers\Controller;
use App\Models\StudentCase;
use App\Models\CaseSection;
use App\Models\FichaClinica;
use App\Models\MedicalRecord;
use App\Support\ActivityAudit;
use App\Support\CaseNotifications;
use Illuminate\Support\Facades\DB;
use App\Support\ThirdYear;
use App\Support\FourthYear;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ThirdYearController extends Controller {
    private function record(Request $r,string $code,bool $start=true):StudentCase{
        $assignment=ThirdYear::assignment($r->user(),$code);
        $record=StudentCase::with('sections','assignment.clinicalCase')->where('case_assignment_id',$assignment->id)->where('estudiante_id',$r->user()->id)->first();
        if (!$record && $start && FourthYear::canStart($assignment)) {
           $record=StudentCase::firstOrCreate(['case_assignment_id'=>$assignment->id,'estudiante_id'=>$r->user()->id],['started_at'=>now(),'estado'=>'En desarrollo']);
           $record->load('sections','assignment.clinicalCase');
        }
        abort_unless($record,409,'Esta actividad no está disponible.');
        return $record;
    }
    public function show(Request $request,string $section) {
        abort_unless(isset(ThirdYear::SECTIONS[$section]),404);
        $code=(string)$request->query('caso','');
        abort_if(!$code,404,'Selecciona un caso asignado.');
        $record=$this->record($request,$code);
        $key='ficha-tp-3ro:'.$code.':'.$section;
        $saved=$record->sections->firstWhere('clave',$section);
        
        $context=[
            'codigo'=>$code,
            'section'=>$section,
            'editable'=>FourthYear::editable($record),
            'expires_at'=>($record->started_at->copy()->addMinutes($record->assignment->effectiveDuration())->lessThan($record->assignment->termino) ? $record->started_at->copy()->addMinutes($record->assignment->effectiveDuration()) : $record->assignment->termino)->toIso8601String(),
            'snapshot'=>$saved ? json_decode($saved->payload,true):null, 
            'usuario'=>$request->user()->name
        ];
        return view('estudiante.'.$section,['casoId'=>$code,'thirdYearContext'=>$context, 'segmentario'=>$record->assignment->clinicalCase->segmentario ?? []]);
    }
    
    public function save(Request $request,string $code,string $section){
        abort_unless(isset(ThirdYear::SECTIONS[$section]),404);
        $record=$this->record($request,$code);
        FourthYear::requireEditable($record);
        $data=$request->validate(['form'=>'required|array','tables'=>'nullable|array']);
        
        if ($section === 'antecedentes-3ro') {
            $motivo = trim((string)($data['form']['motivo_consulta'] ?? ''));
            if ($motivo === '') throw ValidationException::withMessages(['motivo_consulta' => 'Debes ingresar el motivo de consulta antes de guardar.']);
            foreach (['patologias'=>'patologias','alergias'=>'alergias','medicamentos'=>'medicamentos'] as $radio=>$table) {
                if (($data['form']['tiene_'.$radio] ?? '') === 'si' && !collect($data['tables'][$table] ?? [])->contains(fn ($row) => is_array($row) && collect($row)->contains(fn($value) => is_scalar($value) && trim((string)$value) !== ''))) {
                    throw ValidationException::withMessages([$table => 'Seleccionaste «Sí refiere» para '.$table.'. Añade al menos un registro o cambia la opción.']);
                }
            }

            // Las fichas de simulación se vinculan a StudentCase mediante CaseSection.
            // No crear registros clínicos externos con paciente_id fijo o inventado.
        }
        
        if ($section === 'signos-vitales-3ro') {
            $peso = (float) ($data['form']['peso'] ?? 0);
            $talla = (float) ($data['form']['talla'] ?? 0);
            $imc = $data['form']['imc'] ?? null;
            if ($peso > 0 && $talla > 0 && (!is_numeric($imc) || abs((float)$imc - $peso / (($talla / 100) ** 2)) > .11)) {
                throw ValidationException::withMessages(['imc' => 'El IMC no corresponde al cálculo de peso y talla.']);
            }
            if (!empty($data['form']['glasgow']) && (!is_numeric($data['form']['glasgow']) || (int)$data['form']['glasgow'] < 3 || (int)$data['form']['glasgow'] > 15)) {
                throw ValidationException::withMessages(['glasgow' => 'La escala de Glasgow debe estar entre 3 y 15 o dejarse vacía.']);
            }
        }
        if ($section === 'citaciones-3ro') {
            $data['form']['profesional_asignado'] = $request->user()->name;
        }
        $payload=json_encode(['form'=>$data['form'],'tables'=>$data['tables']??[]],JSON_UNESCAPED_UNICODE|JSON_THROW_ON_ERROR);
        abort_if(strlen($payload)>524288,422,'Registro demasiado extenso.');
        CaseSection::updateOrCreate(['student_case_id'=>$record->id,'clave'=>$section],
          ['storage_key'=>'ficha-tp-3ro:'.$code.':'.$section,'storage_type'=>'local','payload'=>$payload]);
        $record->touch();
        return response()->json(['message'=>'Registro guardado correctamente.']);
    }
    
    public function submit(Request $request,string $code){
        $record = $this->record($request, $code, false);
        DB::transaction(function () use ($request, $record) {
            $locked = StudentCase::with('assignment.clinicalCase')->whereKey($record->id)->lockForUpdate()->firstOrFail();
            FourthYear::requireEditable($locked);
            abort_unless($locked->sections()->where('clave', 'citaciones-3ro')->exists(), 422,
                'Guarda primero la sección final de Citaciones y próximos controles.');
            $locked->update(['estado' => 'Entregada', 'submitted_at' => now()]);
            ActivityAudit::submission($request->user(), $locked);
            CaseNotifications::submitted($locked);
        });
        return response()->json(['message'=>'Ficha entregada correctamente.']);
    }
}