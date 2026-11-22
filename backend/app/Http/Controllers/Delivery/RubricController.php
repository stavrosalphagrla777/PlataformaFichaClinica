<?php
namespace App\Http\Controllers\Delivery;

use App\Http\Controllers\Controller;
use App\Models\EvaluationRubric;
use App\Models\ClinicalCase;
use App\Support\ClinicalRubricPdf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/** Catálogo, vista previa y descarga de pautas; asignación segura por caso. */
class RubricController extends Controller
{
    private const LEVEL_SLUGS = ['tercero'=>'3.º medio','cuarto'=>'4.º medio'];

    public function index(Request $request)
    {
        $cases = ClinicalCase::query()
            ->when(!$request->user()->hasRole('administrador'), fn ($q)=>$q->where('docente_id',$request->user()->id))
            ->orderBy('codigo')->get();
        return view('docente.rubricas', [
            'rubrics'=>EvaluationRubric::with('docente')->latest()->get(),
            'cases'=>$cases,
        ]);
    }

    public function store(Request $request)
    {
        $data=$request->validate([
            'nombre'=>'required|string|max:160',
            'nivel'=>['required',Rule::in(['3.º medio','4.º medio'])],
            'titulos'=>'required|array|size:9','titulos.*'=>'required|string|max:200',
            'descripciones'=>'required|array|size:9','descripciones.*'=>'nullable|string|max:500',
        ]);
        $criteria=[];
        foreach ($data['titulos'] as $i=>$title) $criteria[]=[trim($title),trim($data['descripciones'][$i]??'')];
        $rubric=EvaluationRubric::create([
            'docente_id'=>$request->user()->id,'nombre'=>trim($data['nombre']),
            'nivel'=>$data['nivel'],'criterios'=>$criteria,
        ]);
        return redirect()->route('preview.docente.rubricas.show',$rubric)->with('ok','Pauta creada correctamente. Ya está disponible para asignar.');
    }

    public function show(EvaluationRubric $rubric)
    {
        return view('docente.rubrica-detalle',[
            'nombre'=>$rubric->nombre,'nivel'=>$rubric->nivel,
            'criterios'=>$rubric->criterios,'autor'=>$rubric->docente?->name ?? 'Docente',
            'pdfUrl'=>route('preview.docente.rubricas.pdf',$rubric),
        ]);
    }

    public function defaults(string $nivel)
    {
        abort_unless(isset(self::LEVEL_SLUGS[$nivel]),404);
        $level=self::LEVEL_SLUGS[$nivel];
        return view('docente.rubrica-detalle',[
            'nombre'=>'Pauta predeterminada - '.$level,'nivel'=>$level,
            'criterios'=>ClinicalRubricPdf::defaultCriteria($level),'autor'=>'Ficha Clínica TP',
            'pdfUrl'=>route('preview.docente.rubricas.default-pdf',$nivel),
        ]);
    }

    public function pdf(EvaluationRubric $rubric)
    {
        return $this->templatePdf($rubric->nombre,$rubric->nivel,$rubric->criterios,$rubric->docente?->name ?? 'Docente');
    }

    public function defaultPdf(string $nivel)
    {
        abort_unless(isset(self::LEVEL_SLUGS[$nivel]),404);
        $level=self::LEVEL_SLUGS[$nivel];
        return $this->templatePdf('Pauta predeterminada - '.$level,$level,ClinicalRubricPdf::defaultCriteria($level),'Ficha Clínica TP');
    }

    private function templatePdf(string $nombre,string $nivel,array $criterios,string $autor)
    {
        $content=(new ClinicalRubricPdf())->buildTemplate($nombre,$nivel,$criterios,$autor);
        $file='Pauta_'.($nivel==='3.º medio'?'3ro':'4to').'_'.substr(sha1($nombre),0,8).'.pdf';
        return response($content,200,[
            'Content-Type'=>'application/pdf',
            'Content-Disposition'=>'attachment; filename="'.$file.'"',
            'Content-Length'=>(string)strlen($content),
            'Cache-Control'=>'private, no-store, max-age=0',
            'X-Content-Type-Options'=>'nosniff',
        ]);
    }

    public function assign(Request $request, ClinicalCase $case)
    {
        abort_unless($request->user()->hasRole('administrador') || (int)$case->docente_id===(int)$request->user()->id,403);
        $data=$request->validate(['rubric_id'=>'nullable|integer|exists:evaluation_rubrics,id']);
        $rubric=isset($data['rubric_id']) ? EvaluationRubric::findOrFail($data['rubric_id']) : null;
        if($rubric && $rubric->nivel!==$case->nivel)
            throw ValidationException::withMessages(['rubric_id'=>'La pauta y el caso deben ser del mismo nivel.']);
        // Un cambio de pauta por defecto altera futuras asignaciones; nunca fichas ya iniciadas.
        if($case->assignments()->whereHas('studentCases')->exists())
            throw ValidationException::withMessages(['rubric_id'=>'Ya existen fichas iniciadas para este caso. Usa una pauta específica al crear una nueva asignación.']);
        $case->update(['rubric_id'=>$rubric?->id]);
        return back()->with('ok','Pauta del caso actualizada.');
    }
}
