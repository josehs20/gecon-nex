<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEspeciePagamentoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('especie_pagamento', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->boolean('afeta_troco')->default(false)->nullable();
            $table->boolean('credito_loja')->default(false)->nullable();
            $table->boolean('contem_parcela')->default(false)->nullable();

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

        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('especie_pagamento');
    }
}
