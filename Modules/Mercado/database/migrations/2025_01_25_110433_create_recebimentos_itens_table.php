<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecebimentosItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('recebimento_itens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('recebimento_id');
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('estoque_id');
            $table->unsignedBigInteger('pedido_item_id');
            $table->unsignedBigInteger('status_id');
            $table->decimal('quantidade_recebida', 15, 3)->unsigned();
            $table->decimal('quantidade_pedida', 15, 3)->unsigned();
            $table->bigInteger('preco_unitario')->unsigned();
            $table->bigInteger('total');
            $table->string('lote', 50)->nullable();
            $table->date('validade')->nullable();


            $table->timestamps();

            $table->foreign('recebimento_id')->references('id')->on('recebimentos')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->foreign('estoque_id')->references('id')->on('estoques')->onDelete('cascade');
            $table->foreign('pedido_item_id')->references('id')->on('pedido_itens')->onDelete('cascade');
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
        Schema::connection(config('database.connections.mercado.database'))->table('recebimento_itens', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['recebimento_id']);
            $table->dropForeign(['loja_id']);
            $table->dropForeign(['estoque_id']);
            $table->dropForeign(['produto_id']);
            $table->dropForeign(['status_id']);
        });
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('recebimento_itens');
    }
}
