@extends('layouts.app')

@section('title', 'Asignar Horario a Empresas | Mline Siger')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Horario de Empresas</h3>
            <div class="card-body">
                <form action="{{ route('horarios.store') }}" method="POST" accept-charset="utf-8">
                    @csrf

                    <div class="form-group row">
                        <label class="col-sm-2 text-right" for="empresa">Empresa:</label>
                        <span class="col-sm-6">
                            <select class="form-control" name="empresa">
                                @foreach ($empresas as $empresa)
                                    <option value="{{ $empresa->id }}">{{ $empresa->razon_social }}</option>
                                @endforeach
                            </select>
                            <p class="text-muted">Obligatorio</p>
                        </span>
                    </div>

                    <div class="form-row justify-content-around">
                        <fieldset class="col-md-5 border p-4">
                            <legend class="text-sm font-black">Rango para la creacion de Pedidos</legend>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="fechaCreacionInicio">Desde:</label>
                                    <select class="form-control" name="fechaCreacionInicio">
                                        <option value="1">Lunes</option>
                                        <option value="2">Martes</option>
                                        <option value="3">Miercoles</option>
                                        <option value="4">Jueves</option>
                                        <option value="5">Viernes</option>
                                        <option value="6">Sabado</option>
                                        <option value="7">Domingo</option>
                                    </select>
                                    <p class="text-muted">Obligatorio</p>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="horaCreacionInicio">A las:</label>
                                    <input class="form-control" type="time" name="horaCreacionInicio">
                                    <p class="text-muted">Obligatorio</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="fechaCreacionFin">Hasta:</label>
                                    <select class="form-control" name="fechaCreacionFin">
                                        <option value="1">Lunes</option>
                                        <option value="2">Martes</option>
                                        <option value="3">Miercoles</option>
                                        <option value="4">Jueves</option>
                                        <option value="5">Viernes</option>
                                        <option value="6">Sabado</option>
                                        <option value="7">Domingo</option>
                                    </select>
                                    <p class="text-muted">Obligatorio</p>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="horaCreacionFin">A las:</label>
                                    <input class="form-control" type="time" name="horaCreacionFin">
                                    <p class="text-muted">Obligatorio</p>
                                </div>
                            </div>
                        </fieldset>
                        <fieldset class="col-md-5 border p-4">
                            <legend class="text-sm font-black">Rango para la Validacion de Pedidos</legend>
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="fechaValidacionInicio">Desde:</label>
                                    <select class="form-control" name="fechaValidacionInicio">
                                        <option value="1">Lunes</option>
                                        <option value="2">Martes</option>
                                        <option value="3">Miercoles</option>
                                        <option value="4">Jueves</option>
                                        <option value="5">Viernes</option>
                                        <option value="6">Sabado</option>
                                        <option value="7">Domingo</option>
                                    </select>
                                    <p class="text-muted">Obligatorio</p>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="horaValidacionInicio">A las:</label>
                                    <input class="form-control" type="time" name="horaValidacionInicio">
                                    <p class="text-muted">Obligatorio</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="fechaValidacionFin">Hasta:</label>
                                    <select class="form-control" name="fechaValidacionFin">
                                        <option value="1">Lunes</option>
                                        <option value="2">Martes</option>
                                        <option value="3">Miercoles</option>
                                        <option value="4">Jueves</option>
                                        <option value="5">Viernes</option>
                                        <option value="6">Sabado</option>
                                        <option value="7">Domingo</option>
                                    </select>
                                    <p class="text-muted">Obligatorio</p>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="horaValidacionFin">A las:</label>
                                    <input class="form-control" type="time" name="horaValidacionFin">
                                    <p class="text-muted">Obligatorio</p>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="row my-2">
                        <button class="mx-auto btn btn-success">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    @endsection
