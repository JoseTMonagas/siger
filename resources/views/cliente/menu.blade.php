<li class="nav-item">
    <a class="nav-link" href="{{ route('cliente.home')}}">
        <i class="fas fa-home mr-2"></i>
        Inicio
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ asset('rrhh/minuta.pdf') }}">
        <i class="fas fa-clock mr-2"></i>
        Minuta del Mes
    </a>
</li>
<li class="nav-item">
    <a class="nav-link" href="{{ asset('rrhh/saludable.pdf') }}">
        <i class="fas fa-leaf mr-2"></i>
        Una Vida Saludable
    </a>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownRequerimientos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-tasks fa-fw mr-2"></i>
        Ordenes de Pedido
    </a>
    <div class="dropdown-menu">
        @switch(get_class(Auth::user()->userable))
        @case('App\Centro')
        <a class="dropdown-item" href="{{route(
                            'pedidos.centro',
                            Auth::user()->userable->id
                            )}}">
            Lista
        </a>
        <a class="dropdown-item" href="{{ route('requerimientos.create')}}">
            Nuevo
        </a>
        <a class="dropdown-item" href="{{  route('libreria.index') }}">
            Libreria
        </a>
        @break
        @case('App\Empresa')
        <a class="dropdown-item" href="{{route('pedidos.indexCentro')}}">
            Lista
        </a>
        @if(Auth::user()->logistica)
        <a class="dropdown-item" href="{{ route('pedidos.listaLogistica')}}">
            Lista Logistica
        </a>
        @else
        <a class="dropdown-item" href="{{ route('pedidos.validar')}}">
            Validar
        </a>
        @endif
        @break
        @case('App\Holding')
        <a class="dropdown-item" href="{{route('pedidos.indexEmpresa')}}">
            Lista
        </a>
        @break
        @endswitch
    </div>
</li>
@if(!Auth::user()->logistica)
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-wallet mr-2"></i>
        Presupuesto
    </a>
    <div class="dropdown-menu">
        @if(Auth::user()->userable instanceof App\Holding)
        <a class="dropdown-item" href="{{route('presupuesto.create')}}">Cargar</a>
        <a class="dropdown-item" href="{{route('presupuesto.indexHolding')}}">Cuenta Corriente</a>
        @elseif(Auth::user()->userable instanceof App\Empresa)
        <a class="dropdown-item" href="{{route('presupuesto.create')}}">Cargar</a>
        <a class="dropdown-item" href="{{route('presupuesto.indexEmpresa')}}">Cuenta Corriente</a>
        <a class="dropdown-item" href="{{route('presupuesto.cmi')}}">Consolidado</a>
        @else
        <a class="dropdown-item" href="{{route('presupuesto.indexCentro')}}">Cuenta Corriente</a>
        @endif
    </div>
</li>
@if(Auth::user()->userable instanceof App\Holding)
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-industry mr-2"></i>
        Empresas
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('empresas.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('empresas.create')}}">Nuevo</a>
    </div>
</li>
@endif
@if(Auth::user()->userable instanceof App\Empresa)
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-warehouse mr-2"></i>
        Centros
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('centros.index')}}">Lista</a>
        <a class="dropdown-item" href="{{route('centros.create')}}">Nuevo</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-users mr-2"></i>
        Usuarios de Centro
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('user.indexEmpresa')}}">Lista</a>
        <a class="dropdown-item" href="{{route('user.create')}}">Nuevo</a>
    </div>
</li>
@endif
@else
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownProductos" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-chart-bar mr-2"></i>
        Reportes
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('reportes.enviados')}}">
            Productos despachados
        </a>
        <a class="dropdown-item" href="{{route('estado_pago_general')}}">Estados de Pago</a>
        <a class="dropdown-item" href="{{route('estado_pago_resumen')}}">Resumen estados de pago</a>
        <a class="dropdown-item" href="{{route('estado_pago_cierre')}}">Cierre estados de pago</a>
    </div>
</li>
<li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" id="dropdownCierre" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-clipboard-check mr-2"></i>
        Control OC/NC/FE
    </a>
    <div class="dropdown-menu">
        <a class="dropdown-item" href="{{route('orden_compra_index')}}">Orden de Compra</a>
        <a class="dropdown-item" href="{{route('nota_credito_index')}}">Nota de Credito</a>
        <a class="dropdown-item" href="{{route('factura_electronica_index')}}">Factura Electronica</a>
        <a class="dropdown-item" href="{{route('conciliacion_index')}}">Conciliación</a>
    </div>
</li>
@endif