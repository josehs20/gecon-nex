<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaixaEvidenciasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('caixa_evidencias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caixa_id');
            $table->unsignedBigInteger('acao_id');
            $table->unsignedBigInteger('usuario_id');
            $table->bigInteger('valor_total')->nullable();
            $table->bigInteger('valor_dinheiro')->nullable();
            $table->unsignedBigInteger('caixa_recurso_id')->nullable();
            $table->text('descricao')->nullable();

            $table->timestamps();
            $table->foreign('caixa_id')->references('id')->on('caixas')->onDelete('cascade');
            $table->foreign('acao_id')->references('id')->on('acoes')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.connections.mercado.database'))->table('caixa_evidencias', function (Blueprint $table) {
            // Remover chaves estrangeiras
            $table->dropForeign(['caixa_id']);
            $table->dropForeign(['acao_id']);
            $table->dropForeign(['usuario_id']);
        });

        // Finalmente, remover a tabela
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('caixa_evidencias');
    }
}
