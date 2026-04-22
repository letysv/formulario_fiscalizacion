<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $fillable = ['nombre'];
    
    public function ayuntamientos()
    {
        return $this->hasMany(Ayuntamiento::class);
    }
    
    public function registros()
    {
        return $this->hasMany(Registro::class);
    }
}
