<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('usuario_id');
            $table->timestamp('data_limite')->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mercado')->table('pedidos', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['fornecedor_id']);
            $table->dropForeign(['loja_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['usuario_id']);
        });
        Schema::connection('mercado')->dropIfExists('pedidos');

    }
}
