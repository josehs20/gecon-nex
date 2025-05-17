<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimentacaoEstoqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('movimentacao_estoque', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('tipo_movimentacao_estoque_id');
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->foreign('loja_id')->references('id')->on('lojas');
            $table->foreign('status_id')->references('id')->on('status');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
            $table->foreign('tipo_movimentacao_estoque_id')->references('id')->on('tipo_movimentacao_estoque');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('movimentacao_estoque');
    }
}
