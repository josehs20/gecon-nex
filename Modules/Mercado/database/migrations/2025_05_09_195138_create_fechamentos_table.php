<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFechamentosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('fechamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caixa_id')->constrained()->onDelete('cascade');
            $table->foreignId('caixa_evidencia_id')->nullable()->constrained('caixa_evidencias')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->bigInteger('valor_total'); 
            $table->bigInteger('valor_dinheiro');
            $table->bigInteger('valor_esperado_dinheiro');
            $table->string('motivo')->nullable();
            $table->foreignId('caixa_diario_id')->nullable()->constrained('caixa_diario')->onDelete('cascade');
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
        Schema::connection('mercado')->dropIfExists('fechamentos');
    }
}
