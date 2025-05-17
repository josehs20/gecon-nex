<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNfeioLojasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nfeio_lojas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('loja_id');
            $table->string('nfeio_id')->unique(); // ID da loja na API NFE.io
            $table->string('account_id')->nullable(); // ID da conta na API
            $table->string('name')->nullable(); // Razão social
            $table->string('trade_name')->nullable(); // Nome fantasia
            $table->string('federal_tax_number')->nullable(); // CNPJ
            $table->string('tax_regime')->nullable(); // Regime tributário
            $table->string('status')->nullable(); // Status da empresa
            $table->json('address')->nullable(); // Armazena o endereço completo (estado, cidade, etc.)
            $table->timestamps(); // Datas de criação e atualização no sistema

            $table->foreign('empresa_id')->references('id')->on('empresas');
            $table->foreign('loja_id')->references('id')->on('lojas');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nfeio_lojas');
    }
}
