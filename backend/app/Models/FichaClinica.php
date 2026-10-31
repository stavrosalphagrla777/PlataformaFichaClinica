<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FichaClinica extends Model
{
    protected $table = 'fichas_clinicas';

    protected $fillable = [
        'user_id',
        'paciente_id',
        'estado',
        'motivo_consulta',
        'antecedentes',
        'alergias',
        'medicamentos_habituales',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
