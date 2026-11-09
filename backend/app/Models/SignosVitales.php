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
        'interpretacion_clinica',
    ];

    protected $casts = [
        'fecha_hora_registro' => 'datetime',
        'peso' => 'decimal:2',
        'talla' => 'decimal:2',
        'imc' => 'decimal:1',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class, 'medical_record_id');
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por_id');
    }
}