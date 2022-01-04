<?php

namespace App\Http\Controllers;

use App\Centro;
use App\Empresa;
use App\Presupuesto;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PresupuestoController extends Controller
{
    /**
     * Lista de Empresas con su Presupuesto
     *
     * @return \Illuminate\Http\Response
     */
    public function indexHolding($year = null, $mes = null, $acumulado = 0)
    {
        $empresas = Auth::user()->userable->empresas()->get();
        $inicial = $empresas->map(function ($empresa) {
            return $empresa->getTotalPresupuestoByDate($mes, $year);
        })->reduce(function ($carry, $item) {
            return $carry + $item;
        });
        return view('presupuesto.index.holding')->with(compact('presupuestos'));
    }

    /**
     * Lista de Centros con su Presupuesto
     *
     * @return void
     */
    public function indexEmpresa(int $empresaId = null, Request $request)
    {
        $empresa = null;

        if ($empresaId) {
            $empresa = Empresa::find($empresaId);
        } else {
            if (Auth::user()->userable instanceof \App\Empresa) {
                $empresa = Auth::user()->userable;
            } else {
                return back();
            }
        }

        $centros = $empresa->centros;

        if ($request->has("year") && $request->has("month")) {
            $year = $request->input("year");
            $month = $request->input("month");
            $centroId = $request->input("centro_id");
            $acumulado = boolval($request->input("acumulado"));

            $inicial = null;
            if ($acumulado) {
                $inicial = $empresa->getTotalPresupuestoByDate(null, $year);
            } else {
                $inicial = $empresa->getTotalPresupuestoByDate($month, $year);
            }


            $centroPresupuesto = null;
            if ($centroId > 0) {
                $centroPresupuesto = Centro::where("id", $centroId)->get();
            } else {
                $centroPresupuesto = $empresa->centros()->get();
            }

            $requerimientos = $centroPresupuesto->map(function ($centro) use ($year, $month, $acumulado) {
                $query = $centro->requerimientos();
                if (!is_null($year)) {
                    $query = $query->whereYear('created_at', $year);
                }
                if (!is_null($month) && !($acumulado == 1)) {
                    $query = $query->whereMonth('created_at', $month);
                }
                return $query->get();
            });
            $date = Carbon::create($year, $month);
            return view('presupuesto.index.empresa')->with(compact('requerimientos', 'inicial', 'date', "centros"));
        }

        return view('presupuesto.index.empresa')->with(compact("centros"));
    }

    /**
     * Lista de Cuenta segun su Presupuesto
     *
     * @return \Illuminate\Http\Response
     */
    public function indexCentro($centroId = null, Request $request, $acumulado = 0, $soloMes = 1)
    {
        $year = $request->has('year') ? $request->get('year') : null;
        $mes = $request->has('mes') ? $request->get('mes') : null;
        if (is_null($centroId)) {
            $centro = Auth::user()->userable;
            $inicial = $centro->getTotalPresupuestoByDate($mes, $year);
        } else {
            $centro = \App\Centro::find($centroId);
            $inicial = $centro->getTotalPresupuestoByDate($mes, $year);
        }

        $date = Carbon::create($year ?? date("Y"), $mes ?? date("m"));

        $requerimientos = $centro->requerimientos();
        if (!is_null($year)) {
            $requerimientos = $requerimientos->whereYear('created_at', $date->year);
        }
        if (!is_null($mes)) {
            $requerimientos = $requerimientos->whereMonth('created_at', $date->month);
        }
        $requerimientos = $requerimientos->get();

        return view('presupuesto.index.centro')->with(compact('requerimientos', 'inicial', 'date'));
    }

    /**
     * Retorna el CMI de la Empresa
     *
     * @return \Illuminate\Http\Response
     */
    public function cmi($empresaId = null)
    {
        if (is_null($empresaId)) {
            $empresa = Auth::user()->userable;
            $centros = $empresa->centros()->get();
        } else {
            $empresa = \App\Empresa::findOrFail($empresaId);
            $centros = $empresa->centros()->get();
        }
        $cmi = $centros->map(function ($centro) {
            $iniciales = $centro->presupuestos()->latest()->whereYear("fecha_gestion", date("Y"))->limit(12)->get();
            $totales = $centro->getTotalByMes();

            return collect(['centro' => $centro, 'iniciales' => $iniciales, 'totales' => $totales]);
        });

        $totalPresupuesto = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])->map(function ($i) use ($empresa) {
            return $empresa->getTotalPresupuestoByDate($i, date("Y"));
        });

        $totalGasto = collect([1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12])->map(function ($i) use ($empresa) {
            return $empresa->getGastoByDate($i, date("Y"));
        });

        return view('presupuesto.index.cmi')->with(compact('cmi', 'totalPresupuesto', 'totalGasto'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        switch (get_class(Auth::user()->userable)) {
            case 'App\Holding':
                $empresas = Auth::user()->userable->empresas()->get();
                $presupuestos = collect($empresas->map(function ($empresa) {
                    return collect(['empresa' => $empresa, 'presupuesto' => $empresa->presupuestos()->get()]);
                }));
                return view('presupuesto.create')->with(compact('presupuestos'));
                break;
            case 'App\Empresa':
                $centros = Auth::user()->userable->centros()->get();
                $presupuestos = collect($centros->map(function ($centro) {
                    return collect(['centro' => $centro, 'presupuesto' => $centro->presupuestos()->get()]);
                }));
                return view('presupuesto.create')->with(compact('centros'));
                break;
            default:
                return redirect()->route('cliente.home');
                break;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $presupuestos = collect($request->input('input'));
        $year = $request->input('year');

        $empresa = \Auth::user()->userable;
        $presupuestos->map(function ($presupuesto) use ($year) {
            $centro = \App\Centro::find($presupuesto['centro']['id']);
            $presupuestos = collect($presupuesto['presupuesto']);
            $presupuestos->shift();
            $presupuestos->map(function ($presupuesto, $index) use ($centro, $year) {
                $centro->presupuestos()->create([
                    "monto" => $presupuesto,
                    "fecha_gestion" => Carbon::create($year, $index, 1)
                ]);
            });
        });

        $msg = [
            "meta" => [
                "title" => 'Presupuesto Guardado',
                "msg" => 'El Presupuesto fue guardado exitosamente'
            ]
        ];
        return response()->json($msg);
    }
}
