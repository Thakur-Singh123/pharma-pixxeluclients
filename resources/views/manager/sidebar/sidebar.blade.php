<!--sidebar-->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!--header section-->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('admin/dashboard') }}" class="logo">
                <img src="{{ asset('public/admin/images/pd_management_logo.svg') }}" alt="navbar brand"
                    class="navbar-brand" height="20" />
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <!--dashboard section-->
                <li class="nav-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                {{-- add mr --}}
                <li class="nav-item {{ request()->is('manager/mrs*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseMR"
                        class="{{ request()->is('manager/mrs*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('manager/mrs*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-md"></i>
                        <p>MR Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('manager/mrs*') ? 'show' : '' }}" id="collapseMR">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('manager/mrs/create') ? 'active' : '' }}">
                                  <a href="{{ route('manager.mrs.create') }}">
                                    <span class="sub-item">Add MR</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/mrs') ? 'active' : '' }}">
                                  <a href="{{ route('manager.mrs.index') }}">
                                    <span class="sub-item">All MR</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- add task --}}
                <li class="nav-item {{ request()->is('manager/tasks*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseMR"
                        class="{{ request()->is('manager/tasks*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('manager/tasks*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-md"></i>
                        <p>Task</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('manager/tasks*') ? 'show' : '' }}" id="collapseMR">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('manager/tasks/create') ? 'active' : '' }}">
                                  <a href="{{ route('manager.tasks.create') }}">
                                    <span class="sub-item">Add Task</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/tasks') ? 'active' : '' }}">
                                  <a href="{{ route('manager.tasks.index') }}">
                                    <span class="sub-item">All Task</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!--logout section-->
                <li class="nav-item">
                    <a href="{{ route('logout') }}" class="nav-link"
                        onclick="event.preventDefault();
            document.getElementById('logout-form').submit();">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>
                            Logout
                        </p>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
