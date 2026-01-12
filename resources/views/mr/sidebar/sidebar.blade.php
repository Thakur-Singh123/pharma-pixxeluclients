<!--sidebar-->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!--header section-->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('admin/dashboard') }}" class="logo">
                <img src="{{ asset('public/admin/images/Ad People Logo.svg') }}" alt="navbar brand" class="navbar-brand" height="20" />
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
            @if(auth()->user()->user_type == 'MR' && !auth()->user()->can_sale)
            <ul class="nav nav-secondary">
                <!--dashboard section-->
                <li class="nav-item {{ Request::is('mr/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('mr/dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <!--attendance section-->
                <li class="nav-item {{ request()->is('mr/attendance*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseMR"
                        class="{{ request()->is('mr/attendance*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/attendance*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-check"></i>
                        <p>Attendances</p>
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
                <!--Clients section-->
                <li class="nav-item {{ request()->is('mr/clients*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseClients"
                        class="{{ request()->is('mr/clients*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/clients*') ? 'true' : 'false' }}">
                        <i class="fas fa-address-card"></i>
                        <p>Clients</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/clients*') ? 'show' : '' }}" id="collapseClients">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/clients/create') ? 'active' : '' }}">
                                <a href="{{ url('mr/clients/create') }}">
                                    <span class="sub-item">Add Client</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/clients') || request()->is('mr/clients/*/edit') ? 'active' : '' }}">
                                <a href="{{ url('mr/clients') }}">
                                    <span class="sub-item">All Clients</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--daily visit section-->
                <li class="nav-item {{ request()->is('mr/visits*') || request()->is('mr/areas-served') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseVisit"
                        class="{{ request()->is('mr/visits*') || request()->is('mr/areas-served') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/visits*') || request()->is('mr/areas-served') ? 'true' : 'false' }}">
                        <i class="fas fa-walking"></i>
                        <p>Daily Visits</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/visits*') || request()->is('mr/areas-served') ? 'show' : '' }}" id="collapseVisit">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/visits/create') ? 'active' : '' }}">
                                <a href="{{ url('mr/visits/create') }}">
                                    <span class="sub-item">Add Visit</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/visits') || request()->is('mr/visits/edit/*') ? 'active' : '' }}">
                                <a href="{{ url('mr/visits') }}">
                                    <span class="sub-item">All Visits</span>
                                </a>
                            </li>
                            <!-- <li class="{{ request()->is('mr/areas-served') || request()->is('mr/areas-served') || request()->is('mr/visits/edit/*') ? 'active' : '' }}">
                                <a href="{{ url('mr/areas-served') }}">
                                    <span class="sub-item">Areas Served</span>
                                </a>
                            </li> -->
                        </ul>
                    </div>
                </li>
                <!--Task section-->
                <li class="nav-item {{ request()->is('mr/tasks*') || request()->is('mr/tasks-assigned-by-manager') || request()->is('mr/tasks-himself') || request()->is('mr/pending-approval') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseTask"
                        class="{{ request()->is('mr/tasks*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/tasks*') ? 'true' : 'false' }}">
                        <i class="fas fa-clipboard-list"></i>
                        <p>Tasks</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/tasks*') || request()->is('mr/tasks-assigned-by-manager') || request()->is('mr/tasks-himself') || request()->is('mr/pending-approval') || request()->is('mr/tasks-calendar-approved-by-manager') || request()->is('mr/tasks-calendar-rejected-by-manager') ? 'show' : '' }}" id="collapseTask">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/tasks/create') ? 'active' : '' }}">
                                <a href="{{ route('mr.tasks.create') }}">
                                    <span class="sub-item">Add Task</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/tasks') || request()->is('mr/tasks/*/edit') ? 'active' : '' }}">
                                <a href="{{ route('mr.tasks.index') }}">
                                    <span class="sub-item">All Tasks</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/tasks-assigned-by-manager') ? 'active' : '' }}">
                                <a href="{{ url('mr/tasks-assigned-by-manager') }}">
                                    <span class="sub-item">Assigned By Manager</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/tasks-himself') ? 'active' : '' }}">
                                <a href="{{ url('mr/tasks-himself') }}">
                                    <span class="sub-item">Created By Himself</span>
                                </a>
                            </li>
                            <!-- <li class="{{ request()->is('mr/pending-approval') ? 'active' : '' }}">
                                <a href="{{ url('mr/pending-approval') }}">
                                    <span class="sub-item">Pending Approval</span>
                                </a>
                            </li> -->
                            <li class="{{ request()->is('mr/tasks-calendar-rejected-by-manager') ? 'active' : '' }}">
                                <a href="{{ url('mr/tasks-calendar-rejected-by-manager') }}">
                                    <span class="sub-item">Rejected Calendar</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/tasks-calendar-approved-by-manager') ? 'active' : '' }}">
                                <a href="{{ url('mr/tasks-calendar-approved-by-manager') }}">
                                    <span class="sub-item">Approved Calendar</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--doctor section-->
                <!-- <li class="nav-item {{ request()->is('mr/doctors*') ? 'active' : '' }}">
                    <a href="{{ url('mr/doctors') }}"
                        class="nav-link {{ request()->is('mr/doctors') ? 'active' : '' }}">
                        <i class="fas fa-user-md"></i>
                        <p>Doctors</p>
                    </a>
                </li> -->
                <!--patients section-->
                <!-- <li class="nav-item {{ request()->is('mr/patients*') ? 'active' : '' }}">
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
                            <li class="{{ request()->is('mr/patients') || request()->is('mr/patients/*/edit') ? 'active' : '' }}">
                                <a href="{{ url('mr/patients') }}">
                                    <span class="sub-item">All Patient</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> -->
                <!--tada section-->
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
                            <li class="{{ request()->is('mr/tada') || request()->is('mr/tada/*/edit') ? 'active' : '' }}">
                                <a href="{{ url('mr/tada') }}">
                                    <span class="sub-item">All TA/DA</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--event section-->
                @php
                    $isEventActive = request()->is('mr/events*') || request()->is('mr/active-participations*') || request()->is('mr/events-assigne-by-manager*') || request()->is('mr/events-himself*');
                @endphp
                <li class="nav-item {{ $isEventActive ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseEvent"
                        class="{{ $isEventActive ? '' : 'collapsed' }}"
                        aria-expanded="{{ $isEventActive ? 'true' : 'false' }}">
                        <i class="fas fa-calendar-check"></i>
                        <p>Events</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/events*') || request()->is('mr/active-participations*') ? 'show' : '' }}" id="collapseEvent">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/events/create') ? 'active' : '' }}">
                                <a href="{{ route('mr.events.create') }}">
                                    <span class="sub-item">Add Event</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/events') || request()->is('mr/events/edit/*') ? 'active' : '' }}">
                                <a href="{{ route('mr.events.index') }}">
                                    <span class="sub-item">All Events</span>
                                </a>
                            </li>
                             <li class="{{ request()->is('mr/events-assigned-by-manager') ? 'active' : '' }}">
                                <a href="{{ url('mr/events-assigned-by-manager') }}">
                                    <span class="sub-item">Assigned By Manager</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/events-himself') ? 'active' : '' }}">
                                <a href="{{ url('mr/events-himself') }}">
                                    <span class="sub-item">Created By Himself</span>
                                </a>
                            </li>
                             <li class="{{ request()->is('mr/events/pending-for-approval') ? 'active' : '' }}">
                                <a href="{{ route('mr.events.pending-for-approval') }}">
                                    <span class="sub-item">Pending For Approval</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/events-active-participations*') ? 'active' : '' }}">
                                <a href="{{ url('mr/events-active-participations') }}">
                                    <span class="sub-item">Active Participations</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--calander section-->
                <li class="nav-item {{ request()->is('mr/calendar*') ? 'active' : '' }}">
                    <a href="{{ url('mr/calendar') }}"
                        class="nav-link {{ request()->is('mr/calendar') ? 'active' : '' }}">
                        <i class="fas fa-calendar-alt"></i>
                        <p>Calendar</p>
                    </a>
                </li>
                <!--referred patient section-->
                <li class="nav-item {{ request()->is('mr/patients*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseReferredPatient"
                        class="{{ request()->is('mr/patients*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/patients*') ? 'true' : 'false' }}">
                        <i class="fas fa-user-plus"></i>
                        <p>Referred Patients</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/patients*') ? 'show' : '' }}" id="collapseReferredPatient">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/patients/create') ? 'active' : '' }}">
                                <a href="{{ route('mr.patients.create') }}">
                                    <span class="sub-item">Add Patient</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/patients') || request()->is('mr/patients/*/edit') ? 'active' : '' }}">
                                <a href="{{ url('mr/patients') }}">
                                    <span class="sub-item">All Patients</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--problems & challenges faced section-->
                <li class="nav-item {{ request()->is('mr/problems*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseProblem"
                        class="{{ request()->is('mr/problems*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/problems*') ? 'true' : 'false' }}">
                       <i class="fas fa-exclamation-triangle"></i>
                        <p>Problem & Challenges</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/problems*') ? 'show' : '' }}" id="collapseProblem">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/problems/create') ? 'active' : '' }}">
                                <a href="{{ route('mr.problems.create') }}">
                                    <span class="sub-item">Add Problem</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/problems') || request()->is('mr/problems/*/edit') ? 'active' : '' }}">
                                <a href="{{ url('mr/problems') }}">
                                    <span class="sub-item">All Problems</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!--visit plan section-->
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
                <!--Tour Plan Section-->
                <!-- <li class="nav-item {{ request()->is('mr/assigned-tour-plans*') || request()->is('mr/updated-tour-plans*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseTourPlan"
                        class="nav-link {{ request()->is('mr/assigned-tour-plans*') || request()->is('mr/updated-tour-plans*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/assigned-tour-plans*') || request()->is('mr/updated-tour-plansn*') ? 'true' : 'false' }}">
                        <i class="fas fa-map-marked-alt"></i>
                        <p>Tour Plans</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/assigned-tour-plans*') || request()->is('mr/updated-tour-plans*') ? 'show' : '' }}" id="collapseTourPlan">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/assigned-tour-plans') ? 'active' : '' }}">
                                <a href="{{ url('mr/assigned-tour-plans') }}">
                                    <span class="sub-item">Assigned Tour Plans</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/updated-tour-plans') ? 'active' : '' }}">
                                <a href="{{ url('mr/updated-tour-plans') }}">
                                    <span class="sub-item">Updated Tour Plans</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> -->
                <!--report section-->
                {{-- <li class="nav-item {{ request()->is('mr/daily-reports*') ? 'active' : '' }}">
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
                            <li class="{{ request()->is('mr/daily-reports') || request()->is('mr/daily-reports/*/edit') ? 'active' : '' }}">
                                <a href="{{ route('mr.daily-reports.index') }}">
                                    <span class="sub-item">All Reports</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li> --}}
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
            <!--MR sales dashboard section-->
            @else
            <ul class="nav nav-secondary">
                {{-- <!--dashboard section-->
                <li class="nav-item {{ Request::is('admin/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('admin/dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li> --}}
                <!---sales section-->
                <li class="nav-item {{ request()->is('mr/sales*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapseSales"
                        class="{{ request()->is('mr/sales*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('mr/sales*') ? 'true' : 'false' }}">
                        <i class="fas fa-handshake"></i>
                        <p>Sales</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse {{ request()->is('mr/sales*') ? 'show' : '' }}" id="collapseSales">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('mr/sales/create') ? 'active' : '' }}">
                                <a href="{{ route('mr.sales.create') }}">
                                    <span class="sub-item">Add Sale</span>
                                </a>
                            </li>
                            <li class="{{ request()->is('mr/sales') || request()->is('mr/sales/*/edit') ? 'active' : '' }}">
                                <a href="{{ route('mr.sales.index') }}">
                                    <span class="sub-item">All Sales</span>
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
            @endif
        </div>
    </div>
</div>
