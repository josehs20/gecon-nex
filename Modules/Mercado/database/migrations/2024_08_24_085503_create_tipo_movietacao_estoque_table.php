<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTipoMovietacaoEstoqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('tipo_movimentacao_estoque', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');

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
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('tipo_movietacao_estoque');
    }
}
