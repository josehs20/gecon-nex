<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection(config('database.connections.mercado.database'))->create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->string('cod_barras');
            $table->string('cod_aux');
            $table->date('data_validade')->nullable(); // Campo de data
            $table->unsignedBigInteger('unidade_medida_id');
            $table->unsignedBigInteger('classificacao_produto_id');
            $table->unsignedBigInteger('fabricante_id')->nullable();

            $table->timestamps();

            $table->foreign('unidade_medida_id')->references('id')->on('unidade_medida');
            $table->foreign('classificacao_produto_id')->references('id')->on('classificacao_produto');
            $table->foreign('fabricante_id')->references('id')->on('fabricantes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.connections.mercado.database'))->table('produtos', function (Blueprint $table) {
            $table->dropForeign(['unidade_medida_id']);
            $table->dropForeign(['classificacao_produto_id']);
            $table->dropForeign(['loja_id']);
        });

        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('produtos');
    }
}
