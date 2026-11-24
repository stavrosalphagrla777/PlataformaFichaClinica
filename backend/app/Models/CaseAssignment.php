<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseAssignment extends Model
{
    protected $fillable = [
        'clinical_case_id',
        'rubric_id',
        'docente_id',
        'nivel',
        'curso',
        'duracion_minutos',
        'inicio',
        'termino',
        'estado',
        'observaciones',
    ];

    protected $casts = [
        'inicio' => 'datetime',
        'termino' => 'datetime',
        'duracion_minutos' => 'integer',
    ];

    public function clinicalCase()
    {
        return $this->belongsTo(ClinicalCase::class);
    }

    public function rubric()
    {
        return $this->belongsTo(EvaluationRubric::class, 'rubric_id');
    }

    /**
     * Duración efectiva: personalización por asignación
     * o valor del caso de origen.
     */
    public function effectiveDuration(): int
    {
        return (int) ($this->duracion_minutos ?? $this->clinicalCase->duracion);
    }

    public function studentCases()
    {
        return $this->hasMany(StudentCase::class);
    }
}
