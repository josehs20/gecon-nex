<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UnidadeMedidaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::connection(config('database.connections.mercado.database'))->create('unidade_medida', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->string('sigla');
            $table->boolean('pode_ser_float');
            $table->integer('empresa_master_cod');
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
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('unidade_medida');
    }
}
