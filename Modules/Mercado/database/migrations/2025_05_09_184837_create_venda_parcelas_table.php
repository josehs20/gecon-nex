<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendaParcelasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('venda_parcelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id');
            $table->foreignId('loja_id');
            $table->foreignId('venda_pagamento_id');
            $table->integer('numero_parcela');
            $table->bigInteger('valor');
            $table->date('data_vencimento');
            $table->date('data_pagamento')->nullable();
            $table->boolean('pago')->default(false);
            $table->foreignId('forma_pagamento_id');
            $table->foreignId('cliente_id');
            $table->foreignId('status_id');
            $table->foreignId('caixa_diario_id');
            $table->timestamps();


            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('venda_pagamento_id')->references('id')->on('venda_pagamentos')->onDelete('cascade');
            $table->foreign('forma_pagamento_id')->references('id')->on('forma_pagamentos')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
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
        Schema::connection('mercado')->dropIfExists('venda_parcelas');
    }
}
