<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArquivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('arquivos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tipo_arquivo_id');
            $table->unsignedBigInteger('loja_id');
            $table->string('path');
            $table->timestamps();

            $table->foreign('tipo_arquivo_id')->references('id')->on('tipo_arquivo')->onDelete('cascade');
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('arquivos');
    }
}
