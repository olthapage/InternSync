<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('softTemplate/assets/img/LogoInternSync.png') }}">
  <link rel="icon" type="image/png" href="{{ asset('softTemplate/assets/img/LogoInternSync.png') }}">
  <title>
    InternSync
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,800" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/soft-ui-dashboard/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- Font Awesome Icons -->
  <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>
  <!-- CSS Files -->
  <link id="pagestyle" href="softTemplate/assets/css/soft-ui-dashboard.css?v=1.1.0" rel="stylesheet" />
  <!-- Nepcha Analytics (nepcha.com) -->
  <!-- Nepcha is a easy-to-use web analytics. No cookies and fully compliant with GDPR, CCPA and PECR. -->
  <script defer data-site="YOUR_DOMAIN_HERE" src="https://api.nepcha.com/js/nepcha-analytics.js"></script>
   <!-- iziToast CSS & JS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
</head>

<body class="">
  <div class="container position-sticky z-index-sticky top-0">
    <div class="row">
      <div class="col-12">
        <!-- Navbar -->
        <nav class="navbar navbar-expand-lg blur blur-rounded top-0 z-index-3 shadow position-absolute my-3 py-2 start-0 end-0 mx-4">
          <div class="container-fluid pe-0">
            <a class="navbar-brand font-weight-bolder ms-lg-0 ms-3 " href="../pages/dashboard.html">
              InternSync
            </a>
            <button class="navbar-toggler shadow-none ms-2" type="button" data-bs-toggle="collapse" data-bs-target="#navigation" aria-controls="navigation" aria-expanded="false" aria-label="Toggle navigation">
              <span class="navbar-toggler-icon mt-2">
                <span class="navbar-toggler-bar bar1"></span>
                <span class="navbar-toggler-bar bar2"></span>
                <span class="navbar-toggler-bar bar3"></span>
              </span>
            </button>
            <div class="collapse navbar-collapse" id="navigation">
              <ul class="navbar-nav mx-auto ms-xl-auto me-xl-7">
                <li class="nav-item">
                  <a class="nav-link d-flex align-items-center me-2 active" aria-current="page" href="{{ url('/#hero') }}">
                    <i class="fa fa-chart-pie opacity-6 text-dark me-1"></i>
                    Beranda
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{ url('/#about') }}">
                    <i class="fa fa-user opacity-6 text-dark me-1"></i>
                    Tentang kami
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{ url('/#features') }}">
                    <i class="fas fa-user-circle opacity-6 text-dark me-1"></i>
                    Fitur
                  </a>
                </li>
                <li class="nav-item">
                  <a class="nav-link me-2" href="{{ url('/#testimonials') }}">
                    <i class="fas fa-key opacity-6 text-dark me-1"></i>
                    Testimoni
                  </a>
                </li>
              </ul>
              <li class="nav-item d-flex align-items-center">
                <a class="btn btn-round btn-sm mb-0 btn-outline-primary me-2" target="_blank" href="https://www.creative-tim.com/builder?ref=navbar-soft-ui-dashboard">Online Builder</a>
              </li>
              <ul class="navbar-nav d-lg-block d-none">
                <li class="nav-item">
                  <a href="https://www.creative-tim.com/product/soft-ui-dashboard" class="btn btn-sm btn-round mb-0 me-1 bg-gradient-dark">Free download</a>
                </li>
              </ul>
            </div>
          </div>
        </nav>
        <!-- End Navbar -->
      </div>
    </div>
  </div>
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-75">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-6 d-flex flex-column mx-auto">
              <div class="card card-plain mt-8">
                <div class="card-header pb-0 text-left bg-transparent">
                  <h3 class="font-weight-bolder text-info text-gradient">Welcome back</h3>
                  <p class="mb-0">Enter your email and password to sign in</p>
                </div>
                <div class="card-body">
                  <form role="form">
                    <label>Email</label>
                    <div class="mb-3">
                      <input type="email" id="email" class="form-control" placeholder="Email" aria-label="Email" aria-describedby="email-addon">
                    </div>
                    <label>Password</label>
                    <div class="mb-3">
                      <input type="password" id="password" class="form-control" placeholder="Password" aria-label="Password" aria-describedby="password-addon">
                    </div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                      <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <div class="text-center">
                      <button type="button" class="btn bg-gradient-info w-100 mt-4 mb-0" id="btnLogin">Sign in</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    Need help for your account?
                    <a href="https://api.whatsapp.com/send/?phone=628123456789" target="_blank" class="text-info text-gradient font-weight-bold">Get Help</a>
                  </p>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="oblique position-absolute top-0 h-100 d-md-block d-none me-n8">
                <div class="oblique-image bg-cover position-absolute fixed-top ms-auto h-100 z-index-0 ms-n6" style="background-image:url('softTemplate/assets/img/curved-images/curved6.jpg')"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <!--   Core JS Files   -->
  <script src="softTemplate/assets/js/core/popper.min.js"></script>
  <script src="softTemplate/assets/js/core/bootstrap.min.js"></script>
  <script src="softTemplate/assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="softTemplate/assets/js/plugins/smooth-scrollbar.min.js"></script>
  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <!-- Github buttons -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>
  <!-- Control Center for Soft Dashboard: parallax effects, scripts for the example pages etc -->
  <script src="softTemplate/assets/js/soft-ui-dashboard.min.js?v=1.1.0"></script>

  <!-- jQuery (required for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- iziToast CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('btnLogin').addEventListener('click', function() {
                let email = document.getElementById('email').value;
                let password = document.getElementById('password').value;
                let role = 'industri';
                let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch("{{ route('companylogin') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": token,
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            email: email,
                            password: password,
                            role: role
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            iziToast.success({
                                title: 'Sukses',
                                message: data.message,
                                position: 'topCenter'
                            });
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 2000);
                        } else {
                            iziToast.error({
                                title: 'Gagal',
                                message: data.message,
                                position: 'topCenter'
                            });
                        }
                    })
                    .catch(error => {
                        console.error("Login error:", error);
                        iziToast.error({
                            title: 'Error',
                            message: 'Terjadi kesalahan pada server.',
                            position: 'topCenter'
                        });
                    });
            });
        });
    </script>
</body>

</html>
