<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com -->
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> Pantukan </title>
    <link rel="stylesheet" href="{{ asset('css/homepage_view.css') }}">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datepicker.css') }}" />
    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">
    <!-- Bootstrap Select JS -->
    

    @yield('custom_css')
</head>

<style>
    nav-links,
    ol,
    ul {
        padding-left: 0px !important;
    }

    .home-section .home-content {
        position: relative;
        padding-top: 85px;
    }

    .button-success {
        background: rgb(246, 145, 27) !important;
        color: white
    }

    .sidebar {
        /* background: rgb(32, 80, 153) !important; */
        background: white !important;
    }

    .modal-padding {
        padding: 5px;
    }

    .btn {
        padding: 5px !important;
        font-size: 14px !important;
    }

    .modal-color {
        background: rgb(8, 29, 69)
    }

    .form-control {
        padding: 5px !important;
    }

    label {
        font-weight: bold;
        font-size: 13px
    }
    .logo_name{
        margin-left:11px; 
    }
    .links_name{
        color: black !important
    }
    .nav-links li:hover .links_name{
        color: white !important; 
    }

    .nav-links a:hover i {
        color: white !important; 
    }
</style>

<body>
    <div class="sidebar {{ request()->is('booking') ? 'active' : '' }}">
        <div class="logo-details">
            <img src="{{ asset('img/pantukan_logo.png') }}" style="width: 50px;" alt="">
            <span class="logo_name">Pantukan</span>
        </div>
        {{-- navigation  --}}
        @include('pos.navigation')
    </div>
    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="dashboard">
                    @yield('header')
                </span>
            </div>
            <div class="search-box">
                <span>Current Date and Time: {{ \Carbon\Carbon::now()->format('F d, Y h:i A') }}</span>
            </div>

            {{--  profile details  --}}
            <x-profile-details />
        </nav>

        <div class="home-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </section>

    <script>
        let sidebar = document.querySelector(".sidebar");
        let sidebarBtn = document.querySelector(".sidebarBtn");
        sidebarBtn.onclick = function() {
            sidebar.classList.toggle("active");
            if (sidebar.classList.contains("active")) {
                sidebarBtn.classList.replace("bx-menu", "bx-menu-alt-right");
            } else
                sidebarBtn.classList.replace("bx-menu-alt-right", "bx-menu");
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('js')

</body>

</html>
