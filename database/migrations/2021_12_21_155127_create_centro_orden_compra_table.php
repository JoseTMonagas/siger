<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentroOrdenCompraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centro_orden_compra', function (Blueprint $table) {
            $table->id();
            $table->foreignId("centro_id")->constrained();
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
        Schema::dropIfExists('centro_orden_compra');
    }
}
