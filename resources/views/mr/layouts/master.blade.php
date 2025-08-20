<!DOCTYPE html>
<html lang="en">
   <head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge" />
      <title>Pd Management</title>
      <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport"/>
      <link rel="icon" href="{{ asset('public/admin/images/Final-Logo-BMS 2 (1) - Copy.png') }}" type="image/x-icon" />
      <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
      <!--css files-->
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
                        <img
                           src="{{ asset('public/admin/assets/img/kaiadmin/logo_light.svg') }}"
                           alt="navbar brand"
                           class="navbar-brand"
                           height="20"
                        />
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
               <nav
                  class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom"
                  >
                  <div class="container-fluid">
                     <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                        <li class="nav-item topbar-user dropdown hidden-caret">
                           <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                              <div class="avatar-sm">
                                 @if (auth()->user()->image)
                                    <img src="{{ url('public/uploads/users/' . auth()->user()->image) }}" alt="image profile" class="avatar-img rounded" />
                                 @else 
                                    <img src="{{ asset('public/uploads/users/default.png') }}" alt="image profile" class="avatar-img rounded"/>
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
                                             <img src="{{ asset('public/uploads/users/' . auth()->user()->image) }}" alt="image profile" class="avatar-img rounded" />
                                          @else 
                                             <img src="{{ asset('public/uploads/users/default.png') }}" alt="image profile" class="avatar-img rounded"/>
                                          @endif
                                       </div>
                                       <div class="u-text">
                                          <h4>{{ auth()->user()->name }}</h4>
                                          <p class="text-muted">{{ auth()->user()->email }}</p>
                                          <a href="{{ url('admin/profile') }}" class="profile-btn btn-xs btn-secondarys btn-sm">View Profile</a>
                                       </div>
                                    </div>
                                 </li>
                                 <li>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item {{ Request::is('admin/edit-profile') ? 'active' : '' }}" href="{{ url('admin/edit-profile') }}">
                                       Edit Profile
                                    </a>
                                    <a class="dropdown-item {{ Request::is('admin/change-password') ? 'active' : '' }}" href="{{ url('admin/change-password') }}">
                                       Update Password
                                    </a>
                                    <a href="{{ route('logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                       Sign Out
                                    </a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
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
         <!--custom template-->
         <div class="custom-template">
            <div class="title">Settings</div>
            <div class="custom-content">
               <div class="switcher">
                  <div class="switch-block">
                     <h4>Logo Header</h4>
                     <div class="btnSwitch">
                        <button
                           type="button"
                           class="selected changeLogoHeaderColor"
                           data-color="dark"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="blue"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="purple"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="light-blue"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="green"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="orange"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="red"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="white"
                           >
                        </button>
                        <br/>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="dark2"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="blue2"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="purple2"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="light-blue2"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="green2"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="orange2"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeLogoHeaderColor"
                           data-color="red2"
                           >
                        </button>
                     </div>
                  </div>
                  <div class="switch-block">
                     <h4>Navbar Header</h4>
                     <div class="btnSwitch">
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="dark"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="blue"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="purple"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="light-blue"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="green"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="orange"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="red"
                           >
                        </button>
                        <button
                           type="button"
                           class="selected changeTopBarColor"
                           data-color="white"
                           >
                        </button>
                        <br/>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="dark2"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="blue2"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="purple2"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="light-blue2"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="green2"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="orange2"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeTopBarColor"
                           data-color="red2"
                           >
                        </button>
                     </div>
                  </div>
                  <div class="switch-block">
                     <h4>Sidebar</h4>
                     <div class="btnSwitch">
                        <button
                           type="button"
                           class="changeSideBarColor"
                           data-color="white"
                           >
                        </button>
                        <button
                           type="button"
                           class="selected changeSideBarColor"
                           data-color="dark"
                           >
                        </button>
                        <button
                           type="button"
                           class="changeSideBarColor"
                           data-color="dark2"
                           >
                        </button>
                     </div>
                  </div>
               </div>
            </div>
            <div class="custom-toggle">
               <i class="icon-settings"></i>
            </div>
         </div>
      </div>
      <script>
         var base_url = '{{ url("/") }}'; 
      </script>
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
      <!--core js files-->
      <script src="{{ asset('public/admin/assets/js/custom-ajax.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/custom-script.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/core/jquery-3.7.1.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/core/popper.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/core/bootstrap.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/select2/js/select2.js') }}"></script>
      <script src="{{ asset('public/admin/assets/select2/js/select2.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/plugin/chart.js/chart.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/plugin/chart-circle/circles.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/plugin/datatables/datatables.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/plugin/jsvectormap/jsvectormap.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/plugin/jsvectormap/world.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/plugin/sweetalert/sweetalert.min.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/kaiadmin.min.js') }}"></script>
      <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
      <script src="{{ asset('public/admin/assets/js/setting-demo.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/demo.js') }}"></script>
      <script src="{{ asset('public/admin/assets/js/plugin/webfont/webfont.min.js') }}"></script>
      <script>
         WebFont.load({
           google: { families: ["Public Sans:300,400,500,600,700"] },
           custom: {
            families: [
               "Font Awesome 5 Solid",
               "Font Awesome 5 Regular",
               "Font Awesome 5 Brands",
               "simple-line-icons",
            ],
            urls: ["assets/css/fonts.min.css"],
           },
           active: function () {
             sessionStorage.fonts = true;
           },
         });
      </script>
      <script>
         $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
           type: "line",
           height: "70",
           width: "100%",
           lineWidth: "2",
           lineColor: "#177dff",
           fillColor: "rgba(23, 125, 255, 0.14)",
         });
          
         $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
           type: "line",
           height: "70",
           width: "100%",
           lineWidth: "2",
           lineColor: "#f3545d",
           fillColor: "rgba(243, 84, 93, .14)",
         });
          
         $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
           type: "line",
           height: "70",
           width: "100%",
           lineWidth: "2",
           lineColor: "#ffa534",
           fillColor: "rgba(255, 165, 52, .14)",
         });
      </script>
      <script>
         $(document).ready(function () {
            $("#add-row").DataTable({
               pageLength: 5,
            });
         });
      </script>
      <!--initialize summernot-->
      <script>
         $(document).ready(function() {
            $('#summernote').summernote({
               height: 350,
               placeholder: 'Enter description here...'
            });
         });
      </script>
      <script>
         $(document).ready(function() {
            $('#short_summernote').summernote({
               height: 380,
               placeholder: 'Enter key features here...'
            });
         });
      </script>
      <script>
         $(document).ready(function() {
            $('#faq').summernote({
               height: 150,
               placeholder: 'Enter answer here...'
            });
         });
      </script>
      <script>
         $(function () {
            $('.select2').select2()
         });
      </script>
   </body>
</html>

