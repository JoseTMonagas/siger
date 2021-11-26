<?php

namespace App\Http\Controllers;

use App\Centro;
use App\Empresa;
use App\Exports\CierreEstadoPago;
use App\GuiaDespacho;
use App\Mail\Reclamo;
use App\Mail\EstadoPagoActualizado;
use App\Producto;
use App\TipoObservacion;
use App\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class EstadoPagoController extends Controller
{

    public function cuadroEstadoGeneral(Request $request)
    {
        $type = Auth::user()->userable;

        if ($type instanceof \App\CompassRole) {
            $empresas = Empresa::all();
            return view("estado_pago.general", compact("empresas"));
        }

        return view("estado_pago.general");
    }

    public function generateEstadoGeneral(Request $request)
    {
        $inicio = $request->input("inicio");
        $fin = $request->input("fin");

        $guias = GuiaDespacho::whereHas("productos", function (Builder $query) {
            $query->whereIn("tipo_observacion_id", [2, 3, 4, 5, 6, 7, 8]);
        });

        if (isset($inicio)) {
            $guias = $guias->whereDate("fecha", ">=", $inicio);
        } else {
            $inicio = Carbon::now()->subMonth();
            $guias = $guias->whereDate("fecha", ">=", $inicio->format("Y-m-d"));
        }

        if (isset($fin)) {
            $guias = $guias->whereDate("fecha", "<=", $fin);
        } else {
            $fin = Carbon::now();
            $guias = $guias->whereDate("fecha", "<=", $fin->format("Y-m-d"));
        }

        $centros = [];
        if (null !== $request->input("empresa_id")) {
            $empresa = Empresa::find($request->input("empresa_id"));
            $centros = $empresa->centros;
        } else {
            $empresa = Auth::user()->userable;
            $centros = $empresa->centros;
        }
        $guias = $guias->whereHas("requerimiento", function (Builder $query) use ($centros) {
            $query->whereIn("centro_id", $centros->modelKeys());
        });

        $guias = $guias->orderBy("nombre_centro", "asc")->get();

        $aceptadas = collect();
        $rechazadas = collect();
        $observadas = collect();

        foreach ($guias as $guia) {
            if ($guia->hasAceptadas()) {
                $aceptadas->push($guia);
            }

            if ($guia->hasRechazadas()) {
                $rechazadas->push($guia);
            }

            if ($guia->hasObservadas()) {
                $observadas->push($guia);
            }
        }



        $empresas = [];
        if (Auth::user()->userable instanceof \App\CompassRole) {
            $empresas = Empresa::all();
        }

        return view("estado_pago.general", compact("aceptadas", "rechazadas", "observadas", "empresas"));
    }

    public function concepto(GuiaDespacho $guiaDespacho, TipoObservacion $tipoObservacion)
    {
        $productos = $guiaDespacho->productos()->wherePivot("tipo_observacion_id", $tipoObservacion->id)->get();
        $storeRoute = route("estado_pago_concepto_store", [$guiaDespacho]);
        $actualizacionRoute = route("estado_pago_actualizado", [$guiaDespacho, $tipoObservacion]);
        $observaciones = TipoObservacion::all();

        return view("estado_pago.concepto", compact("guiaDespacho", "tipoObservacion", "productos", "storeRoute", "observaciones", "actualizacionRoute"));
    }

    public function conceptoStore(GuiaDespacho $guiaDespacho, Request $request)
    {
        $producto = $request->input("producto");
        $guiaDespacho->productos()->updateExistingPivot($producto["id"], $producto["pivot"]);

        return response()->json($producto);
    }

    public function conceptoMassiveStore(GuiaDespacho $guiaDespacho, Request $request)
    {
        $productos = $request->input("productos");

        foreach ($productos as $producto) {
            $guiaDespacho->productos()->updateExistingPivot($producto["id"], $producto["pivot"]);
        }

        return response()->json($productos);
    }

    public function generarReclamo(GuiaDespacho $guiaDespacho, Producto $producto, Request $request)
    {
        $user = Auth::user();
        $destinatarios = User::where("userable_type", "App\CompassRole")->where("userable_id", 1)->get();
        $message = $request->input("message");
        Mail::to($destinatarios)->send(new Reclamo($guiaDespacho, $producto, $user, $message));
        return response()->json();
    }

    public function enviarActualizacion(GuiaDespacho $guiaDespacho, TipoObservacion $tipoObservacion)
    {
        $users = $guiaDespacho->requerimiento->getUserByRequerimiento();
        $emails = [];
        foreach ($users as $user) {
            $emails[] = $user->email;
        }
        Mail::to($emails)->send(new EstadoPagoActualizado($guiaDespacho, $tipoObservacion));
        return response()->json();
    }

    public function resumen(Request $request)
    {
        $user = Auth::user();
        $empresas = null;
        $centros = null;
        $zonas = null;

        if ($user->userable instanceof \App\CompassRole) {
            $empresas = Empresa::all();
            $centros = Centro::all();
            $zonas =  \App\Abastecimiento::all();
        } else {
            $centros = $user->userable->centros;
        }


        return view("estado_pago.resumen", compact("empresas", "centros", "zonas"));
    }

    public function generarResumen(Request $request)
    {
        $inicio = $request->input("inicio");
        $fin = $request->input("fin");

        $requerimientos = collect();

        if (isset($request->empresas)) {
            $empresas = explode(",", $request->empresas);
            foreach ($empresas as $empresa) {
                $empresa = \App\Empresa::find($empresa);
                $reqEmpresas = $empresa->requerimientos()
                    ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                        $query->whereDate("fecha", ">=", $inicio)
                            ->whereDate("fecha", "<=", $fin)
                            ->whereNotNull("febos_id");
                    })
                    ->orderBy("created_at")
                    ->get();
                $reqEmpresas->load("guiasDespacho");
                $requerimientos->push($reqEmpresas);
            }
            $requerimientos = $requerimientos->flatten();
        } elseif (isset($request->centros)) {
            $centros = explode(",", $request->centros);
            $requerimientos = \App\Requerimiento::whereIn("centro_id", $centros)
                ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                    $query->whereDate("fecha", ">=", $inicio)
                        ->whereDate("fecha", "<=", $fin)
                        ->whereNotNull("febos_id");
                })
                ->orderBy("created_at")
                ->get();
            $requerimientos->load("guiasDespacho");
        } elseif (isset($request->zonas)) {
            $abastecimientos = explode(",", $request->zonas);
            $centros = \App\Centro::whereIn("zona", $abastecimientos)->pluck("id")->toArray();
            $requerimientos = \App\Requerimiento::whereIn("centro_id", $centros)
                ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                    $query->whereDate("fecha", ">=", $inicio)
                        ->whereDate("fecha", "<=", $fin)
                        ->whereNotNull("febos_id");
                })
                ->orderBy("created_at")
                ->get();
            $requerimientos->load("guiasDespacho");
        }

        $guiasDespacho = GuiaDespacho::whereIn("requerimiento_id", $requerimientos->pluck("id"))->distinct()->get();

        $user = Auth::user();
        $empresas = null;
        $centros = null;
        $zonas = null;

        if ($user->userable instanceof \App\CompassRole) {
            $empresas = Empresa::all();
            $centros = Centro::all();
            $zonas =  \App\Abastecimiento::all();
        } else {
            $centros = $user->userable->centros;
        }


        return view("estado_pago.resumen", compact("empresas", "centros", "zonas", "guiasDespacho"));
    }

    public function cierre(Request $request)
    {
        $empresas = Empresa::all();
        return view("estado_pago.cierre", compact("empresas"));
    }

    public function marcarGuiaLiquidado(GuiaDespacho $guiaDespacho)
    {
        $guiaDespacho->liquidado = Carbon::now();
        $guiaDespacho->save();

        return back();
    }

    public function notaCredito(GuiaDespacho $guiaDespacho)
    {
        $neto = 0;
        $productos = $guiaDespacho->productos()->wherePivot("genera_nc", true)->get()->map(function (Producto $producto) {
            $cantidad = 0;
            if ($producto->pivot->cantidad_recibido > 0) {
                $cantidad = $producto->pivot->real - $producto->pivot->cantidad_recibido;
            } else {
                $cantidad = $producto->pivot->real;
            }
            $producto["subtotal"] =  abs($cantidad * $producto->pivot->precio);
            return $producto;
        });
        foreach ($productos as $producto) {
            $neto += $producto["subtotal"];
        }
        $iva = $neto * 0.19;
        $total = $neto + $iva;
        return view("estado_pago.nota-credito", compact("guiaDespacho", "productos", "neto", "iva", "total"));
    }

    public function generarCierre(Request $request)
    {
        $mes = $request->input("mes");
        $inicio = Carbon::create(null, $mes, 1, 0, 0, 0)->startOfMonth();
        $fin = Carbon::create(null, $mes, 1, 0, 0, 0)->endOfMonth();
        $empresa = Empresa::findOrFail($request->input("empresa"));

        $requerimientos = $empresa->requerimientos()
            ->whereIn("estado", ["RECIBIDO", "RECIBIDO CON OBSERVACIONES"])
            ->whereHas("guiasDespacho", function ($query) use ($inicio, $fin) {
                $query->whereDate("fecha", ">=", $inicio)
                    ->whereDate("fecha", "<=", $fin)
                    ->whereNotNull("febos_id");
            })
            ->orderBy("centro_id")
            ->orderBy("created_at")
            ->get()
            ->load("guiasDespacho");

        $estadoPago = [
            ["ESTADO DE PAGO"],
            [""],
            ["PERIODO", $fin->format("M - Y")],
            ["CONTRATO", $empresa->razon_social],
            ["SECTOR", "PUERTO MONTT"],
            ["SERVICIOS", "VIVERES"],
            ["RESPONSABLE", ""],
            [""],
            ["RESUMEN SERVICIOS MENSUAL"],
            ["SERVICIOS", "UNIT", "NETO", "TOTAL", "RESPALDOS"]
        ];

        $detViveres = [
            ["PACK ABASTECIMIENTO CENTROS {$fin->format('M Y')}"],
            [''],
            [
                'FECHA',
                'BODEGA ORIGEN',
                'TRATAMIENTO',
                'CENTRO',
                'GUIA',
                'PRODUCTO',
                'CANTIDAD',
                'UNIT',
                'TOTAL',
                'OBSERVACIONES'
            ]
        ];

        $detGuia = [
            ["CENTRO", "FECHA", "GUIA", "TOTAL"]
        ];


        if ($requerimientos->count() > 0) {
            $total = 0;
            foreach ($requerimientos as $requerimiento) {
                if ($requerimiento->guiasDespacho->count() > 0) {
                    $guias = $requerimiento->guiasDespacho()->orderBy("created_at", "asc")->get();
                    $totalGuias = 0;
                    foreach ($guias as $guiaDespacho) {
                        if ($guiaDespacho->productos->count() > 0) {
                            foreach ($guiaDespacho->productos as $producto) {
                                $cantidad = $producto->pivot->cantidad_recibido ?? $producto->pivot->real;
                                $subtotal = $producto->pivot->precio * $cantidad;
                                $total += $subtotal;
                                $totalGuias += $subtotal;
                                $detViveres[] = [
                                    date("d/m/Y", strtotime($guiaDespacho->created_at)),
                                    'PTO MONTT',
                                    'VTA',
                                    $requerimiento->centro->nombre,
                                    $guiaDespacho->folio,
                                    $producto->detalle,
                                    $cantidad,
                                    $producto->pivot->precio,
                                    $subtotal,
                                    $producto->pivot->observaciones
                                ];
                            }

                            $detGuia[] = [$requerimiento->centro->nombre, $guiaDespacho->fecha, $guiaDespacho->folio, $guiaDespacho->liquidacion];
                        }
                    }
                    $detGuia[] = ["", "Total {$guiaDespacho->fecha}", "", $totalGuias];
                }
            }
            $estadoPago[] = ["VIVERES MES CENTRO LOGISTICO", 1, $total, $total, ""];
        }

        $export = new CierreEstadoPago($estadoPago, $detViveres, $detGuia);
        return Excel::download($export, "cierre-{$empresa->razon_social}.xlsx");
    }
}
