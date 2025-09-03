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
                        <i class="fas fa-user-check"></i>
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
                        <i class="fas fa-walking"></i>
                        <p>Daily Visits</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/visits*') ? 'show' : '' }}" id="collapseVisit">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/visits/create') ? 'active' : '' }}">
                                <a href="{{ url('mr/visits/create') }}">
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
                {{-- Daily tasks --}}
                <li class="nav-item {{ request()->is('mr/tasks') ? 'active' : '' }}">
                    <a href="{{ url('mr/tasks') }}" class="nav-link {{ request()->is('mr/tasks') ? 'active' : '' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <p>Tasks</p>
                    </a>
                </li>
                {{-- Doctor list --}}
                <li class="nav-item {{ request()->is('mr/doctors*') ? 'active' : '' }}">
                    <a href="{{ url('mr/doctors') }}"
                        class="nav-link {{ request()->is('mr/doctors') ? 'active' : '' }}">
                        <i class="fas fa-user-md"></i>
                        <p>Doctors</p>
                    </a>
                </li>
                {{-- Patient list --}}
                <li class="nav-item {{ request()->is('mr/patients*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapsePateint"
                        class="{{ request()->is('mr/patients*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/patients*') ? 'true' : 'false' }}">
                        <i class="fas fa-stethoscope"></i>
                        <p>Patient</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/patients*') ? 'show' : '' }}" id="collapsePateint">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/patients/create') ? 'active' : '' }}">
                                <a href="{{ route('mr.patients.create') }}">
                                    <span class="sub-item">Add Patient</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/patients') ? 'active' : '' }}">
                                <a href="{{ url('mr/patients') }}">
                                    <span class="sub-item">All Patient</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- TADA list --}}
                <li class="nav-item {{ request()->is('mr/tada*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseTADA"
                        class="{{ request()->is('mr/tada*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/tada*') ? 'true' : 'false' }}">
                        <i class="fas fa-suitcase-rolling"></i>
                        <p>TA/DA</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/tada*') ? 'show' : '' }}" id="collapseTADA">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/tada/create') ? 'active' : '' }}">
                                <a href="{{ route('mr.tada.create') }}">
                                    <span class="sub-item">Add TA/DA</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/tada') ? 'active' : '' }}">
                                <a href="{{ url('mr/tada') }}">
                                    <span class="sub-item">All TA/DA</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Calendar --}}
                <li class="nav-item {{ request()->is('mr/calendar*') ? 'active' : '' }}">
                    <a href="{{ url('mr/calendar') }}"
                        class="nav-link {{ request()->is('mr/calendar') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Calendar</p>
                    </a>
                </li>
                {{-- event --}}
                <li class="nav-item {{ request()->is('mr/events*') ? 'active' : '' }}">
                    <a href="{{ url('mr/events') }}"
                        class="nav-link {{ request()->is('mr/events') ? 'active' : '' }}">
                        <i class="fas fa-calendar-check"></i>
                        <p>Events</p>
                    </a>
                </li>
                {{-- visit plans --}}
                 <li class="nav-item {{ request()->is('mr/visit-plans*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseVisitPlan"
                        class="{{ request()->is('mr/visit-plans*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/visit-plans*') ? 'true' : 'false' }}">
                        <i class="fas fa-tasks"></i>
                        <p>Visit Plans</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/visit-plans*') ? 'show' : '' }}" id="collapseVisitPlan">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/visit-plans') ? 'active' : '' }}">
                                <a href="{{ route('mr.visit-plans.index') }}">
                                    <span class="sub-item">All Visit Plans</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/visit-plans/my-interested-plans') ? 'active' : '' }}">
                                <a href="{{ route('mr.visit-plans.my-interested') }}">
                                    <span class="sub-item">Interested Plans</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/visit-plans/my-assigned-plans') ? 'active' : '' }}">
                                <a href="{{ route('mr.visit-plans.my-assigned') }}">
                                    <span class="sub-item">Assigned Plans</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- daily reports --}}
                <li class="nav-item {{ request()->is('mr/daily-reports*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseDailyReport"
                        class="{{ request()->is('mr/daily-reports*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/daily-reports*') ? 'true' : 'false' }}">
                        <i class="fas fa-chart-bar"></i>
                        <p>Daily Reports</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/daily-reports*') ? 'show' : '' }}" id="collapseDailyReport">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/daily-reports/create') ? 'active' : '' }}">
                                <a href="{{ route('mr.daily-reports.create') }}">
                                    <span class="sub-item">Add Report</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/daily-reports') ? 'active' : '' }}">
                                <a href="{{ route('mr.daily-reports.index') }}">
                                    <span class="sub-item">All Reports</span>
                                </a>
                            </li>
                        </ul>
                    </div>
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
