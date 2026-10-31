<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'patient_id',
        'esi_triage',
        'blood_pressure',
        'heart_rate',
        'respiratory_rate',
        'temperature',
        'weight',
        'height',
        'bmi',
    ];

    /**
     * Profesional de salud que registró la ficha.
     */
    public function professional(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Estudiante o paciente asociado a la ficha.
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}

