<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSetoresTable extends Migration
{
    public function up()
    {
        Schema::create('setores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centro_de_custo_id')->constrained('centros_de_custo')->onDelete('cascade');
            $table->string('nome');
            $table->text('descricao');
            $table->string('codigo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('setores');
    }
}
