<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCentroNotaCreditoTributariaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('centro_nota_credito_tributaria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("nota_credito_tributaria_id");
            $table->foreignId("centro_id")->constrained();
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
        Schema::dropIfExists('centro_nota_credito_tributaria');
    }
}
