<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendaItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('venda_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id');
            $table->foreignId('caixa_id');
            $table->foreignId('estoque_id');
            $table->foreignId('loja_id');
            $table->foreignId('produto_id');
            $table->bigInteger('quantidade');
            $table->bigInteger('preco');
            $table->bigInteger('total');
            $table->foreignId('caixa_diario_id');

            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('estoque_id')->references('id')->on('estoques')->onDelete('cascade');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->foreign('caixa_id')->references('id')->on('caixas')->onDelete('cascade');
            $table->foreign('caixa_diario_id')->references('id')->on('caixa_diario')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mercado')->table('venda_itens', function (Blueprint $table) {
            $table->dropForeign(['venda_id']);
            $table->dropForeign(['loja_id']);
            $table->dropForeign(['estoque_id']);
            $table->dropForeign(['produto_id']);
            $table->dropForeign(['caixa_id']);
            $table->dropForeign(['caixa_diario_id']);
        });
        Schema::connection('mercado')->dropIfExists('venda_itens');
    }
}
