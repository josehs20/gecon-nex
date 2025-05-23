<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserLojasRelations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('lojas_usuario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('usuario_id');

            $table->foreign('loja_id')->references('id')->on('lojas');
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
        Schema::connection('mercado')->table('lojas_usuario', function (Blueprint $table) {
            $table->dropForeign(['usuario_id']);
            $table->dropForeign(['loja_id']);

        });
        Schema::connection('mercado')->dropIfExists('lojas_usuario');
    }
}
