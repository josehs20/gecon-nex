<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('pedido_itens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('estoque_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('loja_id');
            $table->decimal('quantidade_pedida', 15, 3)->unsigned();
            $table->longText('observacao')->nullable();

            $table->timestamps();



            $table->foreign('pedido_id')->references('id')->on('pedidos')->onDelete('cascade');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->foreign('estoque_id')->references('id')->on('estoques')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
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
        Schema::connection('mercado')->table('pedido_itens', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['pedido_id']);
            $table->dropForeign(['produto_id']);
            $table->dropForeign(['estoque_id']);
            $table->dropForeign(['loja_id']);
        });
        Schema::connection('mercado')->dropIfExists('pedidos_itens');
    }
}
