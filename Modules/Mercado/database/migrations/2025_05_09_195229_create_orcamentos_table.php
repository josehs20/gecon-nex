<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrcamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('orcamentos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('cliente_id')->nullable()->constrained('clientes')->nullOnDelete();
            $table->foreignId('loja_id')->nullable()->constrained('lojas')->nullOnDelete();
            $table->foreignId('empresa_master_cod')->nullable();
            $table->foreignId('usuario_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('status')->nullOnDelete();
            $table->foreignId('forma_pagamento_id')->nullable()->constrained('forma_pagamentos')->nullOnDelete();
            $table->foreignId('caixa_diario_id')->nullable()->constrained('caixa_diario')->nullOnDelete();
            $table->bigInteger('sub_total');
            $table->bigInteger('total');
            $table->decimal('desconto_porcentagem', 10, 2)->nullable();
            $table->bigInteger('desconto_dinheiro')->nullable();

            $table->text('descricao')->nullable();

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
        Schema::dropIfExists('orcamentos');
    }
}
