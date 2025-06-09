<!--
=========================================================
* Soft UI Dashboard 3 - v1.1.0
=========================================================

* Product Page: https://www.creative-tim.com/product/soft-ui-dashboard
* Copyright 2024 Creative Tim (https://www.creative-tim.com)
* Licensed under MIT (https://www.creative-tim.com/license)
* Coded by Creative Tim

=========================================================

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="apple-touch-icon" sizes="76x76" href="softTemplate/assets/img/LogoInternSync.png">
    <link rel="icon" type="image/png" href="softTemplate/assets/img/LogoInternSync.png">
    <title>
        InternSync
    </title>
    <!-- iziToast CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
    <!--     Fonts and icons     -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style"
        onload="this.onload=null;this.rel='stylesheet'">
    <noscript>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </noscript>

    <!-- CSS Files -->
    <link id="pagestyle" href="{{ asset('softTemplate/assets/css/soft-ui-dashboard.css?v=1.1.0') }}" rel="stylesheet" />
    {{-- DataTables --}}
    <link rel="stylesheet" href="{{ asset('softTemplate/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet"
        href="{{ asset('softTemplate/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('softTemplate/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('softTemplate/assets/img/LogoInternSync.png') }}">
    <link rel="icon" type="image/png" href="{{ asset('softTemplate/assets/img/LogoInternSync.png') }}">

    <style>
        .primary {
            background-color: #fafafa;
        }

        .bg-primary {
            background-color: #fafafa;
        }

        .btn-primary {
            background-color: #fafafa;
            color: #0f0f0f;
        }

        .bg-light {
            background-color: #fafafa;
        }

        /* --- Global DataTables Pagination Styling --- */

        /* Wrapper umum DataTables untuk pagination */
        .dataTables_wrapper .dataTables_paginate .pagination .page-item .page-link {
            background-color: #ffffff !important;
            /* Latar belakang putih untuk semua tombol */
            color: #007bff !important;
            /* Warna teks primary (biru bootstrap default) untuk nomor halaman */
            border: 1px solid #dee2e6 !important;
            /* Border abu-abu standar */
            box-shadow: none !important;
            /* Hapus shadow jika ada */
        }

        /* Tombol pagination saat hover (kecuali yang aktif) */
        .dataTables_wrapper .dataTables_paginate .pagination .page-item:not(.active) .page-link:hover {
            background-color: #f8f9fa !important;
            /* Latar belakang abu-abu sangat muda saat hover */
            color: #0056b3 !important;
            /* Warna teks primary lebih gelap saat hover untuk nomor halaman */
            border-color: #ced4da !important;
            /* Border sedikit lebih gelap saat hover */
        }

        /* Tombol pagination yang aktif */
        .dataTables_wrapper .dataTables_paginate .pagination .page-item.active .page-link {
            background-color: #007bff !important;
            /* Latar belakang primary untuk tombol aktif */
            color: #ffffff !important;
            /* Teks putih untuk tombol aktif (nomor halaman) */
            border-color: #007bff !important;
            /* Border primary untuk tombol aktif */
            font-weight: bold;
            /* Tebalkan nomor halaman aktif */
        }

        /* Tombol pagination yang aktif saat hover (opsional, bisa dibuat berbeda) */
        .dataTables_wrapper .dataTables_paginate .pagination .page-item.active .page-link:hover {
            background-color: #0056b3 !important;
            /* Latar belakang primary lebih gelap saat hover */
            color: #ffffff !important;
            border-color: #0056b3 !important;
        }

        /* Tombol pagination yang dinonaktifkan (disabled) */
        .dataTables_wrapper .dataTables_paginate .pagination .page-item.disabled .page-link {
            background-color: #f8f9fa !important;
            /* Latar belakang lebih terang untuk disabled */
            color: #6c757d !important;
            /* Warna teks abu-abu (muted) untuk nomor halaman */
            border-color: #e9ecef !important;
            /* Border lebih terang */
        }

        /* Styling untuk ikon di tombol pagination (Previous, Next, etc.) */
        .dataTables_wrapper .dataTables_paginate .pagination .page-item .page-link i {
            color: #343a40 !important;
            /* Warna ikon gelap standar */
        }

        .dataTables_wrapper .dataTables_paginate .pagination .page-item.disabled .page-link i {
            color: #6c757d !important;
            /* Warna ikon muted untuk disabled (tetap sama) */
        }

        .dataTables_wrapper .dataTables_paginate .pagination .page-item:not(.active) .page-link:hover i {
            color: #212529 !important;
            /* Warna ikon lebih gelap saat hover */
        }

        /* Ikon pada tombol aktif tetap putih agar kontras dengan latar belakang primary */
        .dataTables_wrapper .dataTables_paginate .pagination .page-item.active .page-link i,
        .dataTables_wrapper .dataTables_paginate .pagination .page-item.active .page-link:hover i {
            color: #ffffff !important;
            /* Warna ikon putih untuk tombol aktif */
        }
    </style>
    <!-- Nepcha Analytics (nepcha.com) -->
    <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    @stack('css')
</head>

<body class="g-sidenav-show  bg-gray-50">
    <aside
        class="sidenav navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl fixed-start ms-3 my-3 bg-white"
        id="sidenav-main" style="overflow-y: hidden;">
        @include('layouts.sidebar')
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <!-- Navbar -->
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl" id="navbarBlur"
            navbar-scroll="true">
            @include('layouts.nav')
        </nav>
        <!-- End Navbar -->
        {{-- Main Content --}}
        <div class="container-fluid py-2">
            @yield('content')
        </div>
        {{--
            <footer class="footer pt-3  ">
                @include('layouts.footer')
            </footer> --}}
        </div>
    </main>
    <!-- iziToast CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <!-- jQuery -->
    <script src="{{ asset('softTemplate/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('softTemplate/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!--   Core JS Files   -->
    <script src="{{ asset('softTemplate/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('softTemplate/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('softTemplate/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('softTemplate/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
    <script src="{{ asset('softTemplate/assets/js/plugins/chartjs.min.js') }}"></script>

    <!-- jQuery (required for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- iziToast CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

    {{-- DataTables --}}
    <script src="{{ asset('softTemplate/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('softTemplate/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('softTemplate/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('softTemplate/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('softTemplate/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('softTemplate/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('softTemplate/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('softTemplate/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('softTemplate/plugins/datatables-buttons/js/buttons.colvis.min.js') }}"></script>
    <script>
        var ctx = document.getElementById("chart-bars").getContext("2d");

        new Chart(ctx, {
            type: "bar",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                    label: "Sales",
                    tension: 0.4,
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false,
                    backgroundColor: "#fff",
                    data: [450, 200, 100, 220, 500, 100, 400, 230, 500],
                    maxBarThickness: 6
                }, ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                        },
                        ticks: {
                            suggestedMin: 0,
                            suggestedMax: 500,
                            beginAtZero: true,
                            padding: 15,
                            font: {
                                size: 14,
                                family: "Inter",
                                style: 'normal',
                                lineHeight: 2
                            },
                            color: "#fff"
                        },
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false
                        },
                        ticks: {
                            display: false
                        },
                    },
                },
            },
        });


        var ctx2 = document.getElementById("chart-line").getContext("2d");

        var gradientStroke1 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke1.addColorStop(1, 'rgba(203,12,159,0.2)');
        gradientStroke1.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke1.addColorStop(0, 'rgba(203,12,159,0)'); //purple colors

        var gradientStroke2 = ctx2.createLinearGradient(0, 230, 0, 50);

        gradientStroke2.addColorStop(1, 'rgba(20,23,39,0.2)');
        gradientStroke2.addColorStop(0.2, 'rgba(72,72,176,0.0)');
        gradientStroke2.addColorStop(0, 'rgba(20,23,39,0)'); //purple colors

        new Chart(ctx2, {
            type: "line",
            data: {
                labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                datasets: [{
                        label: "Mobile apps",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#cb0c9f",
                        borderWidth: 3,
                        backgroundColor: gradientStroke1,
                        fill: true,
                        data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                        maxBarThickness: 6

                    },
                    {
                        label: "Websites",
                        tension: 0.4,
                        borderWidth: 0,
                        pointRadius: 0,
                        borderColor: "#3A416F",
                        borderWidth: 3,
                        backgroundColor: gradientStroke2,
                        fill: true,
                        data: [30, 90, 40, 140, 290, 290, 340, 230, 400],
                        maxBarThickness: 6
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false,
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        grid: {
                            drawBorder: false,
                            display: true,
                            drawOnChartArea: true,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            padding: 10,
                            color: '#b2b9bf',
                            font: {
                                size: 11,
                                family: "Inter",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                    x: {
                        grid: {
                            drawBorder: false,
                            display: false,
                            drawOnChartArea: false,
                            drawTicks: false,
                            borderDash: [5, 5]
                        },
                        ticks: {
                            display: true,
                            color: '#b2b9bf',
                            padding: 20,
                            font: {
                                size: 11,
                                family: "Inter",
                                style: 'normal',
                                lineHeight: 2
                            },
                        }
                    },
                },
            },
        });
    </script>
    <!-- jQuery -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <!-- Bootstrap Bundle -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <!-- jQuery Validate -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Soft UI Dashboard script (wajib di bawah jQuery + Bootstrap) -->
    <script src="{{ asset('softTemplate/assets/js/soft-ui-dashboard.min.js?v=1.1.0') }}"></script>
    <!-- jQuery Validate -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Github buttons -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script>
        var win = navigator.platform.indexOf('Win') > -1;
        if (win && document.querySelector('#sidenav-scrollbar')) {
            var options = {
                damping: '0.5'
            }
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
        }
    </script>
    <!-- Soft UI Dashboard script (wajib di bawah jQuery + Bootstrap) -->
    <script src="{{ asset('softTemplate/assets/js/soft-ui-dashboard.min.js?v=1.1.0') }}"></script>

    @stack('js')
</body>

</html>
