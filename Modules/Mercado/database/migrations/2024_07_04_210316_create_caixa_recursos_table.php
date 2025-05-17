<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaixaRecursosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('caixa_recursos', function (Blueprint $table) {
            $table->id();
            // $table->string('nome');
            $table->unsignedBigInteger('caixa_id');
            $table->unsignedBigInteger('recurso_id');
            // $table->text('descricao')->nullable();
            $table->timestamps();

            $table->foreign('caixa_id')->references('id')->on('caixas')->onDelete('cascade');
            $table->foreign('recurso_id')->references('id')->on('recursos')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('caixa_recursos');
    }
}
