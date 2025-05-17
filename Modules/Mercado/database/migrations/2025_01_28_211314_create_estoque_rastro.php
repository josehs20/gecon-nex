<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstoqueRastro extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('estoque_rastro', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nf_master_cod');
            $table->foreignId('estoque_id')->constrained('estoques')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            $table->foreignId('loja_id')->constrained('lojas')->onDelete('cascade');
            $table->date('dFab')->nullable();
            $table->date('dVal')->nullable();
            $table->string('nLote')->nullable();
            $table->integer('qLote')->nullable();
            $table->string('cAgreg')->nullable();

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
        Schema::connection(config('database.connections.mercado.database'))->table('estoque_rastro', function (Blueprint $table) {
            $table->dropForeign(['estoque_id']);
            $table->dropForeign(['produto_id']);
            $table->dropForeign(['loja_id']);
        });
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('estoque_rastro');
    }
}
