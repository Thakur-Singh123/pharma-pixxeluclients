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
                {{-- mr attendance --}}
                <li class="nav-item {{ request()->is('mr/attendance*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseMR"
                        class="{{ request()->is('mr/attendance*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/attendance*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-md"></i>
                        <p>Attendance</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/attendance*') ? 'show' : '' }}" id="collapseMR">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/attendance') ? 'active' : '' }}">
                                  <a href="{{ route('mr.attendance.index') }}">
                                    <span class="sub-item">Check In</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/attendance/monthly') ? 'active' : '' }}">
                                  <a href="{{ route('mr.attendance.monthly') }}">
                                    <span class="sub-item">Attendance</span>
                                </a>
                            </li>
                            
                        </ul>
                    </div>
                </li>

                 {{-- Daily Visit --}}
                 <li class="nav-item {{ request()->is('mr/visits*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseVisit"
                        class="{{ request()->is('mr/visits*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/visits*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-md"></i>
                        <p>Daily Visits</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/visits*') ? 'show' : '' }}" id="collapseVisit">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/create') ? 'active' : '' }}">
                                  <a href="{{ url('mr/visit-create') }}">
                                    <span class="sub-item">Add Visit</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/visits') ? 'active' : '' }}">
                                  <a href="{{ url('mr/visits') }}">
                                    <span class="sub-item">All Visits</span>
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
