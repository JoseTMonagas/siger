@extends('layouts.app')

@section('title', 'Armar Cajas')

@section('home-route', route('compass.home'))

@section('nav-menu')
@include('compass.menu')
@endsection

@section('main')
<div class="container">
  <div class="card">
    <h3 class="card-header font-bold text-xl">Armar Cajas</h3>
    <div class="card-body">
      <form action="{{ route('compass.pedidos.armarCaja', $requerimiento) }}" method="POST" class="container mt-2">
        @csrf

        <div class="row">
          <div class="col">
            <div class="card">
              <div class="card-body">
                <div class="row">
                  <div class="col text-right">Empresa:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->empresa->razon_social }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Giro:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->empresa->giro }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">RUT Empresa:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->empresa->rut }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Centro:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->nombre }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Comuna:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->comuna }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Ciudad:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->ciudad }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Direccion:</div>
                  <div class="col font-bold">{{ $requerimiento->centro->direccion }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Nombre del Pedido:</div>
                  <div class="col font-bold">{{ $requerimiento->nombre }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Fecha de Creacion:</div>
                  <div class="col font-bold">{{ $requerimiento->created_at }}</div>
                </div>
                <div class="row">
                  <div class="col text-right">Bodeguero Responsable:</div>
                  <div class="col font-bold">
                    <select class="form-control form-control-sm w-50" name="bodeguero">
                      @foreach ($bodegueros as $bodeguero)
                      <option value="{{ $bodeguero->id }}">{{ $bodeguero->nombre }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="row mt-4">
                  <div class="col-md-3">
                    <agregar-producto-caja :productos='@json($productos)' action="{{ route('requerimiento.productos.agregar', $requerimiento) }}"></agregar-producto-caja>
                  </div>
                  <div class="col-md-4 offset-md-1">
                    <button type="submit" name="save" value="1" class="btn btn-info">Guardar</button>
                    <button type="submit" name="save" value="0" class="btn btn-success">Armar</button>
                    <button type="submit" name="delete" value="1" class="btn btn-danger">Eliminar Seleccionados</button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <table id="datatable-requerimiento" class="table">
          <thead>
            <tr>
              <th scope="col">Seleccionar</th>
              <th scope="col">SKU</th>
              <th scope="col">Detalle</th>
              <th scope="col">Cantidad Solicitada</th>
              <th scope="col">Cantidad a despachar</th>
              <th scope="col">Fecha de Vencimiento</th>
              <th scope="col">Observaciones</th>
              <th scope="col">Acciones</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($requerimiento->productos as $producto)
            <tr>
              <td><input type="checkbox" name="remove[]" value="{{$producto->id}}" /></td>
              <input type="hidden" value="{{$producto}}" name="productos[]" />
              <td>{{$producto->sku}}</td>
              <td>{{$producto->detalle}}</td>
              <td>{{$producto->pivot->cantidad}}</td>
              <td><input class="form-control form-control-sm" name="real[]" value="{{$producto->pivot->real ?? $producto->pivot->cantidad}}" type="text"></td>
              <td><input type="date" class="form-control form-control-sm" name="vencimiento[]" min="{{ \Carbon\Carbon::now()->addDays(10) }}"></td>
              <td><input class="form-control form-control-sm" type="text" name="observaciones[]" value="{{$producto->pivot->observacion}}"></td>
              <td>
                <div class="btn-group">
                  <a class="btn btn-primary" href="{{ route('cajas.cambiar', [$requerimiento, $producto]) }}">
                    <i class="fas fa-undo"></i>
                  </a>
                  <a class="btn btn-danger" href="{{ route("cajas.borrar", [$requerimiento, $producto]) }}">
                    &times;
                  </a>
                </div>

              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </form>
    </div>
  </div>
  @endsection