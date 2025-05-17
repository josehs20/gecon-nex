<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProcessoUsuarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('processo_tipo_usuario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('processo_id');
            $table->unsignedBigInteger('tipo_usuario_id');
            $table->timestamps();

            $table->foreign('processo_id')->references('id')->on('processos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mercado')->table('processo_tipo_usuario', function (Blueprint $table) {
            $table->dropForeign(['processo_id']);
            $table->dropForeign(['tipo_usuario_id']);
        });
        Schema::connection('mercado')->dropIfExists('processo_usuario');
    }
}
