<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendaPagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('venda_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venda_id');
            $table->foreignId('forma_pagamento_id');
            $table->foreignId('especie_pagamento_id');
            $table->foreignId('loja_id');
            $table->bigInteger('valor');
            $table->foreignId('cliente_id');
            $table->boolean('parcelado')->default(false);
            $table->integer('quantidade_parcelas')->nullable();
            $table->foreignId('caixa_diario_id')->nullable();

            $table->timestamps();
            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('forma_pagamento_id')->references('id')->on('forma_pagamentos')->onDelete('cascade');
            $table->foreign('especie_pagamento_id')->references('id')->on('especie_pagamento')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
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
        Schema::connection('mercado')->table('venda_pagamentos', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['venda_id']);
            $table->dropForeign(['forma_pagamento_id']);
            $table->dropForeign(['especie_pagamento_id']);
            $table->dropForeign(['loja_id']);
            $table->dropForeign(['caixa_diario_id']);

        });
        Schema::connection('mercado')->dropIfExists('venda_pagamentos');
    }
}
