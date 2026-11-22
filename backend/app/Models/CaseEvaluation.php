<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseEvaluation extends Model
{
    protected $fillable = ['student_case_id', 'docente_id', 'criterios', 'rubrica_criterios', 'notas', 'retroalimentacion', 'porcentaje', 'nota', 'estado', 'finalizada_at'];
    protected $casts = ['criterios' => 'array', 'rubrica_criterios' => 'array', 'notas' => 'array', 'finalizada_at' => 'datetime', 'porcentaje' => 'float', 'nota' => 'float'];
    public function studentCase() { return $this->belongsTo(StudentCase::class); }
}
