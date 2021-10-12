@extends('layouts.app')

@section('title', 'Lista de Pedidos | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\CompassRole))
    @section('home-route', route('compass.home'))
@else
    @section('home-route', route('cliente.home'))
@endif

@section('nav-menu')
    @if (Auth::user()->userable instanceof \App\CompassRole)
        @include('compass.menu')
    @else
        @include('cliente.menu')
    @endif
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Lista de Ordenes de Pedido</h3>
            <div class="card-body">
                <div class="container mt-2">
                    @component('partials.index',
                            ['type' => 2,
                            'empresas' =>
                            $empresas])
                        @endcomponent
                </div>
            </div>
        </div>
    </div>
@endsection
