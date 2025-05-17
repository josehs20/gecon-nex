<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddModuloIdToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('tipo_usuario_id');
            $table->unsignedBigInteger('modulo_id')->nullable();
            $table->unsignedBigInteger('empresa_id')->nullable();
            $table->foreign('modulo_id')->references('id')->on('modulos');
            $table->foreign('tipo_usuario_id')->references('id')->on('tipo_usuarios');
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
        Schema::table('users', function (Blueprint $table) {
             // Remover as chaves estrangeiras primeiro
             $table->dropForeign(['modulo_id']);
             $table->dropForeign(['tipo_usuario_id']);
             $table->dropForeign(['empresa_id']);

             // Remover as colunas
             $table->dropColumn(['tipo_usuario_id', 'modulo_id', 'empresa_id']);
        });
    }
}
