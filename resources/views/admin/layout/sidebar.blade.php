<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <img src="{{ asset('template/admin/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">{{ config('variable.webname') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        @php
          $id = session('id');
          $dtadmin = DB::table('admins')->where('id', $id)->first();
        @endphp
        <div class="image">
          <img src="{{ asset('storage/admins/thumbnail/'.$dtadmin->thumb) }}" width="160" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ $dtadmin->name }}</a>
        </div>
      </div>

      <!-- SidebarSearch Form -->
      {{-- <div class="form-inline">
        <div class="input-group" data-widget="sidebar-search">
          <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
          <div class="input-group-append">
            <button class="btn btn-sidebar">
              <i class="fas fa-search fa-fw"></i>
            </button>
          </div>
        </div>
      </div> --}}

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-child-indent" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <?php
            $menu = DB::select("SELECT * FROM menus ORDER BY urut");
          ?>
          @foreach ($menu as $m)
              @if ($m->type == 'title')
                  <li class="nav-header">{{ $m->menu }}</li>
              @elseif($m->type == 'menu')
                  <li class="nav-item">
                    <a href="{{ route($m->route) }}" class="nav-link {{ Request::segment(2) == $m->uri ? 'active':'' }}">
                      <i class="{{ $m->icon }} nav-icon"></i>
                      <p>{{ $m->menu }}</p>
                    </a>
                  </li>
              @endif
          @endforeach
          <li class="nav-header"></li>
          <li class="nav-item">
            <a href="#" class="nav-link bg-danger" data-toggle="modal" data-target="#modal-logout" data-backdrop="static" data-keyboard="false">
              <i class="fas fa-lock nav-icon"></i>
              <p>KELUAR</p>
            </a>
          </li>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>