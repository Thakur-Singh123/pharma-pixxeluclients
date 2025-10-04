<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Pd Management</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <link rel="icon" href="{{ asset('public/admin/images/Final-Logo-BMS 2 (1) - Copy.png') }}" type="image/x-icon" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
    <!--css files-->
    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/select2/css/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/select2/css/select2.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/plugins.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/kaiadmin.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/fonts.css') }}" />
    <link rel="stylesheet" href="{{ asset('public/admin/assets/css/demo.css') }}" />
</head>
<body>
    <div class="wrapper">
        @include('mr.sidebar.sidebar')
        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <div class="logo-header" data-background-color="dark">
                        <a href="#" class="logo">
                            <img src="{{ asset('public/admin/assets/img/kaiadmin/logo_light.svg') }}" alt="navbar brand"
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
                <nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
                    <div class="container-fluid">
                        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                            <li class="nav-item topbar-icon dropdown hidden-caret submenu">
                                <a class="nav-link dropdown-toggle" href="#" id="notifDropdown"
                                    role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-bell"></i>
                                    <span
                                        class="notification">{{ auth()->user()->unreadNotifications->count() }}</span>
                                </a>
                                <ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown"
                                    data-bs-popper="static">
                                    <li>
                                        <div class="dropdown-title">
                                            You have {{ auth()->user()->unreadNotifications->count() }} new
                                            notification
                                        </div>
                                    </li>
                                    <li>
                                        <div class="scroll-wrapper notif-scroll scrollbar-outer"
                                            style="position: relative;">
                                            <div class="notif-scroll scrollbar-outer scroll-content"
                                                style="height: auto; margin-bottom: 0px; margin-right: 0px; max-height: 244px;">
                                                <div class="notif-center">
                                                    @forelse(auth()->user()->unreadNotifications as $notification)
                                                        <a href="{{ route('notifications.read', $notification->id) }}">
                                                            <div class="notif-icon notif-success">
                                                                <i class=" {{ $notification->data['icon'] }}"></i>
                                                            </div>
                                                            <div class="notif-content">
                                                                <span class="block">
                                                                    {{ $notification->data['message'] }}
                                                                </span>
                                                                <span
                                                                    class="time">{{ $notification->created_at->diffForHumans() }}
                                                                </span>
                                                            </div>
                                                        </a>
                                                    @empty
                                                        <span class="dropdown-item">No Notifications</span>
                                                    @endforelse
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="see-all" href="{{ route('notifications.readAll') }}">
                                            Mark all as read <i class="fa fa-check"></i>
                                        </a>
                                    </li>

                                </ul>
                            </li>
                            <li class="nav-item topbar-user dropdown hidden-caret">
                                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#"
                                    aria-expanded="false">
                                    <div class="avatar-sm">
                                        @if (auth()->user()->image)
                                            <img src="{{ url('public/uploads/users/' . auth()->user()->image) }}"
                                                alt="image profile" class="avatar-img rounded" />
                                        @else
                                            <img src="{{ asset('public/uploads/users/default.png') }}"
                                                alt="image profile" class="avatar-img rounded" />
                                        @endif
                                    </div>
                                    <span class="profile-username">
                                        <span class="op-7">Hi,</span>
                                        <span class="fw-bold">{{ auth()->user()->name }}</span>
                                    </span>
                                </a>
                                <ul class="dropdown-menu dropdown-user animated fadeIn">
                                    <div class="dropdown-user-scroll scrollbar-outer">
                                        <li>
                                            <div class="user-box">
                                                <div class="avatar-lg">
                                                    @if (auth()->user()->image)
                                                        <img src="{{ asset('public/uploads/users/' . auth()->user()->image) }}"
                                                            alt="image profile" class="avatar-img rounded" />
                                                    @else
                                                        <img src="{{ asset('public/uploads/users/default.png') }}"
                                                            alt="image profile" class="avatar-img rounded" />
                                                    @endif
                                                </div>
                                                <div class="u-text">
                                                    <h4>{{ auth()->user()->name }}</h4>
                                                    <p class="text-muted">{{ auth()->user()->email }}</p>
                                                    <a href="{{ url('mr/profile') }}"
                                                        class="profile-btn btn-xs btn-secondarys btn-sm">View
                                                        Profile</a>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="dropdown-divider"></div>
                                            <a class="dropdown-item {{ Request::is('mr/edit-profile') ? 'active' : '' }}"
                                                href="{{ url('mr/edit-profile') }}">
                                                Edit Profile
                                            </a>
                                            <a class="dropdown-item {{ Request::is('mr/change-password') ? 'active' : '' }}"
                                                href="{{ url('mr/change-password') }}">
                                                Update Password
                                            </a>
                                            <a href="{{ route('logout') }}" class="dropdown-item"
                                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                Sign Out
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        </li>
                                    </div>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>
            @yield('content')
            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <!--<div class="copyright">
                     ©2025 Food Machine. All rights reserved<i class="fa fa-heart heart text-danger"></i> by
                     <a href="{{ url('/') }}">Food-Machine.com</a>
                  </div>-->
                </div>
            </footer>
        </div>
    </div>
    <script>
        var base_url = '{{ url('/') }}';
    </script>
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
    <!--core js files-->
    <script src="{{ asset('public/admin/assets/js/custom-ajax.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/custom-script.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('public/admin/assets/js/core/bootstrap.min.js') }}"></script>
</body>
</html>
