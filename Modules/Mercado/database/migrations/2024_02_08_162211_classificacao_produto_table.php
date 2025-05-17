<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ClassificacaoProdutoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('classificacao_produto', function (Blueprint $table) {
            $table->id();
            $table->string('descricao');
            $table->integer('empresa_master_cod');
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
        Schema::connection('mercado')->dropIfExists('classificacao_produto');

    }
}
