<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('certificados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('nfeio_loja_id');
            $table->boolean('ativo');
            $table->string('caminho'); // Caminho do arquivo no servidor
            $table->string('senha')->nullable(); // Se precisar de senha para o .pfx
            $table->string('status')->nullable(); // Se precisar de senha para o .pfx
            $table->timestamp('expiracao')->nullable(); // Data de expiração do certificado
            $table->timestamps();

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('loja_id')->references('id')->on('lojas');
            $table->foreign('nfeio_loja_id')->references('id')->on('nfeio_lojas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('certificados');
    }
}
