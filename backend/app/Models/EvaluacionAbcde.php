<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluacionAbcde extends Model
{
    use HasFactory;

    protected $table = 'evaluaciones_abcde';

    protected $fillable = [
        'medical_record_id',
        'estudiante_id',
        // A - Vía Aérea
        'obstruccion_via_aerea',
        'collar_cervical',
        'aspiracion_secreciones',
        'intubacion',
        'observaciones_a',
        // B - Respiración
        'murmullo_pulmonar',
        'simetria_toracica',
        'oxigenoterapia',
        'tipo_dispositivo_o2',
        'flujo_o2_lmin',
        'ventilacion',
        'observaciones_b',
        // C - Circulación
        'pulso_frecuencia',
        'pulso_ritmo',
        'pulso_calidad',
        'hemorragias',
        'llenado_capilar',
        'accesos_venosos_perifericos',
        'observaciones_c',
        // D - Neurológico
        'glasgow_apertura_ocular',
        'glasgow_respuesta_verbal',
        'glasgow_respuesta_motora',
        'total_glasgow',
        'pupilas',
        'escala_avdi',
        'observaciones_d',
        // E - Exposición
        'lesiones_heridas',
        'lesiones_fracturas',
        'lesiones_contusiones',
        'temperatura_corporal',
        'quemaduras',
        'porcentaje_scq',
        'observaciones_e',
        'observaciones_generales',
    ];

    protected $casts = [
        'oxigenoterapia' => 'boolean',
        'lesiones_heridas' => 'boolean',
        'lesiones_fracturas' => 'boolean',
        'lesiones_contusiones' => 'boolean',
        'quemaduras' => 'boolean',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }

    public function estudiante()
    {
        return $this->belongsTo(User::class, 'estudiante_id');
    }
}
