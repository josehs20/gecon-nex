<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::connection('mercado')->create('caixa_itens_temp', function (Blueprint $table) {
            $table->id();

            $table->foreignId('estoque_id')->constrained('estoques')->cascadeOnDelete();
            $table->foreignId('produto_id')->constrained('produtos')->cascadeOnDelete();
            $table->foreignId('caixa_id')->constrained('caixas')->cascadeOnDelete();
            $table->decimal('quantidade', 15, 3);
            $table->bigInteger('preco');
            $table->bigInteger('total');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('mercado')->dropIfExists('caixa_itens_temp');
    }
};
