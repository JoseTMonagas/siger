<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCommentsToGuiaDespachoProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guia_despacho_producto', function (Blueprint $table) {
            $table->string("comentario_centro")->nullable();
            $table->string("comentario_reclamo")->nullable();
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
            $table->dropColumn("comentario_centro");
            $table->dropColumn("comentario_reclamo");
        });
    }
}
