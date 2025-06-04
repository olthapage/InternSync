{{-- login.blade.php --}}
@extends('layouts.authTemplate')

@section('title', 'Login')

@section('overlay-title', 'Selamat Datang Kembali')
@section('overlay-description', 'Akses akunmu untuk melanjutkan perjalanan magangmu. Pantau pengajuan, lacak progres, dan sambut peluang baru!

')

@section('form-title', 'Masuk ke akun Anda')

@section('form-content')
    <section>
        <div class="mb-3">
            <input type="email" id="email" class="form-control" placeholder="Email" aria-label="Email"
                aria-describedby="email-addon">
        </div>
        <div class="input-group mb-3 align-items-stretch">
            <input type="password" id="password" name="password" class="form-control" placeholder="Password">
            <small id="error-password" class="error-text text-danger"></small>
        </div>
        <label>Login Sebagai</label>
        <div class="mb-3">
            <select id="role" class="form-control" name="role" required>
                <option value="mahasiswa">Mahasiswa</option>
                <option value="dosen">Dosen</option>
                <option value="web">Admin</option>
                <option value="industri">Industri</option>
            </select>
        </div>

        <div class="row">
            <div class="col-8">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" id="rememberMe" checked="">
                    <label class="form-check-label" for="rememberMe">Remember me</label>
                </div>
            </div>
            <div class="col-4">
                <button type="submit" class="btn btn-dark rounded-pill" id="btnLogin">Sign In</button>
            </div>
        </div>
        <div class="d-flex justify-content-between mt-3">
            <a href="{{ route('signup') }}" class="text-decoration-none hover-blue">Belum punya akun?</a>
            <a href="{{ url('/company') }}" class="text-decoration-none hover-blue">Company</a>
        </div>
    </section>
@endsection

@section('bottom-link')
    <a href="{{ route('landing') }}" class="mt-3 d-block text-center text-decoration-none hover-blue">Kembali ke Beranda</a>
@endsection

@section('scripts')
    <script src="{{ asset('softTemplate/assets/js/core/popper.min.js') }}"></script>
    <script src="{{ asset('softTemplate/assets/js/core/bootstrap.min.js') }}"></script>
    <script src="{{ asset('softTemplate/assets/js/plugins/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('softTemplate/assets/js/plugins/smooth-scrollbar.min.js') }}"></script>
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
    <script src="{{ asset('softTemplate/assets/js/soft-ui-dashboard.min.js') }}"></script>

    <!-- jQuery (required for Toastr) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- iziToast CSS & JS -->
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('btnLogin').addEventListener('click', function() {
                let email = document.getElementById('email').value;
                let password = document.getElementById('password').value;
                let role = document.getElementById('role').value;
                let token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

                fetch("{{ route('login') }}", {
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
@endsection
