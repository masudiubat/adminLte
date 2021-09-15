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
            <li class="nav-header">PRIMARY TOOLS</li>
            @hasanyrole('admin')
            <li class="nav-item">
                <a href="{{route('admin.project')}}" class="{{ request()->routeIs('admin.project*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fas fa-project-diagram"></i>
                    <p>All Project </p>
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('admin|client')
            <li class="nav-item">
                <a href="{{route('organization.project')}}" class="{{ request()->routeIs('organization.project*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fas fa-project-diagram"></i>
                    <p>All Project </p>
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('admin')
            <li class="nav-item">
                <a href="{{route('admin.project.create')}}" class="{{ request()->routeIs('admin.project.create*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fas fa-tasks"></i>
                    <p>New Project </p>
                </a>
            </li>
            @endhasanyrole
            @hasanyrole('admin|client')
            <li class="nav-item">
                <a href="{{route('organization.project.create')}}" class="{{ request()->routeIs('organization.project.create*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fas fa-tasks"></i>
                    <p>New Project </p>
                </a>
            </li>
            @endhasanyrole
            <li class="nav-header">AUXILIARY TOOLS</li>
            <li class="nav-item">
                <a href="{{route('researcher.skill')}}" class="{{ request()->routeIs('researcher.skill*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fas fa-laptop-house"></i>
                    <p>Researcher Skills </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{route('social.media')}}" class="{{ request()->routeIs('social.media*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fas fa-hashtag"></i>
                    <p>Social Media </p>
                </a>
            </li>
            <li class="nav-header">ADMINISTRATIVE TOOLS</li>
            <li class="nav-item">
                <a href="{{route('user.index')}}" class="{{ request()->routeIs('user*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fas fa-users"></i>
                    <p>Admin User </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('organization')}}" class="{{ request()->routeIs('organization*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fas fa-building"></i>
                    <p>Organization </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('assign.organization.member')}}" class="{{ request()->routeIs('assign.organization*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fas fa-puzzle-piece"></i>
                    <p>Organization Member</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('activity.log')}}" class="{{ request()->routeIs('activity.log*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fas fa-keyboard"></i>
                    <p>Activity Log </p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{route('role')}}" class="{{ request()->routeIs('role*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fa fa-key"></i>
                    <p>Roles & Permissions </p>
                </a>
            </li>


            <li class="{{ request()->routeIs('personal.profile*') ? 'nav-item menu-open' : 'nav-item' }}">
                <a href="#" class="{{ request()->routeIs('personal.profile*') ? 'nav-link active' : 'nav-link' }}">
                    <i class="nav-icon fas fa-user-cog"></i>
                    <p>
                        My Profile
                        <i class="fas fa-angle-left right"></i>
                    </p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="{{route('personal.profile.detail', Auth::user()->id) }}" class="{{ request()->routeIs('personal.profile.detail') ? 'nav-link active' : 'nav-link' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Profile</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/mailbox/compose.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Compose</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="pages/mailbox/read-mail.html" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Read</p>
                        </a>
                    </li>
                </ul>
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