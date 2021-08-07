<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
      <li class="nav-item">
        <router-link to="/items" class="nav-link">
          <i class="nav-icon fas fa-list blue"></i>
          <p>
            List Barang
          </p>
        </router-link>
      </li>

      <li class="nav-item">
        <router-link to="/transactions" class="nav-link">
          <i class="nav-icon far fa-credit-card blue"></i>
          <p>
            Transaksi
          </p>
        </router-link>
      </li>

      <li class="nav-item">
        <router-link to="/stockItems" class="nav-link">
          <i class="nav-icon fas fa-box blue"></i>
          <p>
            Stok Gudang
          </p>
        </router-link>
      </li>

      <li class="nav-item">
        <router-link to="/displayItems" class="nav-link">
          <i class="nav-icon fas fa-store-alt blue"></i>
          <p>
            Stok Display
          </p>
        </router-link>
      </li>

      <li class="nav-item">
        <router-link to="/orders" class="nav-link">
          <i class="nav-icon fas fa-vote-yea blue"></i>
          <p>
            Pengadaan
          </p>
        </router-link>
      </li>


      @can('isAdmin')
        <li class="nav-item">
          <router-link to="/users" class="nav-link">
            <i class="fa fa-users nav-icon blue"></i>
            <p>Users</p>
          </router-link>
        </li>
      @endcan

      <li class="nav-item">
        <a href="#" class="nav-link" onclick="event.preventDefault();
          document.getElementById('logout-form').submit();">
          <i class="nav-icon fas fa-power-off red"></i>
          <p>
            {{ __('Logout') }}
          </p>
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
          @csrf
        </form>
      </li>
    </ul>
  </nav>