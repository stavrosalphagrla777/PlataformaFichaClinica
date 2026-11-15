<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentCase extends Model
{
    protected $fillable = ['case_assignment_id', 'estudiante_id', 'started_at', 'submitted_at', 'estado'];
    protected $casts = ['started_at' => 'datetime', 'submitted_at' => 'datetime'];

    public function assignment() { return $this->belongsTo(CaseAssignment::class, 'case_assignment_id'); }
    public function estudiante() { return $this->belongsTo(User::class, 'estudiante_id'); }
    public function sections() { return $this->hasMany(CaseSection::class); }
    public function evaluation() { return $this->hasOne(CaseEvaluation::class); }
}
