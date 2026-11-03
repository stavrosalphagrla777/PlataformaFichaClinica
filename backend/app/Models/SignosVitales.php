<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignosVitales extends Model
{
    use HasFactory;

    // Relación con la Ficha Clínica
    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    // Relación con el usuario que registra
    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por_id');
    }

    protected $fillable = [
        'ficha_clinica_id',
        'estudiante_id',
        'frecuencia_cardiaca',
        'presion_arterial',
        'frecuencia_respiratoria',
        'saturacion_oxigeno',
        'temperatura',
        'escala_glasgow',
        'peso',
        'talla',
        'observaciones',
        'interpretacion_clinica',
    ];
}