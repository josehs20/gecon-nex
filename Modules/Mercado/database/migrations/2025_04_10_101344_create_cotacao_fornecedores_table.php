<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCotacaoFornecedoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('cotacao_fornecedores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cotacao_id');
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('fornecedor_id');
            $table->bigInteger('desconto')->nullable();
            $table->bigInteger('frete')->nullable();
            $table->bigInteger('sub_total')->nullable();
            $table->bigInteger('total')->nullable();
            $table->text('observacao')->nullable();
            $table->date('previsao_entrega')->nullable();


            $table->foreign('cotacao_id')->references('id')->on('cotacoes')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('fornecedor_id')->references('id')->on('fornecedor')->onDelete('cascade');

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
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('cotacao_fornecedores');
    }
}
