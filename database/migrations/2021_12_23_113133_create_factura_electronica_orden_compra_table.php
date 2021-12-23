<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaElectronicaOrdenCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_electronica_orden_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId("factura_electronica_id")->constrained();
            $table->foreignId("orden_compra_id")->constrained();
            $table->string("monto");
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
        Schema::dropIfExists('factura_electronica_orden_compra');
    }
}
