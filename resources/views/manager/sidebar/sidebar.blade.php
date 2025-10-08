<!--sidebar-->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!--header section-->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('admin/dashboard') }}" class="logo">
                <img src="{{ asset('public/admin/images/pd_management_logo.svg') }}" alt="navbar brand" class="navbar-brand" height="20" />
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
                 <!--attendance section-->
                <li class="nav-item {{ request()->is('manager/attendance*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseattendance"
                        class="{{ request()->is('manager/attendance*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('manager/attendance*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-check"></i>
                        <p>Attendances</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('manager/attendance*') ? 'show' : '' }}" id="collapseattendance">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('manager/attendance') ? 'active' : '' }}">
                                <a href="{{ route('manager.attendance.index') }}">
                                    <span class="sub-item">Monthly Attendance</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/attendance/daily') ? 'active' : '' }}">
                                <a href="{{ route('manager.daily.attendance') }}">
                                    <span class="sub-item">Today Attendance</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--mr section-->
                <li class="nav-item {{ request()->is('manager/mrs*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseMR"
                        class="{{ request()->is('manager/mrs*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('manager/mrs*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-tie"></i>
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
                            <li class="{{ request()->is('manager/mrs') || request()->is('manager/mrs/*/edit') ? 'active' : '' }}">
                                <a href="{{ route('manager.mrs.index') }}">
                                    <span class="sub-item">All MR</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
               <!--Users section-->
                <li class="nav-item {{ request()->is('manager/pending-users') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseUser"
                        class="{{ request()->is('manager/pending-users') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('manager/pending-users') ? 'true' : 'false' }}">
                        <i class="fas fa-users"></i>
                        <p>MR User Requests</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('manager/active-users') || request()->is('manager/pending-users') || request()->is('manager/suspend-users') ? 'show' : '' }}" id="collapseUser">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('manager/pending-users') || request()->is('manager/pending-users/edit/*') ? 'active' : '' }}">
                                <a href="{{ url('manager/pending-users') }}">
                                    <span class="sub-item">Pending</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/suspend-users') || request()->is('manager/suspend-users/edit/*') ? 'active' : '' }}">
                                <a href="{{ url('manager/suspend-users') }}">
                                    <span class="sub-item">Suspend</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/active-users') || request()->is('manager/active-users/edit/*') ? 'active' : '' }}">
                                <a href="{{ url('manager/active-users') }}">
                                    <span class="sub-item">Active</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--clients section-->
                <li class="nav-item {{ request()->is('manager/clients') || request()->is('manager/clients/*/edit') ? 'active' : '' }}">
                    <a href="{{ url('manager/clients') }}"
                        class="nav-link {{ request()->is('manager/clients') || request()->is('manager/clients/*/edit') ? 'active' : '' }}">
                        <i class="fas fa-address-card"></i>
                        <p>All Clients</p>
                    </a>
                </li>
                <!--daily visit section-->
                <li class="nav-item {{ request()->is('manager/visits') || request()->is('manager/visits/*/edit') ? 'active' : '' }}">
                    <a href="{{ url('manager/visits') }}"
                        class="nav-link {{ request()->is('manager/visits') || request()->is('manager/visits/*/edit') ? 'active' : '' }}">
                        <i class="fas fa-walking"></i>
                        <p>Daily Visits</p>
                    </a>
                </li>
                <!--task section-->
                <li class="nav-item {{ request()->is('manager/tasks*') || request()->is('manager/tasks-waiting-for-approval') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseTask"
                        class="{{ request()->is('manager/tasks*') || request()->is('manager/tasks-waiting-for-approval') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('manager/tasks*') || request()->is('manager/tasks-waiting-for-approval') ? 'true' : 'false' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <p>Tasks</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('manager/tasks*') || request()->is('manager/tasks-calendar-for-approval') || request()->is('manager/tasks-waiting-for-approval') ? 'show' : '' }}" id="collapseTask">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('manager/tasks/create') ? 'active' : '' }}">
                                <a href="{{ route('manager.tasks.create') }}">
                                    <span class="sub-item">Add Task</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/tasks') || request()->is('manager/tasks/*/edit') ? 'active' : '' }}">
                                <a href="{{ route('manager.tasks.index') }}">
                                    <span class="sub-item">All Tasks</span>
                                </a>
                            </li>
                            <!-- <li class="{{ request()->is('manager/tasks-waiting-for-approval') ? 'active' : '' }}">
                                <a href="{{ route('manager.tasks.waiting.for.approval') }}">
                                    <span class="sub-item">Waiting For Approval</span>
                                </a>
                            </li> -->
                            <li class="{{ request()->is('manager/tasks-calendar-for-approval') ? 'active' : '' }}">
                                <a href="{{ url('manager/tasks-calendar-for-approval') }}">
                                    <span class="sub-item">Calendar For Approval</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--doctor section-->
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
                            <li class="{{ request()->is('manager/doctors') || request()->is('manager/doctors/edit/*') ? 'active' : '' }}">
                                <a href="{{ url('manager/doctors') }}">
                                    <span class="sub-item">All Doctors</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/doctors-waiting-for-approval') ? 'active' : '' }}">
                                <a href="{{ url('manager/doctors-waiting-for-approval') }}">
                                    <span class="sub-item">Waiting For Approval</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--tada section-->
                <li class="nav-item {{ request()->is('manager/tada-records') || request()->is('manager/edit-tada/*') ? 'active' : '' }}">
                    <a href="{{ url('manager/tada-records') }}"
                        class="nav-link {{ request()->is('manager/tada-records') || request()->is('manager/edit-tada/*') ? 'active' : '' }}">
                        <i class="fas fa-suitcase-rolling"></i>
                        <p>TA/DA</p>
                    </a>
                </li>
                @php
                    $isEventActive = request()->is('manager/events*') || request()->is('manager/events-waiting-for-approval') || request()->is('manager/events-active-participations*');
                @endphp
                <li class="nav-item {{ $isEventActive ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseEvent"
                        class="{{ $isEventActive ? '' : 'collapsed' }}"
                        aria-expanded="{{ $isEventActive ? 'true' : 'false' }}">
                        <i class="fas fa-calendar-check"></i>
                        <p>Events</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ $isEventActive ? 'show' : '' }}" id="collapseEvent">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('manager/events/create') ? 'active' : '' }}">
                                <a href="{{ route('manager.events.create') }}">
                                    <span class="sub-item">Add Event</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/events') || request()->is('manager/events/*/edit') ? 'active' : '' }}">
                                <a href="{{ route('manager.events.index') }}">
                                    <span class="sub-item">All Events</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/events-waiting-for-approval') ? 'active' : '' }}">
                                <a href="{{ route('manager.waiting.for.approval') }}">
                                    <span class="sub-item">Waiting For Approval</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('manager/events-active-participations*') ? 'active' : '' }}">
                                <a href="{{ url('manager/events-active-participations') }}">
                                    <span class="sub-item">Active Participations</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--calander section-->
                <li class="nav-item {{ request()->is('manager/calendar') ? 'active' : '' }}">
                    <a href="{{ url('manager/calendar') }}"
                        class="nav-link {{ request()->is('manager/calendar') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Calendar</p>
                    </a>
                </li>
                <!--patients section-->
                <li class="nav-item {{ request()->is('manager/patients') || request()->is('manager/patients/*/edit') ? 'active' : '' }}">
                    <a href="{{ url('manager/patients') }}"
                        class="nav-link {{ request()->is('manager/patients') ? 'active' : '' }}">
                        <i class="fas fa-user-plus"></i>
                        <p>Referred Patients</p>
                    </a>
                </li>
                <!--event section-->
                <!--visit plan section-->
                <li class="nav-item {{ request()->is('manager/visit-plans*') || request()->is('manager/edit-visit-plan*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseVisitPlan"
                        class="{{ (request()->is('manager/visit-plans*') || request()->is('manager/edit-visit-plan*')) ? '' : 'collapsed' }}"
                        aria-expanded="{{ (request()->is('manager/visit-plans*') || request()->is('manager/edit-visit-plan*')) ? 'true' : 'false' }}">
                        <i class="fas fa-tasks"></i>
                        <p>Visit Plans</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ (request()->is('manager/visit-plans*') || request()->is('manager/edit-visit-plan*')) ? 'show' : '' }}" id="collapseVisitPlan">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('manager/visit-plans/create') ? 'active' : '' }}">
                                <a href="{{ route('manager.visit-plans.create') }}"><span class="sub-item">Add Visit Plan</span></a>
                            </li>
                            <li class="{{ (request()->is('manager/visit-plans') || request()->is('manager/visit-plans/*/edit') || request()->is('manager/edit-visit-plan/*')) ? 'active' : '' }}">
                                <a href="{{ route('manager.visit-plans.index') }}"><span class="sub-item">All Visit Plans</span></a>
                            </li>
                            <li class="{{ request()->is('manager/visit-plans/interested-mrs') ? 'active' : '' }}">
                                <a href="{{ route('manager.visit.plans.interested.mrs') }}"><span class="sub-item">Interested MRS</span></a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--daily mr report section-->
                <li class="nav-item {{ request()->is('manager/daily-mr-reports*') || request()->is('manager/edit-report*') ? 'active' : '' }}">
                    <a href="{{ url('manager/daily-mr-reports') }}" class="nav-link {{ request()->is('manager/daily-mr-reports*') || request()->is('manager/edit-report*') ? 'active' : '' }}">
                        <i class="fas fa-chart-bar"></i>
                        <p>Daily MR Reports</p>
                    </a>
                </li>
                <!--sales section-->
                <li class="nav-item {{ request()->is('manager/sales') || request()->is('manager/sales/*/edit') ? 'active' : '' }}">
                    <a href="{{ url('manager/sales') }}"
                        class="nav-link {{ request()->is('manager/sales') || request()->is('manager/sales/*/edit') ? 'active' : '' }}">
                        <i class="fas fa-handshake"></i>
                        <p>All Sales</p>
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