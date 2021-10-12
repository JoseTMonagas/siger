@extends('layouts.app')

@section('title', 'Validar Ordenes de Pedido')

@section('home-route', route('cliente.home'))

@section('nav-menu')
    @include('cliente.menu')
@endsection

@section('main')
    <div class="container">

        <div class="card">
            @if (count($centros) > 0)
                <div class="card-header">
                    <div class="d-flex flex-row">
                        <div class="btn-group" role="group" aria-label="Validar Pedidos">
                            <validar-pedidos-component action="{{ route('pedidos.aceptarTodos') }}" :todos="true">Aceptar Todos</validar-pedidos-component>
                            <validar-pedidos-component action="{{ route('pedidos.rechazarTodos') }}" :todos="true" :validacion="false">Rechazar Todos</validar-pedidos-component>
                        </div>
                    </div>
                </div>
            @endif
            <div class="card-body">
                @if (count($centros) > 0)
                    <tabs>
                    @foreach ($centros as $centro)
                        <tab title="{{$centro->nombre}}">
                        <table id="datatable" class="table">
                            <thead>
                                <tr>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Ver</th>
                                    <th scope="col">Editar</th>
                                    <th scope="col">Aceptar</th>
                                    <th scope="col">Rechazar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($centro->requerimientos()->where('estado', 'ESPERANDO VALIDACION')->get() as $requerimiento)
                                    <tr>
                                        <td><a href="{{ route('pedidos.show', $requerimiento) }}">{{ $requerimiento->nombre }}</a></td>
                                        <td>{{ $requerimiento->estado }}</td>
                                        <td class="d-flex flex-row">
                                            <modal-btn-component
                                                title="Orden de Pedido"
                                                :message='[
                                                { data:
                                                @json($requerimiento->productos),
                                                type: "Array", keys: ["sku",
                                                "detalle",
                                                "pivot"], pivot: "cantidad"},
                                                { data: @json(["total" => "$" . number_format($requerimiento->getTotal()) ]), type: "Object", keys: ["total"]}
                                                ]'>Ver Orden de Pedido</modal-btn-component>
                                            </modal-btn-component>
                                        </td>
                                        <td>
                                          <a
                                            class="btn btn-info"
                                            href="{{ route("requerimientos.edit", $requerimiento) }}">Editar</a>
                                        </td>
                                        <td>
                                            <validar-pedidos-component
                                                action="{{ route('pedidos.aceptar') }}"
                                                :todos="false" :pedido='{{ $requerimiento->id }}'>Aceptar</validar-pedidos-component>
                                        </td>
                                        <td>
                                            <validar-pedidos-component
                                                action="{{ route('pedidos.rechazar') }}"
                                                :todos="false" :validacion="false" :pedido='{{ $requerimiento->id }}'>Rechazar</validar-pedidos-component>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        </tab>
                    @endforeach
                    </tabs>
                @else
                    <div class="alert alert-dark">No hay Ordenes de Pedidos pendientes</div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    @if (\Session::has('msg'))
        @php
            $msg = \Session::get('msg')
        @endphp
        <script charset="utf-8">
            (Swal.fire({
                icon: "success",
                title: "{{$msg['title']}}",
                text: "{{$msg['text']}}"
            }))()
        </script>
    @endif
@endsection
