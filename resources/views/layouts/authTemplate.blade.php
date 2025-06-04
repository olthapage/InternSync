{{-- auth-template.blade.php --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Authentication')</title>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    {{-- Montserrat Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <!-- iziToast CSS & JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
    <!-- Fonts and icons -->
    <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
    <!-- Nucleo Icons -->
    <link href="softTemplate/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="softTemplate/assets/css/nucleo-svg.css" rel="stylesheet" />
    <!-- Font Awesome Icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
    <!-- CSS Files -->
    <link id="pagestyle" href="softTemplate/assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
    <link rel="apple-touch-icon" sizes="76x76" href="softTemplate/assets/img/LogoInternSync.png">
    <link rel="icon" type="image/png" href="softTemplate/assets/img/LogoInternSync.png">
    <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body {
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
            font-family: "Montserrat", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400
        }

        .img-overlay-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .img-overlay-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .overlay-text {
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            padding: 2rem 3rem;
            z-index: 10;
            color: white;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8) 0%, rgba(0, 0, 0, 0.4) 60%, transparent 100%);
        }

        .big-text {
            font-size: 2.75rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            line-height: 1.2;
        }

        .small-text {
            font-size: 1.1rem;
            font-weight: 300;
            opacity: 0.9;
            max-width: 80%;
        }

        .auth-card {
            border-radius: 10px;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.05);
        }

        .container-fluid,
        .row {
            margin: 0;
            padding: 0;
        }

        .hover-blue:hover {
            color: #0d6efd !important;
        }

         .slideshow-container {
            position: relative;
            width: 100%;
            height: 100%;
            overflow: hidden;
        }

        .slide {
            position: absolute;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 1.5s ease-in-out;
        }

        .slide.active {
            opacity: 1;
            z-index: 1;
        }
        @yield('custom-styles')
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="row min-vh-100">
            <!-- Left Side: Image -->
            <div class="col-md-6 d-none d-md-block p-0">
                <div class="img-overlay-container">
                    <div class="slideshow-container">
                        <img class="slide fade" src="{{ asset('images/slide1.jpg') }}" alt="Slide 1">
                        <img class="slide fade" src="{{ asset('images/slide2.jpg') }}" alt="Slide 2">
                        <img class="slide fade" src="{{ asset('images/slide3.JPG') }}" alt="Slide 3">
                        <img class="slide fade" src="{{ asset('images/slide4.jpg') }}" alt="Slide 4">
                        <img class="slide fade" src="{{ asset('images/slide5.JPG') }}" alt="Slide 5">
                        <img class="slide fade" src="{{ asset('images/slide6.jpg') }}" alt="Slide 6">
                    </div>
                    <div class="overlay-text">
                        <div class="big-text libre">@yield('overlay-title', 'Welcome')</div>
                        <div class="small-text">@yield('overlay-description', 'Please authenticate to access the system.')</div>
                    </div>
                </div>
            </div>
            <!-- Right Side: Form -->
            <div class="col-md-6 d-flex align-items-center justify-content-center bg-white">
                <div class="w-100" style="max-width: 400px;">
                    <div class="text-center mb-4">
                        <a href="{{ url('/') }}" class="img-fluid">
                            <img src="{{ asset('softTemplate/assets/img/LogoInternSync.png') }}" alt="Logo"
                                class="img-fluid" style="width: 40px; height: auto;">
                        </a>
                    </div>

                    <div class="card auth-card border-0">
                        <div class="card-body p-4">
                            <h4 class="text-center mb-4">@yield('form-title', 'Sign in to your account')</h4>

                            @yield('form-content')

                        </div>
                    </div>

                    <div class="text-center mt-3">
                        @yield('bottom-link')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- jQuery Validation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

    <script>

    let slides = document.querySelectorAll('.slide');
    let currentSlide = 0;

    function showSlide(index) {
        slides.forEach((slide, i) => {
            slide.classList.remove('active');
            if (i === index) {
                slide.classList.add('active');
            }
        });
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    showSlide(currentSlide);
    setInterval(nextSlide, 6000);
    </script>

    @yield('scripts')
</body>

</html>
