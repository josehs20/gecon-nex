<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProdutoImpostosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('produto_impostos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('estoque_id');
            $table->string('ncm_codigo');
            $table->string('descricao')->nullable();
            $table->string('uf')->nullable();
            $table->decimal('icms', 8, 2)->nullable();
            $table->decimal('ipi', 8, 2)->nullable();
            $table->decimal('valor_importado', 8, 2)->nullable();
            $table->decimal('valor_nacional', 8, 2)->nullable();
            $table->date('vigencia_inicio')->nullable();
            $table->date('vigencia_fim')->nullable();
            $table->string('cst_codigo', 3)->nullable();  // Campo para armazenar o código CST

            $table->timestamps();

            $table->foreign('produto_id')->references('id')->on('produtos')->onDelete('cascade');
            $table->foreign('estoque_id')->references('id')->on('estoques')->onDelete('cascade');
        });

        Schema::connection('mercado')->table('estoques', function (Blueprint $table) {
            $table->unsignedBigInteger('ncm_id')->after('produto_id')->nullable();
            $table->unsignedBigInteger('produto_imposto_id')->after('produto_id')->nullable();

            $table->foreign('ncm_id')->references('id')->on('ncms')->onDelete('cascade');
            $table->foreign('produto_imposto_id')->references('id')->on('produto_impostos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mercado')->dropIfExists('produto_impostos');
    }
}
