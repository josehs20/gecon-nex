<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFabricantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('fabricantes', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('descricao')->nullable();
            $table->string('cnpj', 14)->unique();
            $table->string('razao_social');
            $table->string('inscricao_estadual')->nullable();
            $table->unsignedBigInteger('endereco_id')->nullable();
            $table->string('celular')->nullable();
            $table->string('telefone')->nullable();
            $table->string('email')->nullable();
            $table->string('site')->nullable();
            $table->boolean('ativo')->default(true);
            $table->unsignedBigInteger('empresa_master_cod');
            $table->timestamps();

            $table->foreign('endereco_id')->references('id')->on('enderecos');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mercado')->dropIfExists('fabricantes');
    }
}
