<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSangriasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('sangrias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('caixa_id')->constrained()->onDelete('cascade');
            $table->foreignId('caixa_evidencia_id')->nullable()->constrained('caixa_evidencias')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            $table->bigInteger('valor');
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
        Schema::dropIfExists('sangrias');
    }
}
