<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('registros', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sede_id')->constrained()->onDelete('cascade');
            $table->foreignId('ayuntamiento_id')->constrained()->onDelete('cascade');
            $table->foreignId('cargo_id')->constrained()->onDelete('cascade');
            $table->string('nombre_completo');
            $table->string('telefono', 10);
            $table->string('telefono_oficina', 10)->nullable();
            $table->string('correo_personal')->unique();
            $table->string('correo_institucional')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('registros');
    }
};