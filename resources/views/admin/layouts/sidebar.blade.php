<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="brand-link">
        <img src="{{ asset('dist/img/AdminLTELogo.png') }}" alt="{{ config('app.name') }}"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{ auth()->user()?->profile?->file_link ?? asset('dist/img/default-150x150.png') }}"
                    class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="{{ route('admin.profile') }}" class="d-block">{{ auth()->user()->full_name }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                data-accordion="false">
                <li class="nav-item">
                    <a href="{{ route('admin.home') }}"
                        class="nav-link {{ request()->route()->getName() == 'admin.home' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                @can('users')
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}"
                            class="nav-link {{ request()->is('admin/users*') ? 'active' : '' }}"">
                            <i class="nav-icon fa-solid fa-users-gear"></i>
                            <p>Users</p>
                        </a>
                    </li>
                @endcan
                @can('roles')
                    <li class="nav-item">
                        <a href="{{ route('admin.roles.index') }}"
                            class="nav-link {{ request()->is('admin/roles*') ? 'active' : '' }}"">
                            <i class="nav-icon fa-solid fa-shield-halved"></i>
                            <p>Roles</p>
                        </a>
                    </li>
                @endcan
                @can('suppliers')
                    <li class="nav-item">
                        <a href="{{ route('admin.suppliers.index') }}"
                            class="nav-link {{ request()->route()->getName() == 'admin.suppliers.index' ? 'active' : '' }}">
                            <i class="nav-icon fa-solid fa-truck-fast"></i>
                            <p>Suppliers</p>
                        </a>
                    </li>
                @endcan
                @can('customers')
                    <li class="nav-item">
                        <a href="{{ route('admin.customers.index') }}"
                            class="nav-link {{ request()->route()->getName() == 'admin.customers.index' ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>Customers</p>
                        </a>
                    </li>
                @endcan
                <li class="nav-item">
                    <a href="{{ route('admin.profile') }}"
                        class="nav-link {{ request()->route()->getName() == 'admin.profile' ? 'active' : '' }}">
                        <i class="nav-icon fas fa-user"></i>
                        <p>Profile</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
