<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNfEstoquesDetalhes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('estoque_nf', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nf_master_cod');
            $table->foreignId('estoque_id')->constrained('estoques')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            $table->foreignId('loja_id')->constrained('lojas')->onDelete('cascade');
            $table->string('cProd')->nullable();
            $table->string('xProd')->nullable();
            $table->string('cEAN')->nullable()->nullable();
            $table->string('cEANTrib')->nullable()->nullable();
            $table->integer('NCM')->nullable();
            $table->integer('CEST')->nullable()->nullable();
            $table->integer('CFOP')->nullable();
            $table->decimal('vProd', 10, 2)->nullable();
            $table->decimal('vUnCom', 10, 2)->nullable();
            $table->decimal('vUnTrib', 10, 2)->nullable();
            $table->integer('qCom')->nullable();
            $table->integer('qTrib')->nullable();
            $table->string('uCom')->nullable();
            $table->string('uTrib')->nullable();
            $table->boolean('indTot')->nullable();
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
        Schema::connection('mercado')->table('estoque_nf', function (Blueprint $table) {
            $table->dropForeign(['estoque_id']);
            $table->dropForeign(['produto_id']);
            $table->dropForeign(['loja_id']);
        });

        Schema::connection('mercado')->dropIfExists('estoque_nf');
    }
}
