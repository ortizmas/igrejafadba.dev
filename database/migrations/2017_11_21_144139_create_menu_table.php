<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_id')->unsigned(); // INT(10) unsigned;
            $table->integer('recurso_id')->unsigned();
            $table->foreign('recurso_id')->references('id')->on('recurso');
            $table->string('nome', 100);
            $table->string('url', 100);
            $table->integer('posicion')->unsigned(); // INT(10) unsigned;
            $table->string('icono', 45);
            $table->char('activo', 1);
            $table->char('visibilidad', 1);
            $table->char('custom', 1);
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
        Schema::dropIfExists('menu');
    }
}
