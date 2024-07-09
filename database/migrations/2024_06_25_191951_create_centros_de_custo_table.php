<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentrosDeCustoTable extends Migration
{
    public function up()
    {
        Schema::create('centros_de_custo', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->text('descricao');
            $table->string('codigo');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('centros_de_custo');
    }
}

