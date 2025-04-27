<!DOCTYPE html>
<!-- Coding by CodingNepal | www.codingnepalweb.com -->
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <title> Hardware </title>
    <link rel="stylesheet" href="{{ asset('css/homepage_view.css') }}">
    <!-- Boxicons CDN Link -->
    <link href='https://unpkg.com/boxicons@2.0.7/css/boxicons.min.css' rel='stylesheet'>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('css/datepicker.css') }}" />
    <!-- Bootstrap Select CSS -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.14.0-beta2/css/bootstrap-select.min.css">
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
        background: rgb(32, 80, 153) !important;
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

    .logo_name {
        margin-left: 11px;
    }

    /* Container styling */
    .clock-container {
        text-align: center;
        border-radius: 10px;
        /* background: rgba(255, 255, 255, 0.1);
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.3); */
    }

    /* Date styling */
    .date {
        font-size: 14px;
        font-weight: 400;
        color: #bbb;
    }

    /* Time styling */
    .time {
        font-size: 20px;
        font-weight: bold;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hours,
    .minutes,
    .ampm {
        padding: 0 5px;
    }

    /* Blinking colon */
    #colon {
        padding: 0 5px;
        animation: blink 1s infinite;
    }

    @keyframes blink {
        50% {
            opacity: 0;
        }
    }
    .main-content-div{
        background: white;
        padding: 10px;
        margin: 1%;
        border-radius: 10px;
        box-shadow: 20px 15px 26px #c8c8c8;
    
        position: relative;
    }
    .bg-design{
        width: 100%;
        height: 150px;
        background: #052B69;
        position: absolute;
        
    }
    

    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
</style>

<body>
    <div class="sidebar {{ request()->is('booking') ? 'active' : '' }}">
        <div class="logo-details">
            {{-- <img src="{{ asset('img/pantukan_logo.png') }}" style="width: 50px;" alt=""> --}}
            <span class="logo_name">Hardware</span>
        </div>
        {{-- navigation  --}}
        @include('navigation')
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
                {{-- <span>Current Date and Time: {{ \Carbon\Carbon::now()->format('F d, Y h:i A') }}</span> --}}
                <div class="clock-container">
                    <div class="date" id="currentDate"></div>
                    <div class="time">
                        <span class="hours" id="hours"></span>
                        <span id="colon">:</span>
                        <span class="minutes" id="minutes"></span>
                        <span class="ampm" id="ampm"></span>
                    </div>
                </div>
            </div>

            {{--  profile details  --}}
            <x-profile-details />
        </nav>

       
        <div class="home-content">
            <div class="bg-design">

            </div>
            {{-- <div class="main-content"> --}}
            <div class="main-content-div">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            
            
        </div>

        <div class="modal fade" id="changePasswordModal" tabindex="-1"
            aria-labelledby="changePasswordModalLabel" aria-hidden="true" data-bs-backdrop="static"
            data-bs-keyboard="false">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content shadow-lg">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="changePasswordModalLabel">Change Password</h5>
                    </div>
                    <div class="modal-body">
                        <form id="changePasswordForm">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"> <!-- CSRF Token -->
                            <input type="hidden" name="userId" id="userId" value="{{ auth()->user()->id }}">
                            @method('PUT')
                            <!-- New Password -->
                            <div class="mb-3">
                                <label for="newPassword" class="form-label">New Password</label>
                                <div class="input-group">
                                    <input type="password" tabindex="1" class="form-control" id="newPassword"
                                        name="newPassword" placeholder="Enter new password" required minlength="6">
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="newPassword">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="mb-3">
                                <label for="confirmPassword" class="form-label">Confirm New Password</label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirmPassword"
                                        name="confirmPassword" placeholder="Confirm new password" required
                                        minlength="6" tabindex="2">
                                    <button class="btn btn-outline-secondary toggle-password" type="button"
                                        data-target="confirmPassword">
                                        <i class="bx bx-show"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Update Password</button>
                            </div>
                        </form>
                    </div>
                </div>
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
    @yield('scripts')
    <script>
        $(document).ready(function() {
            // checkPassword();

            // function checkPassword() {
            //     $.ajax({
            //         url: '/check-password',
            //         type: 'GET',
            //         success: function(response) {
            //             if (response) {
            //                 $("#changePasswordModal").modal('show');
            //             }
            //         }
            //     });
            // }

            $(".toggle-password").click(function() {
                let input = $("#" + $(this).data("target"));
                let icon = $(this).find("i");

                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                    icon.removeClass("bx-show").addClass("bx-hide");
                } else {
                    input.attr("type", "password");
                    icon.removeClass("bx-hide").addClass("bx-show");
                }
            });

            $("#changePasswordForm").submit(function(e) {
                e.preventDefault();

                let userId = $("#userId").val(); // Get the user ID dynamically
                let formData = $(this).serialize() +
                    "&_method=PUT";

                $.ajax({
                    url: `/users/${userId}`,
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (response.code === 200) {
                            toaster(response.message, "success");
                            setTimeout(() => $("#changePasswordModal").modal('hide'),
                                1500);
                        } else {
                            toaster(response.message, "error");
                        }
                    },
                    error: function(xhr) {
                        let errorMsg = xhr.responseJSON?.message ||
                            "Error updating user.";
                        toaster(errorMsg, "error");
                    }
                });
            });

        });
    </script>

    <script>
        function updateDateTime() {
            let now = new Date();
            let formattedDate = now.toLocaleDateString("en-US", {
                year: "numeric",
                month: "long",
                day: "numeric",
            });

            let formattedTime = now.toLocaleTimeString("en-US", {
                hour: "numeric",
                minute: "2-digit",
                hour12: true,
            }).split(" ");

            // Extract time parts
            let hours = formattedTime[0].split(":")[0];
            let minutes = formattedTime[0].split(":")[1];
            let ampm = formattedTime[1];

            // Cache elements
            const $currentDate = $("#currentDate");
            const $hours = $("#hours");
            const $minutes = $("#minutes");
            const $ampm = $("#ampm");

            // Update only if values have changed (reduces unnecessary DOM updates)
            if ($currentDate.text() !== formattedDate) $currentDate.text(formattedDate);
            if ($hours.text() !== hours) $hours.text(hours);
            if ($minutes.text() !== minutes) $minutes.text(minutes);
            if ($ampm.text() !== ampm) $ampm.text(ampm);

            requestAnimationFrame(updateDateTime);
        }

        $(document).ready(updateDateTime);
    </script>
    <script>
        
    </script>

</body>

</html>
