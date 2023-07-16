<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Compras', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('mount');
            $table->unsignedBigInteger('id_cliente')->nullable();
            $table->unsignedBigInteger('id_producto')->nullable();
            $table->foreign('id_cliente')->references('id')->on('Clientes');
            $table->foreign('id_producto')->references('id')->on('Productos');
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
        Schema::dropIfExists('Compras');
    }
};
