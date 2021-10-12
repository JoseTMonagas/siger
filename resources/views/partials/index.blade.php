@if ($type === 0)
<table id="datatable" class="table">
    <thead>
        <tr>
            <th scope="col">Nombre</th>
            <th scope="col">Folios</th>
            <th scope="col">Estado</th>
            @if ((Auth::user()->userable instanceof \App\Centro))
            <th scope="col">Libreria</th>
            @endif
            <th scope="col">Fecha de Creacion</th>
            <th scope="col">Ultima Actualizacion</th>
            <th scope="col">Accion</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requerimientos as $requerimiento)
        <tr>
            <td>
                <a href="{{ route('pedidos.show', $requerimiento) }}">
                    {{ $requerimiento->nombre }}
                </a>
            </td>
            <td>
                {{ $requerimiento->folio ?? "N/A"  }}
            </td>
            <td>{{ $requerimiento->estado }}</td>
            @if ((Auth::user()->userable instanceof \App\Centro))
            <td>
                <agregar-libreria-component action="{{
                                        route('libreria.editar', $requerimiento)
                                        }}" :library='@json(Auth::user()
                                        ->hasRequerimiento($requerimiento))'></agregar-libreria-component>
            </td>
            @endif
            <td>{{ $requerimiento->created_at }}</td>
            <td>{{ $requerimiento->updated_at }}</td>
            <td>
                <div class="btn-group" role="group">
                    @if (Auth::user()->userable instanceof \App\Centro)
                    @if ( $requerimiento->estado === 'DESPACHADO')
                    <a class="btn btn-outline-success" href="{{ route(
                                                 'pedidos.recepcion',
                                                 $requerimiento) }}">
                        Recepcion de Pedido
                    </a>
                    @endif
                    @if ( $requerimiento->estado === 'RECIBIDO CON OBSERVACIONES')
                    <a class="btn btn-outline-info" href="{{ route(
                                                 'rechazos.show',
                                                 $requerimiento) }}">
                        Ver Observaciones
                    </a>
                    @endif
                    @endif
                    @if ( $requerimiento->estado === 'DESPACHADO')
                    <modal-btn-component title="Orden de Pedido" :message='[
                                           { data: @json([
                                               "nombre" => $requerimiento->transporte->nombre_chofer,
                                               "rut" => $requerimiento->transporte->rut_chofer,
                                               "contacto" => $requerimiento->transporte->contacto
                                           ])
                                           , type: "Object", keys: ["nombre",
                                           "rut", "contacto"]}
                                           ]'>
                        Ver Transporte
                    </modal-btn-component>
                    @endif
                </div>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@elseif ($type === 1)
<div class="table-responsive">
    <table id="datatable" class="table table-sm">
    <thead>
        <tr>
            <th scope="col" rowspan="2">Nombre</th>
            <th scope="col" rowspan="2">Accion</th>
            <th class="text-center" scope="row" colspan="{{ \App\Estado::all()->count() }}">Estados</th>
        </tr>
        <tr>
            @foreach(\App\Estado::all() as $estado)
                <th scope="col">{{ $estado->nombre }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($centros as $centro)
        <tr>
            <td>{{ $centro->nombre }}</td>
            <td>
                <a href="{{ route('pedidos.centroIndex', ['centro' => $centro->id, 'estado' => '0']) }}">
                    Ver Detalles
                </a>
            </td>
            @foreach(\App\Estado::all() as $estado)
            <td>
                {{ count($centro->requerimientos()->where('estado', $estado->nombre)->get()) }}

            </td>
            @endforeach
            
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@elseif ($type === 2)
<div class="table-responsive">
    <table id="datatable" class="table table-sm">
    <thead>
        <tr>
            <th scope="col" rowspan="2">Nombre</th>
            <th scope="col" rowspan="2">Accion</th>
            <th class="text-center" scope="row" colspan="{{ \App\Estado::all()->count() }}">Estados</th>
        </tr>
        <tr>
            @foreach(\App\Estado::all() as $estado)
                <th scope="col">{{ $estado->nombre }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @foreach ($empresas as $empresa)
        <tr>
            <td>{{ $empresa->razon_social }}</td>
            <td>
                <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => 0])}}">
                    Ver Todos
                </a>
            </td>
            @foreach(\App\Estado::all() as $estado)
            <td>
                <a href="{{ route('pedidos.indexCentro', ['empresa' => $empresa, 'estado' => $estado->id])}}">
                    {{ count($empresa->getRequerimientoByEstado($estado->nombre)) }}
                </a>
            </td>
            @endforeach
            
        </tr>
        @endforeach
    </tbody>
</table>
</div>
@endif