<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Usuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->integer('usuario_master_cod');
            $table->unsignedBigInteger('loja_id')->nullable();
            $table->unsignedBigInteger('endereco_id')->nullable();
            $table->unsignedBigInteger('status_id')->nullable();

            $table->boolean('ativo')->default(true);
            $table->date('data_nascimento')->nullable();
            $table->string('documento', 14)->nullable()->unique();
            $table->string('telefone', 15)->nullable();
            $table->string('celular', 15)->nullable();
            $table->date('data_admissao')->nullable();
            $table->decimal('salario', 10, 2)->default(0);
            $table->enum('tipo_contrato', ['CLT', 'PJ', 'Estagiário', 'Jovem aprendiz', 'Temporário'])->nullable();
            $table->date('data_demissao')->nullable();
            $table->decimal('comissao', 5, 2)->default(0);

            $table->timestamps();
            // Chave estrangeira para loja_id
            $table->foreign('loja_id')->references('id')->on('lojas');

            // Chave estrangeira para endereco_id
            $table->foreign('endereco_id')->references('id')->on('enderecos');

            // Chave estrangeira para status_id
            $table->foreign('status_id')->references('id')->on('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('devolucoes', function (Blueprint $table) {
            // Remove a chave estrangeira
            $table->dropForeign(['loja_id']);
            $table->dropForeign(['venda_id']);
            $table->dropForeign(['usuario_id']);

        });
        Schema::connection('mercado')->dropIfExists('usuarios');

    }
}
