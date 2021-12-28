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
        <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Control de Factura Electronica</h3>
        <div class="card-body">
            <div class="container mt-2">
                <header class="d-flex flex-row justify-content-end my-2">
                    <a class="btn btn-outline-success" href="{{ route("factura_electronica_create", $cierre) }}">Crear FE</a>
                </header>
                <table class="table table-sm" id="datatable">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Folio</th>
                            <th>Monto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($facturas as $factura)
                        <tr>
                            <td>{{ $factura->fecha  }}</td>
                            <td>{{ $factura->folio  }}</td>
                            <td>{{ $factura->monto  }}</td>
                            <td>
                                <div class="d-inline-flex justify-content-around">
                                    <a href="{{ asset($factura->documento) }}" target="_BLANK">Ver PDF</a>
                                    <a href="{{ route("factura_electronica_edit", $factura) }}" class="btn btn-warning mx-2">Editar</a>
                                    <form method="POST" action="{{ route('factura_electronica_delete', $factura)  }}">
                                        @csrf
                                        @method("DELETE")

                                        <button class="btn btn-danger" type="submit">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection