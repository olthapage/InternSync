@extends('layouts.authTemplate')

@section('title', 'Register Pengguna - InternSync')
@section('overlay-title', 'Selamat Datang di InternSync')
@section('overlay-description', 'Buat akun baru untuk memulai perjalanan Anda dalam mencari tempat magang terbaik.')

@section('form-title', 'Buat Akun Baru')

@section('form-content')
<form id="form-register" method="POST" action="{{ route('post.signup') }}">
    @csrf

    <div class="mb-3">
        <label for="username" class="form-label">NIM / NIDN <span class="text-danger">*</span></label>
        <input type="text" name="username" id="username" class="form-control form-control-lg" placeholder="Masukkan NIM atau NIDN" value="{{ old('username') }}">
        <small id="error-username" class="error-text text-danger d-block mt-1"></small>
    </div>

    <div class="mb-3">
        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
        <input type="email" name="email" id="email" class="form-control form-control-lg" placeholder="Masukkan Email Anda" value="{{ old('email') }}">
        <small id="error-email" class="error-text text-danger d-block mt-1"></small>
    </div>

    <div class="mb-3">
        <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control form-control-lg" placeholder="Masukkan Nama Lengkap Anda" value="{{ old('nama_lengkap') }}">
        <small id="error-nama_lengkap" class="error-text text-danger d-block mt-1"></small>
    </div>

    <div class="mb-3">
        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
        <input type="password" name="password" id="password" class="form-control form-control-lg" placeholder="Masukkan Password">
        <small id="error-password" class="error-text text-danger d-block mt-1"></small>
    </div>

    <div class="mb-3">
        <label for="password_confirmation" class="form-label">Konfirmasi Password <span class="text-danger">*</span></label>
        <input type="password" name="password_confirmation" id="password_confirmation" class="form-control form-control-lg" placeholder="Ulangi Password">
        <small id="error-password_confirmation" class="error-text text-danger d-block mt-1"></small>
    </div>

    <div class="row align-items-center mt-4">
        <div class="col-7">
            <a href="{{ url('login') }}" class="link-text text-decoration-none hover-blue">Sudah punya akun? Login</a>
        </div>
        <div class="col-5 text-end">
            <button type="submit" class="btn btn-dark rounded-pill">Sign up</button>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    $(document).ready(function () {
    $('#form-register').validate({
        rules: {
            username: {
                required: true,
                minlength: 5,
                maxlength: 20
            },
            email: {
                required: true,
                email: true
            },
            nama_lengkap: {
                required: true,
                minlength: 3
            },
            password: {
                required: true,
                minlength: 6
            },
            password_confirmation: {
                required: true,
                equalTo: "#password"
            }
        },
        messages: {
            username: {
                required: "NIM atau NIDN wajib diisi.",
                minlength: "Minimal 5 karakter.",
                maxlength: "Maksimal 20 karakter."
            },
            email: {
                required: "Email tidak boleh kosong.",
                email: "Format email tidak valid."
            },
            nama_lengkap: {
                required: "Nama lengkap tidak boleh kosong.",
                minlength: "Minimal 3 karakter."
            },
            password: {
                required: "Password tidak boleh kosong.",
                minlength: "Minimal 6 karakter."
            },
            password_confirmation: {
                required: "Ulangi password Anda.",
                equalTo: "Password tidak cocok."
            }
        },
        errorElement: 'small',
        errorClass: 'error-text text-danger d-block',
        highlight: function (el) {
            $(el).addClass('is-invalid');
        },
        unhighlight: function (el) {
            $(el).removeClass('is-invalid');
        },
        submitHandler: function (form) {
            // Submit via AJAX
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: $(form).serialize(),
                success: function (response) {
                    if (response.status && response.redirect) {
                        window.location.href = response.redirect;
                    } else {
                        // tangani response lain, misal error pesan
                        alert(response.message || 'Terjadi kesalahan.');
                    }
                },
                error: function (xhr) {
                    // Tangani error validasi dari backend
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        // Reset dulu error lama
                        $('.error-text').remove();
                        $('.is-invalid').removeClass('is-invalid');

                        $.each(errors, function (key, messages) {
                            let input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            if (input.next('small.error-text').length === 0) {
                                input.after('<small class="error-text text-danger d-block">' + messages[0] + '</small>');
                            }
                        });
                    } else {
                        alert('Terjadi kesalahan server.');
                    }
                }
            });
            return false; // jangan submit form normal
        }
    });
});
</script>
@endsection
