@extends('layouts.app')

@section('title', 'Factura Electronica | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\CompassRole))
@section('home-route', route('compass.home'))
@section('nav-menu')
@include('compass.menu')
@endsection
@else
@section('home-route', route('cliente.home'))
@section('nav-menu')
@include('cliente.menu')
@endsection
@endif

@section('main')
<div class="container">
    <div class="card">
        <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: @isset($facturaElectronica) Editar @else Crear @endisset Factura Electronica @isset($facturaElectronica) {{ $facturaElectronica->folio }} @endisset</h3>
        <div class="card-body">
            <div class="container mt-2">
                <form method="POST" action="{{ $route }}" enctype="multipart/form-data">
                    @csrf

                    <div class="d-flex justify-content-around">
                        <div class="d-inline-flex flex-column form-group">
                            <label for="">Fecha:</label>
                            <input required class="form-control" name="fecha" type="date" @isset($facturaElectronica) value="{{ $facturaElectronica->fecha }}" @endisset />
                        </div>
                        <div class="d-inline-flex flex-column form-group">
                            <label for="">Folio:</label>
                            <input required class="form-control" name="folio" type="text" @isset($facturaElectronica) value="{{ $facturaElectronica->folio }}" @endisset />
                        </div>
                        <div class="d-inline-flex flex-column form-group">
                            <label for="">Monto:</label>
                            <input required class="form-control" name="monto" type="text" @isset($facturaElectronica->monto) value="{{ $facturaElectronica->monto }}" @endisset />
                        </div>
                        <div class="d-inline-flex flex-column form-group">
                            <label for="">Documento:</label>
                            <input class="form-control" name="documento" type="file" value="" />
                        </div>

                        <button type="submit" class="btn btn-success">Guardar</button>
                    </div>

                    <div class="container">
                        <div class="row">
                            <div class="col-md-4">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>OC</th>
                                            <th>Monto</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($ordenes as $orden)
                                        <tr>
                                            <th>{{ $orden["folio"]  }}</th>
                                            <td>
                                                <input class="form-control" type="text" name="orden-{{$orden["id"]}}" @isset($orden["pivot"]) value="{{ $orden["pivot"]["monto"] }}" @endisset />
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>

                                </table>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection