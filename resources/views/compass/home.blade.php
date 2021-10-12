@extends('layouts.app')

@section('title', 'Compass SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <div class="card-header font-bold text-xl">{{
            Auth::user()->getNombreRelacionado() }}: Dashboard</div>
            <div class="card-body">
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-tachometer-alt"></i> Accesos Directo:</h3>
                        <div class="d-flex flex-row justify-content-around align-items-end">
                            @if (Auth::user()->userable->name == 'Compras')
                                <a class="btn btn-outline-primary" href="{{ route('compass.pedidos.verificar')}}">
                                    <i class="fas fa-tasks"></i>
                                    Verificar Ordenes de Pedido
                                </a>
                                <a class="btn btn-outline-primary" href="{{ route('cargarFolios') }}">
                                    <i class="fas fa-tasks"></i>
                                    Cargar Folios
                                </a>
                                <a class="btn btn-outline-primary" href="{{ route('usuarios.index') }}">
                                    <i class="fas fa-tasks"></i>
                                    Lista de Usuarios
                                </a>
                            @else
                                <a class="btn btn-outline-primary" href="{{ route('compass.pedidos.cajasIndex')}}">
                                    <i class="fas fa-tasks"></i>
                                    Armar Cajas
                                </a>
                                <a class="btn btn-outline-primary" href="{{ route('compass.pedidos.programarDespachos') }}">
                                    <i class="fas fa-tasks"></i>
                                    Programar Despachos
                                </a>
                                <a class="btn btn-outline-primary" href="{{ route('compass.pedidos.despachar') }}">
                                    <i class="fas fa-tasks"></i>
                                    Despachar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card mb-3">
                    <div class="card-body">
                        <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-chart-line"></i> Reportes:</h3>
                        @component('partials.index',
                            ['type' => 2,
                            'empresas' =>
                            \App\Empresa::all()])
                        @endcomponent
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <h3 class="font-bold text-md border-bottom mb-3"><i class="fas fa-bell"></i> Ultimas Notificaciones:</h3>
                        @component('partials.notifications', ['notifications' => $notifications])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
