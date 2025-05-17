<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDevolucaoItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('devolucao_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('devolucao_id');
            $table->foreignId('loja_id');
            $table->foreignId('venda_id');
            $table->foreignId('caixa_id');
            $table->foreignId('venda_item_id');
            $table->foreignId('estoque_origem_id');
            $table->foreignId('estoque_destino_id');
            $table->foreignId('produto_id');
            $table->bigInteger('quantidade');
            $table->bigInteger('preco');
            $table->bigInteger('total');
            $table->foreignId('caixa_diario_id')->nullable();
            $table->timestamps();

            $table->foreign('devolucao_id')->references('id')->on('devolucoes')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('caixa_id')->references('id')->on('caixas')->onDelete('cascade');
            $table->foreign('venda_item_id')->references('id')->on('venda_itens')->onDelete('cascade');
            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
            $table->foreign('estoque_origem_id')->references('id')->on('estoques')->onDelete('cascade');
            $table->foreign('estoque_destino_id')->references('id')->on('estoques')->onDelete('cascade');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
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
        Schema::connection(config('database.connections.mercado.database'))->table('devolucao_itens', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['devolucao_id']);
            $table->dropForeign(['loja_id']);
            $table->dropForeign(['venda_item_id']);
            $table->dropForeign(['venda_id']);
            $table->dropForeign(['estoque_origem_id']);
            $table->dropForeign(['estoque_destino_id']);
            $table->dropForeign(['produto_id']);
            $table->dropForeign(['caixa_id']);
            $table->dropForeign(['caixa_diario_id']);


        });

        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('devolucao_itens');
    }
}
