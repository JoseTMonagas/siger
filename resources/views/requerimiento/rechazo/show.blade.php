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
            Productos Rechazados en Guia de Despachos
        </h3>
        <div class="card-body">
            <div class="row">

            </div>
          <div class="row justify-content-center align-items-center">
            <div class="col-md-10">
            <index-component :headers="[
                { text: 'Folio Guia', value: 'guia_despacho.folio' },
                { text: 'Detalle', value: 'producto.detalle' },
                { text: 'Cantidad', value: 'cantidad' },
                { text: 'Motivo', value: 'motivo' },
                ]" :items='@json($rechazos)'></index-component>
            </div>

          </div>
        </div>

    </div>
@endsection
