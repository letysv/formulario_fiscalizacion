<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ayuntamiento extends Model
{
    protected $fillable = ['nombre', 'sede_id'];
    
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }
    
    public function registros()
    {
        return $this->hasMany(Registro::class);
    }
}
