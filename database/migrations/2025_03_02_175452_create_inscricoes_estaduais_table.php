<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInscricoesEstaduaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inscricoes_estaduais', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('nfeio_loja_id');
            $table->string('state_tax_id')->nullable(); // ID da conta (ex: '66d8cf1ff2f80c0f50c810c6')
            $table->string('account_id')->nullable(); // ID da conta (ex: '66d8cf1ff2f80c0f50c810c6')
            $table->string('company_id')->nullable(); // ID da empresa (ex: '52a91388f0624b8b95542b8a7b3453f1')
            $table->string('code')->nullable(); // Código do estado (ex: 'RJ')
            $table->string('special_tax_regime')->nullable(); // Regime tributário especial (ex: 'Automatico')
            $table->string('type')->nullable(); // Tipo de emissão (ex: 'Default')
            $table->string('tax_number')->nullable(); // Número da inscrição estadual (ex: '86856433')
            $table->string('status')->nullable(); // Status da inscrição (ex: 'Active')
            $table->integer('serie')->default(0); // Série (ex: 1)
            $table->integer('number')->default(0); // Número (ex: 0)
            $table->json('processing_details')->nullable(); // Detalhes do processamento (json)
            $table->json('security_credential')->nullable(); // Detalhes do processamento (json)
            $table->timestamps(); // Created at, updated at


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
        Schema::dropIfExists('inscricoes_estaduais');
    }
}
