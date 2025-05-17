<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFichaClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('ficha_cliente', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id');
            $table->foreignId('loja_id');
            $table->foreignId('venda_id')->nullable();
            $table->bigInteger('valor');
            $table->foreignId('venda_pagamento_id')->nullable();
            $table->foreignId('venda_parcela_id')->nullable();
            $table->foreignId('caixa_diario_id')->nullable();
            $table->timestamps();


            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('venda_pagamento_id')->references('id')->on('venda_pagamentos')->onDelete('cascade');
            $table->foreign('venda_parcela_id')->references('id')->on('venda_parcelas')->onDelete('cascade');
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
        Schema::dropIfExists('ficha_cliente');
    }
}
