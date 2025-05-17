<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevolucaoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('devolucoes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id');
            $table->foreignId('caixa_id');
            $table->foreignId('loja_id');
            $table->foreignId('usuario_id');
            $table->text('motivo');
            $table->dateTime('data_devolucao');
            $table->bigInteger('total_devolvido');
            $table->foreignId('forma_pagamento_id')->nullable();
            $table->foreignId('caixa_diario_id')->nullable();
            $table->foreignId('caixa_evidencia_id')->nullable();
            $table->timestamps();

            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('caixa_id')->references('id')->on('caixas')->onDelete('cascade');
            $table->foreign('caixa_evidencia_id')->references('id')->on('caixa_evidencias')->onDelete('cascade');
            $table->foreign('caixa_diario_id')->references('id')->on('caixa_diario')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.connections.mercado.database'))->table('devolucoes', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['loja_id']);
            $table->dropForeign(['venda_id']);
            $table->dropForeign(['usuario_id']);
            $table->dropForeign(['caixa_id']);
            $table->dropForeign(['caixa_evidencia_id']);
            $table->dropForeign(['caixa_diario_id']);

        });
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('devolucao');
    }
}
