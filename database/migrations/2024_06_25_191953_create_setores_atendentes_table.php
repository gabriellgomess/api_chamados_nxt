<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetoresAtendentesTable extends Migration
{
    public function up()
    {
        Schema::create('setores_atendentes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setor_id')->constrained('setores')->onDelete('cascade');
            $table->foreignId('atendente_id')->constrained('atendentes')->onDelete('cascade');
            $table->boolean('is_gestor')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('setores_atendentes');
    }
}

