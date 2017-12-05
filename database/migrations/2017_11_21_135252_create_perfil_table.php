<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePerfilTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfil', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rol');
            $table->string('plantilla', 45);
            $table->char('activo', 1);
            $table->timestamps();
        });

        Schema::create('recurso', function (Blueprint $table) {
            $table->increments('id');
            $table->string('modulo', 45);
            $table->string('controlador', 45);
            $table->string('accion', 45);
            $table->string('recurso', 100);
            $table->text('descripcion');
            $table->char('activo', 1);
            $table->char('custom', 1);
            $table->timestamps();
        });

        Schema::create('recurso_perfil', function(Blueprint $table)
        {
            $table->increments('id');
            $table->integer('recurso_id')->unsigned();
            $table->foreign('recurso_id')->
                references('id')->
                on('recurso');
         
            $table->integer('perfil_id')->unsigned();
            $table->foreign('perfil_id')->
                references('id')->
                on('perfil');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recurso_perfil');
        Schema::dropIfExists('perfil');
        Schema::dropIfExists('recurso');
    }
}
