<?php
namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Support\ClinicalRubricPdf;

class RubricPdfTemplateTest extends TestCase
{
    public function test_both_default_templates_render_valid_pdf_structure(): void
    {
        foreach (['3.º medio','4.º medio'] as $level) {
            $criteria=ClinicalRubricPdf::defaultCriteria($level);
            $this->assertCount(9,$criteria);
            $pdf=(new ClinicalRubricPdf())->buildTemplate('Pauta de prueba',$level,$criteria,'Docente QA');
            $this->assertStringStartsWith('%PDF-1.4',$pdf);
            $this->assertStringContainsString('%%EOF',$pdf);
            $this->assertGreaterThan(2000,strlen($pdf));
        }
    }
}
