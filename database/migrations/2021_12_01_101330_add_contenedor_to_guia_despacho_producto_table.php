<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContenedorToGuiaDespachoProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guia_despacho_producto', function (Blueprint $table) {
            $table->boolean("contenedor")->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('guia_despacho_producto', function (Blueprint $table) {
            $table->dropColumn("contenedor");
        });
    }
}
