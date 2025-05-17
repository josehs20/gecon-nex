<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaixasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('caixas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->string('nome');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('usuario_id')->nullable();
            $table->boolean('ativo')->default(true);
            $table->string('token')->unique()->nullable();
            $table->timestamps();

            $table->foreign('loja_id')->references('id')->on('lojas');
            $table->foreign('status_id')->references('id')->on('status');
            $table->foreign('usuario_id')->references('id')->on('usuarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.connections.mercado.database'))->table('caixas', function (Blueprint $table) {
            $table->dropForeign(['loja_id']);
            $table->dropForeign(['status_id']);
            $table->dropForeign(['usuario_id']);
            $table->dropIfExists();
        });
    }
}
