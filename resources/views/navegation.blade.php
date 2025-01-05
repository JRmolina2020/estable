<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

    <li class="nav-item">
      <a href="{{ url('home') }}" class="nav-link">
        <i class="fi fi-microsoft"></i>
        <p>Indicadores</p>
      </a>
    </li>
    @hasrole('administrador')
    <li class="nav-item">
      <a href="{{ url('empresa') }}" class="nav-link">
        <i class="fi fi-shopping-basket-add"></i>
        <p>Empresa</p>
      </a>
    </li>
    @endrole
    <li class="nav-item">
      <a href="{{ url('gastos') }}" class="nav-link">
        <i class="fi fi-credit-card"></i>
        <p>Gastos</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ url('perfil') }}" class="nav-link">
        <i class="fi fi-user-secret"></i>
        <p>Perfil</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ url('categorias') }}" class="nav-link">
        <i class="fi fi-sunglasses-alt"></i>
        <p>Categorias</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ url('productos') }}" class="nav-link">
        <i class="fi fi-sunglasses-alt"></i>
        <p>Productos</p>
      </a>
    </li>
    @hasrole('administrador')
    <li class="nav-item">
      <a href="{{ url('proveedores') }}" class="nav-link">
        <i class="fi fi-wink"></i>
        <p>Proveedores</p>
      </a>
    </li>
    @endrole
    <li class="nav-item">
      <a href="{{ url('clientes') }}" class="nav-link">
        <i class="fi fi-male"></i>
        <p>Clientes</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ url('facturas') }}" class="nav-link">
        <i class="fi fi-shopify"></i>
        <p>Facturas</p>
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ url('fstate') }}" class="nav-link">
        <i class="fi fi-shopify"></i>
        <p>Facturas pendiente</p>
      </a>
    </li>
    @hasrole('administrador')
    <li class="nav-item">
      <a href="{{ url('fupdate') }}" class="nav-link">
        <i class="fi fi-shopify"></i>
        <p>Moficar factura</p>
      </a>
    </li>
    @endrole
    @hasrole('administrador')
    <li class="nav-item">
      <a href="{{ url('cuentas') }}" class="nav-link">
        <i class="fi fi-credit-card"></i>
        <p>Cuentas</p>
      </a>
    </li>
    @endrole
    <li class="nav-item">
      <a href="{{ url('entradas') }}" class="nav-link">
        <i class="fi fi-curve"></i>
        <p>Entradas</p>
      </a>
    </li>

    <li class="nav-item">
      <a href="{{ url('inventario') }}" class="nav-link">
        <i class="fi fi-curve"></i>
        <p>Inventario</p>
      </a>
    </li>


    @hasrole('administrador')
    <li class="nav-item">
      <a href="#" class="nav-link">
        <i class="fi fi-world-o"></i>
        <p>
          Seguridad
          <i class="right fi fi-more-v-a"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('usuarios') }}" class="nav-link">
            <i class="fi fi-male"></i>
            <p>
              Usuarios
            </p>
          </a>
        </li>
      </ul>

      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('roles') }}" class="nav-link">
            <i class="fi fi-persons"></i>
            <p>
              Roles
            </p>
          </a>
        </li>
      </ul>
      <ul class="nav nav-treeview">
        <li class="nav-item">
          <a href="{{ url('permisos') }}" class="nav-link">
            <i class="fi fi-preview"></i>
            <p>
              Permisos
            </p>
          </a>
        </li>
      </ul>
    </li>
    @endrole
    <li class="nav-item has-treeview">
      <a class="nav-link" onclick="event.preventDefault(); 
          document.getElementById('logout-form').submit();" href="{{ route('logout') }}">
        <i class="fi fi-frowning"></i>
        <p>Salir</p>
      </a>
    </li>
    <form id="logout-form" action="{{route('logout')}}" method="POST" style="display:none;">
      {{ csrf_field() }}
    </form>
  </ul>
</nav>