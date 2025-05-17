<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormaPagamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('forma_pagamentos', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->boolean('ativo');
            $table->unsignedBigInteger('especie_pagamento_id');
            $table->unsignedBigInteger('loja_id');

            $table->timestamps();
            $table->foreign('especie_pagamento_id')->references('id')->on('especie_pagamento')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mercado')->table('forma_pagamentos', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['especie_pagamento_id']);
            $table->dropForeign(['loja_id']);
        });
        Schema::connection('mercado')->dropIfExists('forma_pagamentos');
    }
}
