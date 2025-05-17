<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEstoqueImpostos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('estoque_impostos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nf_master_cod');
            $table->foreignId('tipo_imposto_id')->constrained('tipo_imposto')->onDelete('cascade');
            $table->foreignId('estoque_id')->constrained('estoques')->onDelete('cascade');
            $table->foreignId('produto_id')->constrained('produtos')->onDelete('cascade');
            $table->foreignId('loja_id')->constrained('lojas')->onDelete('cascade');
            $table->string('tipo_imposto');  // Tipo do imposto: PIS, COFINS, ICMS, IPI, etc.
            $table->string('CST')->nullable();  // Código de Situação Tributária (CST)
            $table->decimal('vBC', 10, 2)->default(0);  // Base de Cálculo do Imposto
            $table->decimal('aliquota', 5, 2)->default(0);  // Alíquota do Imposto
            $table->decimal('valor_imposto', 10, 2)->default(0);  // Valor do Imposto
            $table->integer('cEnq')->nullable();  // Para IPI
            $table->integer('orig')->nullable();  // Para ICMS
            $table->integer('csosn')->nullable();  // Para CSOSN (Caso aplicável)
            $table->decimal('pPIS', 5, 2)->nullable();  // Para PIS
            $table->decimal('pCOFINS', 5, 2)->nullable();  // Para COFINS
        

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
        Schema::connection(config('database.connections.mercado.database'))->table('estoque_impostos', function (Blueprint $table) {
            $table->dropForeign(['tipo_imposto_id']);
            $table->dropForeign(['estoque_id']);
            $table->dropForeign(['produto_id']);
            $table->dropForeign(['loja_id']);
        });
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('estoque_impostos');
    }
}
