<?php

namespace App\Support;

use App\Models\StudentCase;

/** Generación PDF A4 independiente de librerías externas; sin dependencias Composer. */
final class ClinicalRubricPdf
{
    private const W = 595.28;
    private const H = 841.89;
    private array $pages = [];
    private array $ops = [];
    private int $page = 0;
    private float $y = 0;

    /** Criterios tomados de las pautas oficiales entregadas para este proyecto. */
    private const URGENCY = [
        ['Admisión', 'Registra de forma completa los datos del paciente, previsión de salud, contacto de emergencia y medicamentos habituales.'],
        ['Categorización Triage', 'Determina el motivo de consulta, asigna la prioridad ESI (ESI 1 al 5) y registra la escala de dolor EVA.'],
        ['Evaluación ABCDE', 'Completa la secuencia de evaluación primaria de urgencias (Vía aérea, Ventilación, Circulación, Neurológico/Glasgow y Exposición).'],
        ['Anamnesis y Examen Físico', 'Redacta la enfermedad actual y realiza el examen físico general orientado al motivo de urgencia.'],
        ['Procedimientos TENS', 'Documenta correctamente los procedimientos clínicos realizados (ej. toma de ECG, instalación de VVP, Glicemia) especificando hora y responsable.'],
        ['Administración de Medicamentos', 'Registra los fármacos administrados indicando medicamento, dosis, vía de administración, hora y reacciones observadas.'],
        ['Revisión de Exámenes', 'Consulta las imágenes/exámenes adjuntos y verifica que cuenten con fecha y hora correspondientes.'],
        ['Acciones de Enfermería', 'Registra el seguimiento cronológico del paciente bajo la denominación «Acciones de enfermería».'],
        ['Destino y Cierre', 'Selecciona el destino del paciente (Alta, Observación, Derivación, etc.), escribe la justificación clínica y finaliza el caso en el tiempo establecido.'],
    ];

    private const CESFAM = [
        ['Registro y Antecedentes', 'Ingresa o verifica los antecedentes del paciente, identificando patologías crónicas (AUGE/GES) y alertas de alergias.'],
        ['Signos Vitales y Antropometría', 'Registra todos los parámetros (PA, FC, FR, Temp, SatO2, Peso, Talla, IMC) sin omitir campos.'],
        ['Interpretación de Signos Vitales', 'Escribe la interpretación clínica de los parámetros ingresados en el cuadro de texto libre correspondiente.'],
        ['Examen Físico General', 'Completa la valoración general (conciencia, facies, marcha, EVA) y los hallazgos por segmentos según la opción predeterminada.'],
        ['Prestaciones / Acciones TENS', 'Documenta las intervenciones o atenciones de enfermería brindadas durante la consulta de salud.'],
        ['Educación al Paciente (Texto)', 'Redacta de forma clara y oportuna las indicaciones de educación entregadas al paciente en la sección correspondiente.'],
        ['Confirmación de Educación', 'Marca la casilla de verificación para validar que la educación fue entregada al paciente antes del cierre.'],
        ['Citaciones y Próximos Controles', 'Registra la fecha/hora del próximo control, periodicidad, profesional asignado e indicaciones de seguimiento.'],
        ['Cierre y Cumplimiento', 'Completa la atención sin dejar campos obligatorios vacíos y respetando el plazo asignado por la plataforma.'],
    ];

