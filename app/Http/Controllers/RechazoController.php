<?php

namespace App\Http\Controllers;

use App\Rechazo;
use Illuminate\Http\Request;

class RechazoController extends Controller
{
    public function show(\App\Requerimiento $requerimiento)
    {
        $rechazos = $requerimiento->productosRechazados;
        $rechazos->load("guiaDespacho", "producto");
        $rechazos->each(function ($rechazo) {
            $rechazo["cantidad"] = $rechazo->productoGuia->pivot->real;
        });

        return view("requerimiento.rechazo.show", compact("rechazos"));
    }

    public function estadoView(\App\GuiaDespacho $guiaDespacho=null)
    {
        if (isset($guiaDespacho)) {
            
            $rechazos = $guiaDespacho->rechazos;
        } else {
            $empresa = \Auth::user()->userable;
            $requerimientos = $empresa->requerimientos()->where("estado", "RECIBIDO CON OBSERVACIONES")->get();
            $requerimientos->load("productosRechazados");
            $rechazos = $requerimientos->flatMap(function($requerimiento) {
                return $requerimiento->productosRechazados;
            });
        }

        return view("requerimiento.rechazo.estado", compact("rechazos"));
    }

    public function cambiarEstado(\App\Rechazo $rechazo, Request $request)
    {
        $rechazo->estadoPago = $request->state;
        $rechazo->save();

        return response('OK', 200);
    }

    public function guardarEstados(Request $request)
    {
        Rechazo::whereIn("id", json_decode($request->rechazos))
                  ->update(["cierre" => true]);

        return redirect()->route("home");

    }
}
