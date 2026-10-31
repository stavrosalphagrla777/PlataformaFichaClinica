<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Triage extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'estudiante_id',
        'motivo_consulta',
        'prioridad_esi',
        'escala_eva',
        'frecuencia_cardiaca',
        'presion_arterial',
        'frecuencia_respiratoria',
        'saturacion_oxigeno',
        'temperatura',
        'escala_glasgow',
        'peso',
        'talla',
        'nivel_prioridad_calculado',
        'tiempo_maximo_espera_min',
        'riesgos_identificados',
        'observaciones',
    ];

    protected $casts = [
        'riesgos_identificados' => 'array',
    ];

    // Relación con la Ficha Clínica
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    // Relación con el Estudiante (User)
    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }
}