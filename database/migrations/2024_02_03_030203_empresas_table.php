<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmpresasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('razao_social');
            $table->string('nome_fantasia');
            $table->unsignedBigInteger('status_id');
            $table->string('cnpj');
            $table->boolean('ativo')->nullable();
            $table->timestamps();
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropForeign(['status_id']);
        });

        Schema::dropIfExists('empresas');
    }
}
