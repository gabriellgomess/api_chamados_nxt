<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chamados', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->text('descricao');
            $table->string('status')->default('aberto');
            $table->string('prioridade')->default('media');
            $table->foreignId('setor_id')->constrained('setores');
            $table->foreignId('solicitante_id')->constrained('users');
            $table->foreignId('atendente_id')->nullable()->constrained('atendentes');
            $table->timestamp('data_inicio')->nullable();
            $table->timestamp('data_fim')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('chamados');
    }
};
