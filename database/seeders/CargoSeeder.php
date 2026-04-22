<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cargo;

class CargoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $cargos = [
            'Presidente',
            'Tesorero',
            'Director de Obras Públicas',
            'Síndico',
            'Contralor Municipal'
        ];

        foreach ($cargos as $cargo) {
            Cargo::create(['nombre' => $cargo]);
        }
    }
}
