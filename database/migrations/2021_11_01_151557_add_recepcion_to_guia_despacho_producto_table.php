<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRecepcionToGuiaDespachoProductoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('guia_despacho_producto', function (Blueprint $table) {
            $table->unsignedBigInteger("tipo_observacion_id")->default(1);
            $table->unsignedDouble("cantidad_recibido")->nullable();
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
            $table->dropColumn("tipo_observacion_id");
            $table->dropColumn("cantidad_recibido");
        });
    }
}
