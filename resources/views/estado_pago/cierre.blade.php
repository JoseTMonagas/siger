@extends('layouts.app')

@section('title', 'Cierre Estado de Pago | MLine SIGER')

@section('home-route', route('compass.home'))
@section('nav-menu')
@include('compass.menu')
@endsection

@section('main')
<div class="container">
    <div class="card">
        <h3 class="card-header font-bold text-xl">Cierre Estado de Pago</h3>
        <div class="card-body">
            <div class="d-flex flex-row mb-2">
            </div>
            <div class="container mt-2">
                <form action="{{ route("estado_pago_generar_cierre") }}" method="POST" accept-charset="utf-8">
                    @csrf

                    <div class="row">
                        <div class="form-group col-md-3 d-flex flex-col">
                            <label class="" for="inicio">Mes:</label>
                            <span class="">
                                <select name="mes" class="form-control">
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                </select>
                                <p class="text-muted">Obligatorio</p>
                            </span>
                        </div>

                        <div class="form-group col-md-4 d-flex flex-col">
                            <label for="empresa">Empresa:</label>
                            <span>
                                <autoselect :items='@json($empresas)' item-text="razon_social" item-value="id" name="empresa"></autoselect>
                            </span>
                        </div>
                    </div>

                    <button class="btn btn-primary my-5">Generar cierre estado de pago</button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection