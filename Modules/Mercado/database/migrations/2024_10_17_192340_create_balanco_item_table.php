<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalancoItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('balanco_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('estoque_id');
            $table->unsignedBigInteger('balanco_id');
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('tipo_movimentacao_estoque_id');
            $table->decimal('quantidade_estoque_sistema', 15, 3);
            $table->decimal('quantidade_estoque_real', 15, 3);
            $table->decimal('quantidade_resultado_operacional', 15, 3);
            $table->boolean('ativo')->default(0);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('estoque_id')->references('id')->on('estoques')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('balanco_id')->references('id')->on('balanco')->onDelete('cascade');
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
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('balanco_item');
    }
}
