<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FornecedorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('fornecedor', function (Blueprint $table) {
            $table->id();
            $table->integer('empresa_master_cod');
            $table->string('nome', 250);
            $table->string('nome_fantasia', 250);
            $table->string('documento', 50)->unique();
            $table->string('pessoa', 1);
            $table->boolean('ativo')->default(true);
            $table->string('celular', 30)->nullable();
            $table->string('telefone_fixo', 30)->nullable();
            $table->string('email', 250)->unique()->nullable();
            $table->string('site', 250)->nullable();
            $table->unsignedBigInteger('endereco_id');
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
        Schema::connection(config('database.connections.mercado.database'))->table('fornecedor', function (Blueprint $table) {
            $table->dropForeign(['endereco_id']);
        });
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('fornecedor');
    }
}
