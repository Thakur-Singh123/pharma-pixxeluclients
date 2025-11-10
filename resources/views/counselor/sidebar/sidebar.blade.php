<!--sidebar-->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!--header section-->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('counselor/dashboard') }}" class="logo">
                <img src="{{ asset('public/admin/images/Ad People Logo.svg') }}" alt="navbar brand" class="navbar-brand"
                    height="20" />
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
                <li class="nav-item {{ Request::is('counselor/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('counselor/dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <!--add patient-->
                <!-- Patient Management -->
                <li class="nav-item {{ request()->is('counselor/bookings*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapsePatient"
                        class="{{ request()->is('counselor/bookings*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('counselor/bookings*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-plus"></i>
                        <p>Patient Management</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ request()->is('counselor/bookings*') ? 'show' : '' }}"
                        id="collapsePatient">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('counselor/bookings/create') ? 'active' : '' }}">
                                <a href="{{ route('counselor.bookings.create') }}">
                                    <span class="sub-item">Add Patient</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->is('counselor/bookings') || request()->is('counselor/bookings/*/edit') ? 'active' : '' }}">
                                <a href="{{ route('counselor.bookings.index') }}">
                                    <span class="sub-item">All Patients</span>
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
