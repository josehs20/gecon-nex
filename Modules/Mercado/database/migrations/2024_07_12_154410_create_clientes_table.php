<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('clientes', function (Blueprint $table) {
            $table->id();
            $table->integer('empresa_master_cod');
            $table->string('nome', 250);
            $table->string('documento', 50)->nullable()->unique();
            $table->string('pessoa');
            $table->boolean('ativo')->default(true);
            $table->unsignedBigInteger('status_id')->default(6);
            $table->string('celular', 30)->nullable();
            $table->string('telefone_fixo', 30)->nullable();
            $table->string('email')->unique()->nullable();
            $table->date('data_nascimento')->nullable();
            $table->text('observacao')->nullable();
            $table->unsignedBigInteger('endereco_id')->nullable();
            $table->foreign('endereco_id')->references('id')->on('enderecos');
            $table->foreign('status_id')->references('id')->on('status');
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
        Schema::connection(config('database.connections.mercado.database'))->table('clientes', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
            $table->dropForeign(['endereco_id']);
        });
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('cliente');
    }
}
