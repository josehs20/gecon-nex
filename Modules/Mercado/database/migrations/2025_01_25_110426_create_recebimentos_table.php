<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRecebimentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('recebimentos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pedido_id');
            $table->unsignedBigInteger('usuario_id');
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('arquivo_id')->nullable();
            $table->timestamp('data_recebimento')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();


            $table->foreign('pedido_id')->references('id')->on('pedidos')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('arquivo_id')->references('id')->on('arquivos')->onDelete('cascade');

        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mercado')->table('recebimentos', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['pedido_id']);
            $table->dropForeign(['usuario_id']);
            $table->dropForeign(['loja_id']);
            $table->dropForeign(['status_id']);
        });
        Schema::connection('mercado')->dropIfExists('recebimentos');
    }
}
