<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
/** Nueve criterios editables para preservar la escala acordada y el PDF. */
class EvaluationRubric extends Model {
 protected $fillable=['docente_id','nombre','nivel','criterios'];
 protected $casts=['criterios'=>'array'];
 public function docente(){return $this->belongsTo(User::class,'docente_id');}
 public function cases(){return $this->hasMany(ClinicalCase::class,'rubric_id');}
}
