<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $fillable = [
        'sede_id', 
        'ayuntamiento_id', 
        'cargo_id', 
        'nombre_completo', 
        'telefono',
        'telefono_oficina',
        'correo_personal', 
        'correo_institucional'
    ];
    
    public function sede()
    {
        return $this->belongsTo(Sede::class);
    }
    
    public function ayuntamiento()
    {
        return $this->belongsTo(Ayuntamiento::class);
    }
    
    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }
}