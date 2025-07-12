<!doctype html>
<html lang="ar" dir="rtl">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Meta -->
    <meta name="description" content="Responsive Bootstrap4 Dashboard Template for Teachers">
    <meta name="author" content="ParkerThemes">
    <link rel="shortcut icon" href="images/abajimLOGO.png" />
    <meta name="csrf-token" content="{{ csrf_token() }}"/>

    <!-- Title -->
    <title>فضاء المعلم</title>

    <!-- Google Fonts (Patrick Hand for theme, Tajawal as fallback) -->
    <link href="https://fonts.googleapis.com/css2?family=Patrick+Hand&family=Tajawal:wght@400;700&display=swap" rel="stylesheet">

    <!-- *************
        ************ Common Css Files *************
    ************ -->
    <!-- Bootstrap css -->
    <link rel="stylesheet" href="{{url('enseignant/css/bootstrap.min.css')}}">

    <!-- Icomoon Font Icons css -->
    <link rel="stylesheet" href="{{url('enseignant/fonts/style.css')}}">

    <!-- Main css -->
    <link rel="stylesheet" href="{{url('enseignant/css/main.css')}}">

    <!-- *************
        ************ Vendor Css Files *************
    ************ -->
    <!-- DateRange css -->
    <link rel="stylesheet" href="{{url('enseignant/vendor/daterange/daterange.css')}}" />

    <!-- Chartist css -->
    <link rel="stylesheet" href="{{url('enseignant/vendor/chartist/css/chartist.min.css')}}" />
    <link rel="stylesheet" href="{{url('enseignant/vendor/chartist/css/chartist-custom.css')}}" />

    <!-- Data Tables -->
    <link rel="stylesheet" href="{{url('enseignant/vendor/datatables/dataTables.bs4.css')}}" />
    <link rel="stylesheet" href="{{url('enseignant/vendor/datatables/dataTables.bs4-custom.css')}}" />
    <link href="{{url('enseignant/vendor/datatables/buttons.bs.css')}}" rel="stylesheet" />

    <!-- Custom Theme CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #e1f5fe 0%, #b3e5fc 100%);
            font-family: 'Patrick Hand', cursive, 'Tajawal', sans-serif;
            margin: 0;
            padding: 0;
            position: relative;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(180deg, rgba(2, 136, 209, 0.2), rgba(255, 255, 255, 0.5)),
                        url('https://www.transparenttextures.com/patterns/chalkboard.png');
            opacity: 0.7;
            z-index: -1;
        }

        /* Header Styling */
        .header {
            background: #0288d1;
            border-bottom: 3px solid #b3e5fc;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo-wrapper img {
            height: 50px;
            transition: transform 0.3s ease;
        }

        .logo-wrapper img:hover {
            transform: scale(1.1);
        }

        .header-items .user-settings {
            color: #fff;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .header-items .user-settings .user-name {
            margin-right: 10px;
        }

        .header-items .user-settings .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            overflow: hidden;
            background: #fff;
            position: relative;
        }

        .header-items .user-settings .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .header-items .user-settings .status {
            position: absolute;
            bottom: 0;
            right: 0;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #66bb6a;
            border: 2px solid #fff;
        }

        .dropdown-menu {
            background: rgba(255, 255, 255, 0.95);
            border: 2px solid #0288d1;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            padding: 10px 0;
            font-size: 1.1rem;
        }

        .header-profile-actions a {
            color: #0277bd;
            padding: 10px 20px;
            display: block;
            transition: background 0.3s ease;
        }

        .header-profile-actions a:hover {
            background: #ffca28;
            color: #fff;
            text-decoration: none;
        }

        .header-profile-actions .header-user-profile {
            padding: 15px;
            text-align: center;
        }

        .header-profile-actions .header-user-profile img {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid #ffca28;
        }

        .header-profile-actions .header-user-profile h5 {
            margin: 10px 0 5px;
            font-size: 1.2rem;
            color: #0288d1;
        }

        .header-profile-actions .header-user-profile p {
            margin: 0;
            font-size: 0.9rem;
            color: #666;
        }

        /* Main Container */
        .main-container {
            position: relative;
            z-index: 1;
            padding: 80px 20px 20px; /* Adjusted for fixed header */
            min-height: calc(100vh - 120px); /* Ensure content fills the page */
        }

        /* Page Header */
        .page-header {
            background: rgba(255, 255, 255, 0.95);
            border: 3px solid #0288d1;
            border-radius: 15px;
            margin-bottom: 20px;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .breadcrumb {
            background: none;
            padding: 0;
            margin: 0;
        }

        .breadcrumb-item {
            color: #0277bd;
            font-size: 1.2rem;
        }

        .breadcrumb-item.active {
            color: #ef5350;
        }

        .app-actions li a {
            color: #0288d1;
            font-size: 1.5rem;
        }

        .app-actions li a:hover {
            color: #ffca28;
        }

        /* Table Container */
        .table-container {
            background: rgba(255, 255, 255, 0.95);
            border: 3px solid #0288d1;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }

        .t-header {
            color: #0288d1;
            font-size: 1.8rem;
            text-shadow: 1px 1px #b3e5fc;
            text-align: center;
            margin-bottom: 15px;
        }

        .custom-table th, .custom-table td {
            border-bottom: 1px solid #b3e5fc;
        }

        .custom-table th {
            background: #0288d1;
            color: #fff;
            text-shadow: 1px 1px #0277bd;
        }

        /* Buttons */
        .btn-primary {
            background: #0288d1;
            border: none;
            color: #fff;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 1.2rem;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: #0277bd;
        }

        .btn-outline-danger {
            background: #ef5350;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        .btn-outline-danger:hover {
            background: #d32f2f;
        }

        .btn-outline-success {
            background: #66bb6a;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        .btn-outline-success:hover {
            background: #43a047;
        }

        .btn-danger {
            background: #ef5350;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 1rem;
            transition: background 0.3s ease;
        }

        .btn-danger:hover {
            background: #d32f2f;
        }

        /* Alerts */
        .alert-success {
            background: #66bb6a;
            color: #fff;
            border-radius: 5px;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #43a047;
        }

        .alert-success .close {
            color: #fff;
            opacity: 1;
            font-size: 1.2rem;
        }

        /* No Quizzes Message */
        .no-quizzes {
            text-align: center;
            color: #ef5350;
            font-size: 1.5rem;
            padding: 20px;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .header {
                padding: 10px;
            }
            .logo-wrapper img {
                height: 40px;
            }
            .page-header {
                flex-direction: column;
                text-align: center;
            }
            .app-actions {
                margin-top: 10px;
            }
            .main-container {
                padding: 60px 10px 20px;
            }
        }
    </style>
</head>
<body>

    <!-- Loading starts -->
    <div id="loading-wrapper" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255, 255, 255, 0.8); z-index: 1040; display: flex; justify-content: center; align-items: center;">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
    <!-- Loading ends -->

    <!-- *************
        ************ Header section start *************
    ************* -->

    <!-- Header start -->
    <header class="header">
        <div class="logo-wrapper">
            <a href="{{url('/dashboard')}}" class="logo">
			<img src="{{ asset('images/abajim.png') }}" alt="Abajim Logo" />
		    </a>
        </div>
        <div class="header-items">
            <!-- Header actions start -->
            <ul class="header-actions">
                <li class="dropdown">
                    <a href="#" id="userSettings" class="user-settings" data-toggle="dropdown" aria-haspopup="true">
                        @if (Auth::check())
                            <span class="user-name">{{ Auth::user()->full_name }}<span class="status actif"></span></span>
                            <span class="avatar">
                                <img src="{{url('enseignant/img/user.png')}}" alt="User Avatar" />
                                <span class="status actif"></span>
                            </span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userSettings">
                        <div class="header-profile-actions">
                            <div class="header-user-profile">
                                <div class="header-user">
                                    <img src="{{url('enseignant/img/user.png')}}" alt="User Profile" />
                                </div>
                                <h5>{{ Auth::user()->full_name }}</h5>
                                <p>{{ Auth::user()->role_name }}</p>
                            </div>
                            <a href="{{route('enseignant.edit')}}"><i class="icon-settings1"></i> إعدادات الحساب</a>
                            <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="icon-log-out1"></i> تسجيل الخروج</a>
                        </div>
                    </div>
                </li>
            </ul>
            <!-- Header actions end -->
        </div>
    </header>
    <!-- Header end -->

    <!-- Screen overlay start -->
    <div class="screen-overlay" style="position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 1020; display: none;"></div>
    <!-- Screen overlay end -->

    <!-- *************
        ************ Header section end *************
    ************* -->

    <!-- Container fluid start -->
    <div class="container-fluid">
        <!-- Navigation start -->
        @include('enseignant.layout.header')
        <!-- Navigation end -->

        <!-- *************
            ************ Main container start *************
        ************* -->
        <div class="main-container">
            @yield('content')
        </div>
        <!-- *************
            ************ Main container end *************
        ************* -->

        <!-- Footer start -->
        @include('enseignant.layout.footer')
        <!-- Footer end -->
    </div>
    <!-- Container fluid end -->

    <!-- *************
        ************ Required JavaScript Files *************
    ************* -->
    <!-- Required jQuery first, then Bootstrap Bundle JS -->
    <script src="{{url('enseignant/js/jquery.min.js')}}"></script>
    <script src="{{url('enseignant/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{url('enseignant/js/moment.js')}}"></script>

    <!-- *************
        ************ Vendor Js Files *************
    ************* -->
    <!-- Slimscroll JS -->
    <script src="{{url('enseignant/vendor/slimscroll/slimscroll.min.js')}}"></script>
    <script src="{{url('enseignant/vendor/slimscroll/custom-scrollbar.js')}}"></script>

    <!-- Daterange -->
    <script src="{{url('enseignant/vendor/daterange/daterange.js')}}"></script>
    <script src="{{url('enseignant/vendor/daterange/custom-daterange.js')}}"></script>

    <!-- jVector Maps -->
    <script src="{{url('enseignant/vendor/jvectormap/jquery-jvectormap-2.0.3.min.js')}}"></script>
    <script src="{{url('enseignant/vendor/jvectormap/world-mill-en.js')}}"></script>
    <script src="{{url('enseignant/vendor/jvectormap/gdp-data.js')}}"></script>

    <!-- Rating JS -->
    <script src="{{url('enseignant/vendor/rating/raty.js')}}"></script>
    <script src="{{url('enseignant/vendor/rating/raty-custom.js')}}"></script>

    <!-- Main Js Required -->
    <script src="{{url('enseignant/js/main.js')}}"></script>
    <!-- Data Tables -->
    <script src="{{url('enseignant/vendor/datatables/dataTables.min.js')}}"></script>
    <script src="{{url('enseignant/vendor/datatables/dataTables.bootstrap.min.js')}}"></script>

    <!-- Custom Data tables -->
    <script src="{{url('enseignant/vendor/datatables/custom/custom-datatables.js')}}"></script>
    <script src="{{url('enseignant/vendor/datatables/custom/fixedHeader.js')}}"></script>

    <!-- Download / CSV / Copy / Print -->
    <script src="{{url('enseignant/vendor/datatables/buttons.min.js')}}"></script>
    <script src="{{url('enseignant/vendor/datatables/jszip.min.js')}}"></script>
    <script src="{{url('enseignant/vendor/datatables/pdfmake.min.js')}}"></script>
    <script src="{{url('enseignant/vendor/datatables/vfs_fonts.js')}}"></script>
    <script src="{{url('enseignant/vendor/datatables/html5.min.js')}}"></script>
    <script src="{{url('enseignant/vendor/datatables/buttons.print.min.js')}}"></script>

    <script src="{{url('enseignant/js/custom.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- Custom Script to Hide Loading -->
    <script>
        $(window).on('load', function() {
            $('#loading-wrapper').fadeOut(500);
        });
    </script>
</body>
</html>