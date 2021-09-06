<div class="sidebar">
    <!-- Sidebar user panel (optional) -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
            @if(Auth::user()->image)
            <img src="{{ asset('images/users/'. Auth::user()->image)}}" class="img-circle elevation-2" alt="User Image">
            @else
            <img src="{{ asset('images/logos/favicon.png')}}" class="img-circle elevation-2" alt="User Image">
            @endif


        </div>
        <div class="info">
            <a href="#" class="d-block">{{Auth::user()->name}}</a>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
    with font-awesome or any other icon font library -->
            <li class="nav-header">ADMINISTRATIVE TOOLS</li>
            <li class="nav-item">
                <a href="{{route('user.index')}}" class="{{ request()->routeIs('user*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Admin User </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('role')}}" class="{{ request()->routeIs('role*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fa fa-key"></i>
                    <p>Roles & Permissions </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="nav-link">
                    <i class="nav-icon fa fa-power-off"></i>
                    <p>Log Out</p>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </li>
        </ul>
    </nav>
    <!-- /.sidebar-menu -->
</div>