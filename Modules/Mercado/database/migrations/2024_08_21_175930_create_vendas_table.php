<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('vendas', function (Blueprint $table) {
            $table->id();
            $table->string('n_venda');
            $table->foreignId('cliente_id');
            $table->foreignId('caixa_id');
            $table->foreignId('caixa_evidencia_id')->nullable();
            $table->foreignId('loja_id');
            $table->foreignId('usuario_id');
            $table->foreignId('status_id');
            $table->foreignId('forma_pagamento_id')->nullable();
            $table->bigInteger('sub_total');
            $table->bigInteger('total');
            $table->decimal('desconto_porcentagem', 10, 2)->nullable();
            $table->bigInteger('desconto_dinheiro')->nullable();
            $table->foreignId('caixa_diario_id')->nullable();

            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
            $table->foreign('caixa_id')->references('id')->on('caixas')->onDelete('cascade');
            $table->foreign('caixa_evidencia_id')->references('id')->on('caixa_evidencias')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('forma_pagamento_id')->references('id')->on('forma_pagamentos')->onDelete('cascade');
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
        Schema::connection(config('database.connections.mercado.database'))->table('vendas', function (Blueprint $table) {
            $table->dropForeign(['cliente_id']);
            $table->dropForeign(['caixa_id']);
            $table->dropForeign(['caixa_evidencia_id']);
            $table->dropForeign(['loja_id']);
            $table->dropForeign(['usuario_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['forma_pagamento_id']);
            $table->dropForeign(['caixa_diario_id']);

        });

        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('vendas');
    }
}
