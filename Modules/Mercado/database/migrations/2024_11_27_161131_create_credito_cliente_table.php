<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCreditoClienteTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('credito_cliente', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cliente_id');
            $table->bigInteger('credito_loja')->unsigned()->nullable();
            $table->bigInteger('credito_ave')->unsigned()->nullable();
            $table->bigInteger('credito_loja_usado')->unsigned()->nullable();
            $table->timestamps();
            $table->foreign('cliente_id')->references('id')->on('clientes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('credito_cliente');
    }
}
