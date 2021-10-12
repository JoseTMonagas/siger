@extends('layouts.app')

@section('title', 'Asignar Usuarios | Mline SIGER')

@section('home-route', route('compass.home'))

@section('nav-menu')
    @include('compass.menu')
@endsection

@section('main')
    <div class="container">
        <div class="card">
            <h3 class="card-header font-bold text-xl">Asignar Usuarios</h3>
            <div class="card-body">
                <form action="{{ route('usuario.asignacion') }}" method="POST" accept-charset="utf-8">
                    @csrf

                    <div class="form-group form-row">
                        <label class="col-md-4 col-form-label text-md-right" for="name">Nombre:</label>
                        <div class="col-md-6">
                            <input type="text" readonly class="form-control-plaintext" value="{{$user->name}}">
                        </div>
                    </div>

                    <div class="form-group form-row">
                        <label class="col-md-4 col-form-label text-md-right" for="name">E-mail:</label>
                        <div class="col-md-6">
                            <input type="text" readonly class="form-control-plaintext" value="{{$user->email}}">
                        </div>
                    </div>

                    <div class="form-group form-row">
                        <label class="col-md-4 col-form-label text-md-right" for="asignacion">Asignacion:</label>
                        <div class="col-md-6">
                            <select class="form-control" name="asignacion">
                                @foreach ($asignacion as $obj)
                                    <option value="{{$obj->id}}">{{$obj->nombre ?? $obj->razon_social ?? $obj->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <input type="hidden" value="{{$user->id}}" name="userId"/>
                    <input type="hidden" value="{{get_class($asignacion[0])}}" name="asignacionType"/>

                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Asignar') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
