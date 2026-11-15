<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicalCase extends Model
{
    protected $fillable = ['docente_id', 'codigo', 'titulo', 'descripcion', 'antecedentes', 'segmentario', 'nivel', 'tipo', 'estado', 'servicio', 'duracion', 'paciente', 'edad', 'sexo', 'prevision', 'esi'];

    protected $casts = ['segmentario' => 'array'];

    public function assignments() { return $this->hasMany(CaseAssignment::class); }
    public function docente() { return $this->belongsTo(User::class, 'docente_id'); }
}
