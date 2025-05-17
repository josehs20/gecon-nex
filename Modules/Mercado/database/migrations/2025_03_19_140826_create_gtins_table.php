<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGtinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('gtins', function (Blueprint $table) {
            $table->id();
            $table->string('gtin', 14)->unique(); // Código GTIN/EAN
            $table->string('ncm', 20)->nullable();
            $table->string('cest', 20)->nullable();
            $table->string('descricao')->nullable(); // Tipo de embalagem (Unidade, Caixa, etc.)
            $table->string('tipo')->nullable(); // Tipo de embalagem (Unidade, Caixa, etc.)
            $table->integer('quantidade')->default(1); // Quantidade por embalagem
            $table->decimal('comprimento', 8, 2)->nullable(); // Comprimento em cm
            $table->decimal('altura', 8, 2)->nullable(); // Altura em cm
            $table->decimal('largura', 8, 2)->nullable(); // Largura em cm
            $table->decimal('peso_bruto', 8, 3)->nullable(); // Peso bruto em kg
            $table->decimal('peso_liquido', 8, 3)->nullable(); // Peso líquido em kg
            $table->date('ultima_verificacao')->nullable();

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
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('gtins');
    }
}
