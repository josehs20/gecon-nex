<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCaixaPermissoesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('caixa_permissoes', function (Blueprint $table) {
            $table->id();

            $table->foreignId('caixa_id')->constrained('caixas')->onDelete('cascade');
            $table->foreignId('usuario_id')->constrained('usuarios')->onDelete('cascade');
            // $table->foreignId('tipo_usuario_id')->constrained('tipo_usuario')->onDelete('cascade');

            $table->boolean('superior')->default(false); // define se é superior (tipo gerente, por exemplo)

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
        Schema::dropIfExists('caixa_permissoes');
    }
}
