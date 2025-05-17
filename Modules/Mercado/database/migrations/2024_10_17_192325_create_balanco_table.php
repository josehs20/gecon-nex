<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBalancoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mercado')->create('balanco', function (Blueprint $table) {
            $table->id();

            // Foreign key fields
            $table->unsignedBigInteger('loja_id');
            $table->unsignedBigInteger('status_id');
            $table->unsignedBigInteger('usuario_id');
            $table->longText('observacao')->nullable();

            // Timestamps for created_at and updated_at
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('loja_id')->references('id')->on('lojas')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->foreign('usuario_id')->references('id')->on('usuarios')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('balanco');
    }
}
