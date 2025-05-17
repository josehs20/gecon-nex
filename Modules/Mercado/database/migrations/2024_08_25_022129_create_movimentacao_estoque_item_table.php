<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMovimentacaoEstoqueItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('movimentacao_estoque_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estoque_id');
            $table->decimal('quantidade_movimentada', 15, 3);
            $table->unsignedBigInteger('tipo_movimentacao_estoque_id');
            $table->unsignedBigInteger('movimentacao_id');
            $table->boolean('ativo')->default(0);

            $table->foreign('estoque_id')->references('id')->on('estoques');
            $table->foreign('movimentacao_id')->references('id')->on('movimentacao_estoque');
            $table->foreign('tipo_movimentacao_estoque_id')->references('id')->on('tipo_movimentacao_estoque');

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
        Schema::connection('mercado')->table('movimentacao_estoque_item', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['estoque_id']);
            $table->dropForeign(['movimentacao_id']);
            $table->dropForeign(['tipo_movimentacao_id']);

        });

        Schema::connection('mercado')->dropIfExists('movimentacao_estoque_item');
    }
}
