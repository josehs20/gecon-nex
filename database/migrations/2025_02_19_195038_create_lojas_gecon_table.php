<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLojasGeconTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('lojas', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->unsignedBigInteger('empresa_id');
            $table->boolean('ativo')->default(true);
            $table->boolean('matriz');
            $table->string('cnpj');
            $table->string('email')->nullable();
            $table->string('telefone')->nullable();
            $table->unsignedBigInteger('modulo_id');
            $table->unsignedBigInteger('status_id');
            $table->timestamps();


            // Chave estrangeira para status_id
            $table->foreign('status_id')->references('id')->on('status');
            $table->foreign('modulo_id')->references('id')->on('modulos');
            $table->foreign('empresa_id')->references('id')->on('empresas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lojas');
    }
}
