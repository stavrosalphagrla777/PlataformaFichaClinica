<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignosVitales extends Model
{
    use HasFactory;

    protected $table = 'signos_vitales';

    protected $fillable = [
        'medical_record_id',
        'registrado_por_id',
        'fecha_hora_registro',
        'frecuencia_cardiaca',
        'presion_arterial',
        'frecuencia_respiratoria',
        'temperatura',
        'saturacion_oxigeno',
        'glicemia_hgt',
        'peso',
        'talla',
        'imc',
        'clasificacion_imc',
        'estado_hemodinamico',
        'observaciones',
    ];

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
}
