<!--sidebar-->
<div class="sidebar" data-background-color="dark">
    <div class="sidebar-logo">
        <!--header section-->
        <div class="logo-header" data-background-color="dark">
            <a href="{{ url('vendor/dashboard') }}" class="logo">
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
                <li class="nav-item {{ Request::is('purchase-manager/dashboard') ? 'active' : '' }}">
                    <a href="{{ url('purchase-manager/dashboard') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <!-- Purchase Orders -->
                <li class="nav-item {{ request()->is('purchase-manager/purchase-orders*') ? 'active' : '' }}">
                    <a data-bs-toggle="collapse" href="#collapsePO"
                        class="{{ request()->is('purchase-manager/purchase-orders*') ? '' : 'collapsed' }}"
                        aria-expanded="{{ request()->is('purchase-manager/purchase-orders*') ? 'true' : 'false' }}">
                        <i class="fas fa-file-invoice"></i>
                        <p>Purchase Orders</p>
                        <span class="caret"></span>
                    </a>

                    <div class="collapse {{ request()->is('purchase-manager/purchase-orders*') ? 'show' : '' }}" id="collapsePO">
                        <ul class="nav nav-collapse">
                            <li class="{{ request()->is('purchase-manager/purchase-orders/create') ? 'active' : '' }}">
                                <a href="{{ route('purchase-manager.purchase-orders.create') }}">
                                    <span class="sub-item">Create PO</span>
                                </a>
                            </li>
                            <li
                                class="{{ request()->is('purchase-manager/purchase-orders') || (request()->is('purchase-manager/purchase-orders/*') && !request()->is('purchase-manager/purchase-orders/create')) ? 'active' : '' }}">
                                <a href="{{ route('purchase-manager.purchase-orders.index') }}">
                                    <span class="sub-item">All Purchase Orders</span>
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
