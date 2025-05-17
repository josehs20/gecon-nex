<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditsTable extends Migration
{
    public function up()
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_type')->nullable();
            $table->string('event');
            $table->string('auditable_type');
            $table->unsignedBigInteger('auditable_id');
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->text('url')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->text('tags')->nullable();
            $table->unsignedBigInteger('processo_id')->nullable();
            $table->unsignedBigInteger('acao_id')->nullable();
            $table->text('comentario')->nullable();
            $table->timestamps();

            $table->foreign('processo_id')->references('id')->on('processos')->onDelete('set null');

            $table->foreign('acao_id')->references('id')->on('acoes')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('audits', function (Blueprint $table) {
            // Remover chaves estrangeiras
            $table->dropForeign(['processo_id']);
            $table->dropForeign(['acao_id']);

            // Remover colunas
            $table->dropColumn(['processo_id', 'acao_id']);
        });
        Schema::dropIfExists('audits');
    }
}
