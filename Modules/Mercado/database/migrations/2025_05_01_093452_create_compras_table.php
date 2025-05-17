<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateComprasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('cotacao_id');
            $table->unsignedBigInteger('cot_fornecedor_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('especie_pagamento_id');



            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('cotacao_id')->references('id')->on('cotacoes')->onDelete('cascade');
            $table->foreign('cot_fornecedor_id')->references('id')->on('cotacao_fornecedores')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('especie_pagamento_id')->references('id')->on('especie_pagamento')->onDelete('cascade');

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
        Schema::connection('mercado')->dropIfExists('compras');
    }
}
