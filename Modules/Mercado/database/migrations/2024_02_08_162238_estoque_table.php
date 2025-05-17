<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EstoqueTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('estoques', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('custo')->unsigned();
            $table->bigInteger('preco')->unsigned();
            $table->decimal('quantidade_total', 15, 3)->unsigned();
            $table->decimal('quantidade_disponivel', 15, 3)->unsigned();
            $table->decimal('quantidade_minima', 15, 3)->unsigned()->nullable();
            $table->decimal('quantidade_maxima', 15, 3)->unsigned()->nullable();
            $table->string('localizacao')->nullable();

            // $table->unsignedBigInteger('loja_produto_id');
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('produto_id');
            $table->timestamps();

            // $table->foreign('loja_produto_id')->references('id')->on('lojas_produto');
            $table->foreign('loja_id')->references('id')->on('lojas');
            $table->foreign('produto_id')->references('id')->on('produtos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.connections.mercado.database'))->table('estoques', function (Blueprint $table) {
            $table->dropForeign(['loja_id', 'produto_id']);
        });

        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('estoques');
    }
}
