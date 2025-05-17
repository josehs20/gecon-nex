<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNcmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection(config('database.connections.mercado.database'))->create('ncms', function (Blueprint $table) {
            $table->id();
            $table->string('codigo');
            $table->text('descricao');
            $table->date('data_inicio');
            $table->date('data_fim');
            $table->string('tipo_ato_ini');
            $table->string('numero_ato_ini');
            $table->integer('ano_ato_ini');
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
        Schema::connection(config('database.connections.mercado.database'))->dropIfExists('ncms');
    }
}
