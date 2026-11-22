<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseSection extends Model
{
    protected $fillable = ['student_case_id', 'clave', 'storage_key', 'storage_type', 'payload'];
    public function studentCase() { return $this->belongsTo(StudentCase::class); }
}
