<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class AuditEvent extends Model {
    protected $fillable=['actor_id','target_id','action','description'];
    public function actor(){return $this->belongsTo(User::class,'actor_id');}
    public function target(){return $this->belongsTo(User::class,'target_id');}
}
