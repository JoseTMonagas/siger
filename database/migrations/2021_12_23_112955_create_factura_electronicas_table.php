<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacturaElectronicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('factura_electronicas', function (Blueprint $table) {
            $table->id();
            $table->foreignId("cierre_id")->constrained();
            $table->date("fecha");
            $table->string("folio");
            $table->string("monto");
            $table->string("documento")->nullable();
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
        Schema::dropIfExists('factura_electronicas');
    }
}
