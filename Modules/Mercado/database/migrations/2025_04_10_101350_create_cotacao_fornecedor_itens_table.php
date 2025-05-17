<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotacaoFornecedorItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('cotacao_fornecedor_itens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cotacao_fornecedor_id');
            $table->unsignedBigInteger('fornecedor_id');
            $table->unsignedBigInteger('pedido_item_id');
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('cotacao_id');
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('estoque_id');
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('status_id');
            $table->decimal('quantidade', 15, 3)->nullable()->unsigned();
            $table->bigInteger('preco_unitario')->nullable()->unsigned();
            $table->timestamps();


            $table->foreign('cotacao_fornecedor_id')->references('id')->on('cotacao_fornecedores')->onDelete('cascade');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedor')->onDelete('cascade');
            $table->foreign('pedido_item_id')->references('id')->on('pedido_itens')->onDelete('cascade');
            $table->foreign('pedido_id')->references('id')->on('pedidos')->onDelete('cascade');
            $table->foreign('cotacao_id')->references('id')->on('cotacoes')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('estoque_id')->references('id')->on('estoques')->onDelete('cascade');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('cotacao_fornecedor_itens');
    }
}