    private function op(string $instruction): void { $this->ops[] = $instruction; }
    private static function number(float $n): string { return number_format($n, 2, '.', ''); }
    private static function encoded(string $s): string
    {
        $s = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/u', ' ', $s) ?? $s;
        $converted = iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $s);
        return str_replace(['\\','(',')',"\r","\n"], ['\\\\','\\(','\\)',' ',' '], $converted === false ? '' : $converted);
    }
    private function text(float $x, float $top, string $s, int $size = 9, bool $bold = false, array $color = [28,47,65]): void
    {
        $rgb = implode(' ', array_map(fn ($v) => self::number($v / 255), $color));
        $this->op(sprintf('BT /F%d %d Tf %s rg 1 0 0 1 %s %s Tm (%s) Tj ET',
            $bold ? 2 : 1, $size, $rgb, self::number($x), self::number(self::H - $top - $size), self::encoded($s)));
    }
    private function box(float $x, float $top, float $w, float $h, array $rgb = [255,255,255]): void
    {
        $c=implode(' ',array_map(fn ($v)=>self::number($v/255),$rgb));
        $this->op($c.' rg '.self::number($x).' '.self::number(self::H-$top-$h).' '.self::number($w).' '.self::number($h).' re f');
    }
    private function line(float $x1,float $y1,float $x2,float $y2, array $rgb = [204,219,231]): void
    {
        $c=implode(' ',array_map(fn ($v)=>self::number($v/255),$rgb));
        $this->op($c.' RG 0.65 w '.self::number($x1).' '.self::number(self::H-$y1).' m '.self::number($x2).' '.self::number(self::H-$y2).' l S');
    }
    private static function length(string $value): int
    {
        return function_exists('mb_strlen') ? mb_strlen($value) : iconv_strlen($value, 'UTF-8');
    }
    private static function slice(string $value, int $start, ?int $length = null): string
    {
        return function_exists('mb_substr') ? mb_substr($value, $start, $length) : iconv_substr($value, $start, $length, 'UTF-8');
    }
    private function wrapped(string $s, float $w, int $size = 9): array
    {
        $s=trim(preg_replace('/\s+/u',' ',str_replace(["\r","\n"],' ',$s)) ?? $s);
        if ($s === '') return ['—'];
        $charLimit=max(8,(int)floor($w/($size*0.48)));
        $result=[];$buffer='';
        foreach (preg_split('/\s+/u',$s) ?: [] as $word) {
            $word=(string)$word;
            if (self::length($word) > $charLimit) {
                if ($buffer !== '') {$result[]=$buffer;$buffer='';}
                while(self::length($word)>$charLimit){$result[]=self::slice($word,0,$charLimit);$word=self::slice($word,$charLimit);}
            }
            $test=$buffer === '' ? $word : $buffer.' '.$word;
            if (self::length($test)>$charLimit && $buffer!=='') {$result[]=$buffer;$buffer=$word;} else $buffer=$test;
        }
        if ($buffer !== '') $result[]=$buffer;
        return $result ?: ['—'];
    }
    private function multiline(float $x, float $top, array $lines, int $size = 9, float $height = 12, bool $bold = false): void
    {
        foreach ($lines as $i=>$line) $this->text($x,$top+$i*$height,(string)$line,$size,$bold);
    }
    private function newPage(bool $continued = false): void
    {
        if ($this->ops) $this->pages[] = implode("\n",$this->ops)."\n";
        $this->ops=[];$this->page++;
        $this->box(0,0,self::W,13,[22,86,134]);
        $this->text(36,23,'FICHA CLÍNICA TP  |  PAUTA DE COTEJO',11,true,[16,75,119]);
        $this->text(487,26,'Página '.$this->page,8,false,[76,99,119]);
        $this->line(36,44,559,44);
        $this->y=$continued?56:58;
        if ($continued) {
            $this->text(36,$this->y,'Continuación del informe de evaluación',11,true);
            $this->y+=25;
        }
    }
    private function ensure(float $height): void { if ($this->y + $height > 785) $this->newPage(true); }
    private function tableHead(): void
    {
        $this->ensure(30);
        $this->box(36,$this->y,523,26,[21,78,121]);
        $cols=[[45,'N.º'],[72,'Criterio de registro y protocolo'],[331,'Sí'],[361,'No'],[396,'Observaciones del evaluador']];
        foreach($cols as [$x,$txt]) $this->text($x,$this->y+7,$txt,8,true,[255,255,255]);
        $this->y+=27;
    }
    private function criteria(array $criteria, array $answers, array $notes): void
    {
        $this->tableHead();
        foreach ($criteria as $i=>[$title,$description]) {
            $answer=$answers[$i]??'pendiente';
            $observation=trim((string)($notes[$i]??''));
            $left=$this->wrapped($title.': '.$description,252,8);
            $right=$this->wrapped($observation === '' ? 'Sin observación registrada.' : $observation,155,8);
            $height=max(35, max(count($left),count($right))*12+12);
            if ($this->y + $height > 785) $this->tableHeadOnNewPage();
            if ($i % 2) $this->box(36,$this->y,523,$height,[246,250,254]);
            $this->line(36,$this->y,559,$this->y);$this->line(36,$this->y+$height,559,$this->y+$height);
            foreach([36,64,324,355,389,559] as $x)$this->line($x,$this->y,$x,$this->y+$height);
            $this->text(46,$this->y+8,(string)($i+1),9,true);
            $this->multiline(71,$this->y+8,$left,8,12);
            $this->text(337,$this->y+8,$answer==='si'?'X':'—',9,$answer==='si');
            $this->text(367,$this->y+8,$answer==='no'?'X':'—',9,$answer==='no');
            $this->multiline(397,$this->y+8,$right,8,12);
            $this->y+=$height;
        }
        $this->y+=15;
    }
    private function tableHeadOnNewPage(): void { $this->newPage(true);$this->tableHead(); }
    private function info(string $name,string $value): void
    {
        $this->ensure(22);
        $this->text(38,$this->y,$name,9,true,[38,71,98]);
        $this->text(200,$this->y,$value ?: 'No informado',9);
        $this->y+=19;
    }
    public function build(StudentCase $record, string $teacher): string
    {
        $eval=$record->evaluation;
        $third=$record->assignment->nivel === '3.º medio';
        $criteria=$third ? self::CESFAM : self::URGENCY;
        $answers= array_is_list($eval->criterios ?? []) ? ($eval->criterios ?? []) : [];
        $notes= array_is_list($eval->notas ?? []) ? ($eval->notas ?? []) : [];
        $this->newPage();
        $this->text(36,$this->y,$third ? 'ATENCIÓN PRIMARIA (CESFAM)' : 'SERVICIO DE URGENCIAS',15,true,[14,74,118]);$this->y+=25;
        $this->info('Nombre del estudiante',$record->estudiante->name);
        $this->info('RUT','No registrado en la plataforma');
        $this->info('Nivel / Curso',$record->assignment->nivel.' / '.($record->estudiante->curso ?: 'Sin curso'));
        $this->info('Caso clínico asignado',$record->assignment->clinicalCase->codigo.' · '.$record->assignment->clinicalCase->titulo);
        $this->info('Nombre del docente evaluador',$teacher);
        $this->info('Fecha de evaluación',$eval->finalizada_at?->format('d/m/Y H:i') ?? $eval->updated_at->format('d/m/Y H:i'));
        $this->y+=5;
        $this->box(36,$this->y,523,44,[235,245,253]);
        $this->text(45,$this->y+6,'ESCALA DE RENDIMIENTO',9,true,[18,73,114]);
        $this->text(45,$this->y+22,'20% = Nota 2,0     |     60% = Nota 4,0 (aprobación)     |     100% = Nota 7,0',9,false,[28,58,85]);
        $this->y+=57;
        $this->text(36,$this->y,'CRITERIOS DE EVALUACIÓN',11,true,[20,71,109]);$this->y+=22;
        $this->criteria($criteria,$answers,$notes);
        $yes=count(array_filter($answers,fn($s)=>$s==='si'));
        $this->ensure(120);
        $this->box(36,$this->y,523,92,[236,245,252]);
        $this->text(47,$this->y+9,'RESUMEN DE EVALUACIÓN',11,true,[15,74,118]);
        $this->text(47,$this->y+30,'Criterios cumplidos: '.($answers ? ($yes.' / 9') : 'No desglosado (modalidad anterior)'),9,true);
        $this->text(47,$this->y+48,'Porcentaje de logro: '.($eval->porcentaje===null ? 'No registrado' : number_format((float)$eval->porcentaje,1,',','.').'%'),9,true);
        $this->text(315,$this->y+48,'Nota: '.($eval->nota===null ? 'No registrada' : number_format((float)$eval->nota,1,',','.')),10,true);
        $this->text(47,$this->y+66,'Resultado: '.($eval->porcentaje===null ? 'Sin resultado' : ((float)$eval->porcentaje >= 60 ? 'APROBADO' : 'REPROBADO')),9,true);
        $this->y+=105;
        $this->ensure(40);
        $this->text(36,$this->y,'COMENTARIOS Y RETROALIMENTACIÓN DEL DOCENTE',10,true,[15,74,118]);$this->y+=21;
        $general=$this->wrapped(trim((string)$eval->retroalimentacion) ?: 'Sin comentarios generales.',515,9);
        foreach ($general as $line) {$this->ensure(16);$this->text(39,$this->y,$line,9);$this->y+=15;}
        $this->y+=42;$this->ensure(50);
        $this->line(42,$this->y,268,$this->y);$this->line(320,$this->y,549,$this->y);
        $this->text(78,$this->y+6,'Firma del estudiante',9);$this->text(367,$this->y+6,'Firma del docente',9);
        $this->y+=34;
        $this->text(36,$this->y,'Documento generado por Ficha Clínica TP. Firmas no incorporadas digitalmente.',7,false,[89,108,126]);
        $this->pages[]=implode("\n",$this->ops)."\n";
        $this->ops=[];
        return $this->serialize();
    }
    private function serialize(): string
    {
        $objects=[''];
        $add=static function (string $value) use (&$objects): int {$objects[]=$value;return count($objects)-1;};
        $root=$add('');$pagesRoot=$add('');
        $font=$add('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>');
        $bold=$add('<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>');
        $pageIds=[];
        foreach ($this->pages as $stream) {
            $contents=$add('<< /Length '.strlen($stream).' >>'."\nstream\n".$stream."endstream");
            $pageIds[]=$add('<< /Type /Page /Parent '.$pagesRoot.' 0 R /MediaBox [0 0 '.self::number(self::W).' '.self::number(self::H).'] /Resources << /Font << /F1 '.$font.' 0 R /F2 '.$bold.' 0 R >> >> /Contents '.$contents.' 0 R >>');
        }
        $objects[$root]='<< /Type /Catalog /Pages '.$pagesRoot.' 0 R >>';
        $objects[$pagesRoot]='<< /Type /Pages /Kids ['.implode(' ',array_map(fn($n)=>$n.' 0 R',$pageIds)).'] /Count '.count($pageIds).' >>';
        $pdf="%PDF-1.4\n%\xE2\xE3\xCF\xD3\n";$offsets=[0];
        for($n=1;$n<count($objects);$n++){$offsets[$n]=strlen($pdf);$pdf.=$n." 0 obj\n".$objects[$n]."\nendobj\n";}
        $xref=strlen($pdf);$pdf.="xref\n0 ".count($objects)."\n0000000000 65535 f \n";
        for($n=1;$n<count($objects);$n++)$pdf.=sprintf('%010d 00000 n ', $offsets[$n])."\n";
        return $pdf."trailer\n<< /Size ".count($objects)." /Root {$root} 0 R >>\nstartxref\n{$xref}\n%%EOF\n";
    }
}
