@extends('layouts.app')

@section('title', 'Editar Estado de la Empresa | Mline SIGER')

@if ((Auth::user()->userable instanceof \App\Empresa))
  @section('home-route', route('cliente.home'))
@elseif (Auth::user()->userable instanceof \App\CompassRole)
    @section('home-route', route('compass.home'))
@endif

@section('nav-menu')
  @if ((Auth::user()->userable instanceof \App\Empresa))
    @include('cliente.menu')
  @elseif (Auth::user()->userable instanceof \App\CompassRole)
    @include('compass.menu')
  @endif
@endsection

@section('main')
  <div class="container">
    <div class="card">
      <h3 class="card-header font-bold text-xl">Editar Estado</h3>
      <div class="card-body">
        <form action="{{route('centros.habilitar', $centro)}}" method="POST" accept-charset="utf-8">
          @csrf

          <div class="form-group row">
                    <label class="col-sm-2">Nombre:</label>
            <span class="col-sm-6">
              <input class="form-control-plaintext" value="{{ $centro->nombre }}" type="text" disabled>
            </span>
          </div>

          <div class="form-group row">
            <label class="col-sm-2" for="rut">Estado:</label>
            <span class="col-sm-6">
              <select name="estado" class="form-control">
                <option @if (!isset($centro->habilitado)) selected @endif value="null">Segun Horario asignado</option>
                  <option @if (isset($centro->habilitado) && (!$centro->habilitado)) selected @endif  value="false">Inhabilitado</option>
                    <option @if (isset($centro->habilitado) && ($centro->habilitado))  selected @endif value="true">Habilitado</option>
              </select>
            </span>
          </div>

          <div class="form-group row">
            <div class="col-sm-10">
              <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
@endsection

@section('js')
@endsection
