<?php

use App\TipoObservacion;
use Illuminate\Database\Seeder;

class TipoObservacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TipoObservacion::create([
            "estado" => "ACEPTADO",
            "nombre" => "N/A",
            "cantidad" => false
        ]);

        TipoObservacion::create([
            "estado" => "RECHAZO",
            "nombre" => "Productos Faltantes (Totalidad)",
            "cantidad" => false
        ]);

        TipoObservacion::create([
            "estado" => "RECHAZO",
            "nombre" => "Productos Faltantes (Parcial)",
            "cantidad" => true
        ]);

        TipoObservacion::create([
            "estado" => "OBSERVACION",
            "nombre" => "Productos en mal estado",
            "cantidad" => true
        ]);

        TipoObservacion::create([
            "estado" => "OBSERVACION",
            "nombre" => "Productos Vencidos",
            "cantidad" => true
        ]);

        TipoObservacion::create([
            "estado" => "OBSERVACION",
            "nombre" => "Productos por vencer",
            "cantidad" => true
        ]);

        TipoObservacion::create([
            "estado" => "OBSERVACION",
            "nombre" => "Envases deteriorados",
            "cantidad" => true
        ]);
    }
}
