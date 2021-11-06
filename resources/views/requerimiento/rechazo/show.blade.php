@extends('layouts.app')

@section('title', 'Ver Observaciones | Mline SIGER')

@section('home-route', route('cliente.home'))

@section('nav-menu')
@include('cliente.menu')
@endsection

@section('main')
<div class="card">
    <h3 class="card-header font-bold text-xl">
        {{ Auth::user()->userable->nombre }}:
        Productos con Observaciones en Guia de Despachos
    </h3>
    <div class="card-body">
        <div class="row">

        </div>
        <div class="row justify-content-center align-items-center">
            <div class="col-md-10">
                <index-component :headers="[
                { text: 'Folio Guia', value: 'guia.folio' },
                { text: 'Detalle', value: 'producto.detalle' },
                { text: 'Cantidad', value: 'producto.pivot.cantidad_recibido' },
                { text: 'Motivo', value: 'motivo.nombre' },
                ]" :items='@json($observados)'></index-component>
            </div>

        </div>
    </div>

</div>
@endsection