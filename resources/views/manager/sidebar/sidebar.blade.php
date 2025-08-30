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
                    <a data-bs-toggle="collapse" href="#collapseTask"
                        class="{{ request()->is('manager/tasks*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('manager/tasks*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-md"></i>
                        <p>Task</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('manager/tasks*') ? 'show' : '' }}" id="collapseTask">
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
                {{-- Doctor --}}
                <li class="nav-item {{ request()->is('manager/doctors*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseDoctor"
                        class="{{ request()->is('manager/doctors*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('manager/doctors*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-md"></i>
                        <p>Doctors</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('manager/doctors*') ? 'show' : '' }}" id="collapseDoctor">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('manager/doctors/create') ? 'active' : '' }}">
                                <a href="{{ url('manager/doctors/create') }}">
                                    <span class="sub-item">Add Doctor</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/doctors') ? 'active' : '' }}">
                                <a href="{{ url('manager/doctors') }}">
                                    <span class="sub-item">All Doctors</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{-- Ta/Da --}}
                <li class="nav-item {{ request()->is('manager/tada-records') ? 'active' : '' }}">
                    <a href="{{ url('manager/tada-records') }}"
                        class="nav-link {{ request()->is('manager/tada-records') ? 'active' : '' }}">
                        <i class="fas fa-check-square"></i>
                        <p>TA/DA</p>
                    </a>
                </li>
                  {{-- Calendar --}}
                <li class="nav-item {{ request()->is('manager/calendar*') ? 'active' : '' }}">
                    <a href="{{ url('manager/calendar') }}"
                        class="nav-link {{ request()->is('manager/calendar') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Calendar</p>
                    </a>
                </li>
                {{-- events --}}
                <li class="nav-item {{ request()->is('manager/events*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseEvent"
                        class="{{ request()->is('manager/events*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('manager/events*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-md"></i>
                        <p>Events</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('manager/events*') ? 'show' : '' }}" id="collapseEvent">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('manager/events/create') ? 'active' : '' }}">
                                <a href="{{ route('manager.events.create') }}">
                                    <span class="sub-item">Add Event</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/events') ? 'active' : '' }}">
                                <a href="{{ route('manager.events.index') }}">
                                    <span class="sub-item">All Events</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{--visit plans --}}
                <li class="nav-item {{ request()->is('manager/visit-plans*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseVisitPlan"
                        class="{{ request()->is('manager/visit-plans*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('manager/visit-plans*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-md"></i>
                        <p>Visit Plans</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('manager/visit-plans*') ? 'show' : '' }}" id="collapseVisitPlan">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('manager/visit-plans/create') ? 'active' : '' }}">
                                <a href="{{ route('manager.visit-plans.create') }}">
                                    <span class="sub-item">Add Visit Plan</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/visit-plans') ? 'active' : '' }}">
                                <a href="{{ route('manager.visit-plans.index') }}">
                                    <span class="sub-item">All Visit Plans</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/visit-plans/interested-mrs') ? 'active' : '' }}">
                                <a href="{{ route('manager.visit.plans.interested.mrs') }}">
                                    <span class="sub-item">Interested MRS</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                {{--daily mr reports--}}
                <li class="nav-item {{ request()->is('manager/daily-mr-reports*') ? 'active' : '' }}">
                    <a href="{{ url('manager/daily-mr-reports') }}"
                        class="nav-link {{ request()->is('manager/daily-mr-reports') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Daily MR Reports</p>
                    </a>
                </li>
               {{-- attendance --}}
                <li class="nav-item {{ request()->is('manager/attendance*') ? 'active' : '' }}">
                    <a href="{{ url('manager/attendance') }}"
                        class="nav-link {{ request()->is('manager/attendance') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Attendance</p>
                    </a>
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
