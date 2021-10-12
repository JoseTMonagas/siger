<?php

namespace App\Http\Controllers;

use App\Horario;
use App\Http\Requests\StoreHorario;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    /**
     * Display a detail of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Horario $horario)
    {
        return view('compass.horarios.index')->with(compact('horario'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $empresas = \App\Empresa::all();
        return view('compass.horarios.create')->with(compact('empresas'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $horario = new Horario;
        $horario->fecha_creacion_inicio = $request->input('fechaCreacionInicio');
        $horario->hora_creacion_inicio = $request->input("horaCreacionInicio");
        $horario->fecha_creacion_fin = $request->input("fechaCreacionFin");
        $horario->hora_creacion_fin = $request->input("horaCreacionFin");
        $horario->fecha_validacion_inicio = $request->input("fechaValidacionInicio");
        $horario->hora_validacion_inicio = $request->input("horaValidacionInicio");
        $horario->fecha_validacion_fin = $request->input("fechaValidacionFin");
        $horario->hora_validacion_fin = $request->input("horaValidacionFin");

        $empresa =  \App\Empresa::findOrFail($request->input('empresa'));
        $old =$empresa->horario()->first();
        if (isset($old)) $old->delete();
        $horario->empresa()->associate($empresa);

        if ($horario->saveOrFail()) {
            $msg = [
                "meta" => [
                    "title" => "Horario asignado exitosamente",
                    "msg" => "El Horario fue asignado con exito a la empresa " . $empresa->razon_social
                ]
            ];

            return redirect()->route('empresas.index')->with(compact('msg'));
        } else {
            $msg = [
                "meta" => [
                    "title" => "Horario no pudo ser asignado",
                    "msg" => "La asignacion no se pudo realizar"
                ]
            ];

            return redirect()->route('empresas.index')->with(compact('msg'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function edit(Horario $horario)
    {
        return view('compass.horarios.edit')->with(compact('horario'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Horario $horario)
    {
        $horario = Horario::find($horario->id);

        $horario->fecha_creacion_inicio = $request->input('fechaCreacionInicio');
        $horario->hora_creacion_inicio = $request->input("horaCreacionInicio");
        $horario->fecha_creacion_fin = $request->input("fechaCreacionFin");
        $horario->hora_creacion_fin = $request->input("horaCreacionFin");
        $horario->fecha_validacion_inicio = $request->input("fechaValidacionInicio");
        $horario->hora_validacion_inicio = $request->input("horaValidacionInicio");
        $horario->fecha_validacion_find = $request->input("fechaValidacionFin");
        $horario->hora_validacion_fin = $request->input("horaValidacionFin");

        $empresa =  \App\Empresa::findOrFail($request->input('empresa'));
        $horario->empresas()->associate($empresa);

        if ($horario->saveOrFail()) {
            $msg = [
                "meta" => [
                    "title" => "Horario asignado exitosamente",
                    "msg" => "El Horario fue asignado con exito a la empresa " . $empresa->razon_social
                ]
            ];

            return redirect()->route('horarios.index')->with(compact('msg'));
        } else {
            $msg = [
                "meta" => [
                    "title" => "Horario no pudo ser asignado",
                    "msg" => "La asignacion no se pudo realizar"
                ]
            ];

            return redirect()->route('horarios.index')->with(compact('msg'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Horario $horario)
    {
        $horario->delete();

        $msg = [
            "meta" => [
                "title" => "Horario eliminado",
                "msg" => "El horario fue eliminado exitosamente"
            ]
        ];

        return redirect()->route('horarios.index')->with(compact('msg'));
    }
}
