<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaixaDiarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('caixa_diario', function (Blueprint $table) {
            $table->id();

            $table->foreignId('caixa_id')->constrained('caixas')->onDelete('cascade');
            $table->foreignId('caixa_evidencia_id')->nullable()->constrained('caixa_evidencias')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('loja_id')->constrained('lojas')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('status')->onDelete('cascade');

            $table->timestamp('data_abertura')->nullable();
            $table->timestamp('data_fechamento')->nullable();

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
        Schema::dropIfExists('caixa_diario');
    }
}
