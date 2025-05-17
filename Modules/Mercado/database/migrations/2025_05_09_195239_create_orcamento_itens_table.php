<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrcamentoItensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('orcamento_itens', function (Blueprint $table) {
            $table->id();

            $table->foreignId('orcamento_id')->constrained('orcamentos')->cascadeOnDelete();
            $table->foreignId('estoque_id')->nullable()->constrained('estoques')->nullOnDelete();
            $table->foreignId('loja_id')->nullable()->constrained('lojas')->nullOnDelete();
            $table->foreignId('produto_id')->nullable()->constrained('produtos')->nullOnDelete();
            $table->foreignId('caixa_diario_id')->nullable()->constrained('caixa_diario')->nullOnDelete();

            $table->decimal('quantidade', 15, 3);
            $table->bigInteger('preco');
            $table->bigInteger('total');

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
        Schema::dropIfExists('orcamento_itens');
    }
}
