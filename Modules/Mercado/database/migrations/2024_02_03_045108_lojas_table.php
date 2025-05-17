<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class LojasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('lojas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->integer('empresa_master_cod');
            $table->integer('loja_master_cod');
            $table->string('cnpj')->nullable();
            $table->unsignedBigInteger('endereco_id')->nullable();
            $table->unsignedBigInteger('status_id');
            $table->timestamps();

            // Chave estrangeira para endereco_id
            $table->foreign('endereco_id')->references('id')->on('enderecos');

            // Chave estrangeira para status_id
            $table->foreign('status_id')->references('id')->on('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mercado')->table('lojas', function (Blueprint $table) {
            $table->dropForeign(['endereco_id']);
            $table->dropForeign(['status_id']);
        });
        Schema::connection('mercado')->dropIfExists('lojas');
    }
}
