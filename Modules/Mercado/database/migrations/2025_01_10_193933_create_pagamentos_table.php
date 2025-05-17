<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePagamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('pagamentos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('caixa_id');
            $table->unsignedBigInteger('caixa_evidencia_id');
            $table->unsignedBigInteger('venda_id');
            $table->unsignedBigInteger('venda_pagamento_id');
            $table->unsignedBigInteger('forma_pagamento_id');
            $table->unsignedBigInteger('especie_pagamento_id');
            $table->integer('parcelas')->nullable();
            $table->dateTime('data_pagamento')->nullable();
            $table->bigInteger('valor')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('caixa_id')->references('id')->on('caixas')->onDelete('cascade');
            $table->foreign('venda_id')->references('id')->on('vendas')->onDelete('cascade');
            $table->foreign('venda_pagamento_id')->references('id')->on('venda_pagamentos')->onDelete('cascade');
            $table->foreign('caixa_evidencia_id')->references('id')->on('caixa_evidencias')->onDelete('cascade');
            $table->foreign('forma_pagamento_id')->references('id')->on('forma_pagamentos')->onDelete('cascade');
            $table->foreign('especie_pagamento_id')->references('id')->on('especie_pagamento')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.connections.mercado.database'))->table('pagamentos', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['loja_id']);
            $table->dropForeign(['caixa_id']);
            $table->dropForeign(['venda_id']);
            $table->dropForeign(['venda_pagamento_id']);
            $table->dropForeign(['caixa_evidencia_id']);
            $table->dropForeign(['forma_pagamento_id']);
            $table->dropForeign(['especie_pagamento_id']);
        });
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('pagamentos');
    }
}
