<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCompraItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('compra_itens', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('compra_id');
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('cot_for_item_id');



            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('compra_id')->references('id')->on('compras')->onDelete('cascade');
            $table->foreign('cot_for_item_id')->references('id')->on('cotacao_fornecedor_itens')->onDelete('cascade');
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
        Schema::connection('mercado')->dropIfExists('compra_itens');
    }
}
