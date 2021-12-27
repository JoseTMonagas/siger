@extends('layouts.app')

@section('title', 'Conciliacion | Mline SIGER')

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
        <h3 class="card-header font-bold text-xl">{{ Auth::user()->getNombreRelacionado() }}: Conciliacion OC/NC por EP</h3>
        <div class="card-body">
            <div class="container mt-2">
                <div class="d-flex flex-column mb-4">
                    <span>
                        <b>Liquidacion:</b>
                        $ {{ number_format($cierre->monto, 0, ".", "") }}
                    </span>
                    <span>
                        <b>Factura:</b>
                        <a href="#" data-toggle="modal" data-target="#modalResumenFacturas">
                            $ {{ number_format($totalFacturasElectronica, 0, ".", "")  }}
                        </a>
                        <!-- Modal -->
                        <div class="modal fade" id="modalResumenFacturas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Resumen Facturas Electronicas</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-sm">
                                            <thead>
                                                <th>Fecha</th>
                                                <th>Folio</th>
                                                <th>Monto</th>
                                                <th>PDF</th>
                                            </thead>
                                            <tbody>
                                                @foreach($facturasElectronica as $factura)
                                                <tr>
                                                    <td>{{ $factura->fecha }}</td>
                                                    <td>{{ $factura->folio }}</td>
                                                    <td>{{ $factura->monto }}</td>
                                                    <td>
                                                        <a href="{{ asset($factura->documento)  }}" target="_BLANK">Ver PDF</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </span>
                    <span>
                        <b>Orden de Compra:</b>
                        <a href="#" data-toggle="modal" data-target="#modalResumenOC">
                            $ {{ number_format($totalOrdenesCompra, 0, ".", "")  }}
                        </a>
                        <!-- Modal -->
                        <div class="modal fade" id="modalResumenOC" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Resumen Ordenes de Compra</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-sm">
                                            <thead>
                                                <th>Fecha</th>
                                                <th>Folio</th>
                                                <th>Monto</th>
                                                <th>PDF</th>
                                            </thead>
                                            <tbody>
                                                @foreach($ordenesCompra as $orden)
                                                <tr>
                                                    <td>{{ $orden->fecha }}</td>
                                                    <td>{{ $orden->folio }}</td>
                                                    <td>{{ $orden->monto }}</td>
                                                    <td>
                                                        <a href="{{ asset($orden->documento)  }}" target="_BLANK">Ver PDF</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </span>
                    <span>
                        <b>Notas de Credito:</b>
                        <a href="#" data-toggle="modal" data-target="#modalResumenNC">
                            $ {{ number_format($totalNotasCredito, 0, ".", "")  }}
                        </a>
                        <!-- Modal -->
                        <div class="modal fade" id="modalResumenNC" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h4 class="modal-title" id="myModalLabel">Resumen Notas de Credito Tributaria</h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-sm">
                                            <thead>
                                                <th>Fecha</th>
                                                <th>Folio</th>
                                                <th>Monto</th>
                                                <th>PDF</th>
                                            </thead>
                                            <tbody>
                                                @foreach($notasCreditoTributaria as $notas)
                                                <tr>
                                                    <td>{{ $notas->fecha }}</td>
                                                    <td>{{ $notas->folio }}</td>
                                                    <td>{{ $notas->monto }}</td>
                                                    <td>
                                                        <a href="{{ asset($notas->documento)  }}" target="_BLANK">Ver PDF</a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </span>
                </div>

                <table class="table table-sm" id="datatable">
                    <thead>
                        <tr>
                            <th class="text-right">Centro</th>
                            <th class="text-left">EDP</th>
                            <th class="text-left">OC</th>
                            <th class="text-left">NC PF</th>
                            <th class="text-left">NC Trib.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($conciliacion as $centro)
                        <tr>
                            <td class="text-right">{{ $centro["centro"]->nombre  }}</td>
                            <td class="text-left">$ {{ number_format($centro["estadoPago"], 0, ".", "")  }}</td>
                            <td class="text-left">
                                <a href="#" data-toggle="modal" data-target="#modalOC{{$centro["centro"]->id}}">
                                    $ {{ number_format($centro["ordenCompra"], 0, ".", "")  }}
                                </a>
                                <!-- Modal -->
                                <div class="modal fade" id="modalOC{{$centro["centro"]->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Resumen OC</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <th>Fecha</th>
                                                        <th>Folio</th>
                                                        <th>Monto</th>
                                                        <th>PDF</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($centro["ordenes"] as $orden)
                                                        <tr>
                                                            <td>{{ $orden->fecha }}</td>
                                                            <td>{{ $orden->folio }}</td>
                                                            <td>{{ $orden->monto }}</td>
                                                            <td>
                                                                <a href="{{ asset($orden->documento)  }}" target="_BLANK">Ver PDF</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-left">$ {{ number_format($centro["notaCreditoProforma"], 0, ".", "")  }}</td>
                            <td class="text-left">
                                <a href="#" data-toggle="modal" data-target="#modalNC{{$centro["centro"]->id}}">
                                    $ {{ number_format($centro["notaCreditoTributaria"], 0, ".", "")  }}
                                </a>
                                <!-- Modal -->
                                <div class="modal fade" id="modalNC{{$centro["centro"]->id}}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title" id="myModalLabel">Resumen NC Trib.</h4>
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <th>Fecha</th>
                                                        <th>Folio</th>
                                                        <th>Monto</th>
                                                        <th>PDF</th>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($centro["notas"] as $nota)
                                                        <tr>
                                                            <td>{{ $nota->fecha }}</td>
                                                            <td>{{ $nota->folio }}</td>
                                                            <td>{{ $nota->monto }}</td>
                                                            <td>
                                                                <a href="{{ asset($nota->documento)  }}" target="_BLANK">Ver PDF</a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
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