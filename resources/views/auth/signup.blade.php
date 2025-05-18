{{-- register.blade.php --}}
@extends('layouts.authTemplate')

@section('title', 'Register Pengguna - Soft UI Style')

@section('overlay-title', 'Selamat Datang')
@section('overlay-description', 'Buat akun baru untuk memulai perjalanan Anda. Bergabunglah dengan kami dan nikmati pengalaman mencatri tempat magang yang lebih baik!')

{{-- Form title --}}

@section('form-title', 'Register with')

@section('form-content')
    <form action="{{ url('register') }}" method="POST" id="form-register">
        @csrf
        <div class="mb-3">
            <select id="role" name="role" class="form-control form-control-lg">
                <option value="">Pilih Role</option>
                <option value="dosen">Dosen</option>
                <option value="mahasiswa">Mahasiswa</option>
            </select>
        </div>
        <small id="error-role" class="error-text text-danger d-block mb-2"></small>

        <div class="mb-3" id="nidn-group" style="display:none;">
            <input type="text" name="nidn" id="nidn" class="form-control form-control-lg" placeholder="NIDN">
        </div>
        <small id="error-nidn" class="error-text text-danger d-block mb-2"></small>

        <div class="mb-3" id="nim-group" style="display:none;">
            <input type="text" name="nim" id="nim" class="form-control form-control-lg" placeholder="NIM">
        </div>
        <small id="error-nim" class="error-text text-danger d-block mb-2"></small>

        <div class="mb-3">
            <input type="text" id="nama_lengkap" name="nama_lengkap" class="form-control form-control-lg"
                placeholder="Nama Lengkap">
        </div>
        <small id="error-nama_lengkap" class="error-text text-danger d-block mb-2"></small>

        <div class="mb-3">
            <input type="email" id="email" name="email" class="form-control form-control-lg"
                placeholder="Email">
        </div>
        <small id="error-email" class="error-text text-danger d-block mb-2"></small>

        <div class="mb-3">
            <input type="password" id="password" name="password" class="form-control form-control-lg"
                placeholder="Password">
        </div>
        <small id="error-password" class="error-text text-danger d-block mb-2"></small>

        <div class="mb-3">
            <input type="password" id="password_confirmation" name="password_confirmation"
                class="form-control form-control-lg" placeholder="Confirm Password">
        </div>
        <small id="error-password_confirmation" class="error-text text-danger d-block mb-2"></small>

        <div class="form-check form-check-info text-left mb-3">
            <input class="form-check-input" type="checkbox" name="terms" id="terms" value="agree">
            <label class="form-check-label" for="terms">
                Saya setuju dengan <a href="#" class="text-dark font-weight-bolder">Syarat dan Ketentuan</a>
            </label>
        </div>
        <small id="error-terms" class="error-text text-danger d-block mb-2"></small>


        <div class="row align-items-center mt-4">
            <div class="col-7">
                <a href="{{ url('login') }}" class="link-text text-decoration-none hover-blue">Sudah punya akun?</a>
            </div>
            <div class="col-5 text-end">
                <button type="submit" class="btn btn-dark rounded-pill">Sign up</button>
            </div>
        </div>
    </form>
@endsection

@section('bottom-link')
    <a href="{{ route('landing') }}" class="link-text text-decoration-none hover-blue">Kembali ke home</a>
@endsection

@section('scripts')
    {{-- Pastikan jQuery dan jquery-validate sudah di-load di authTemplate --}}
    {{-- Jika ingin menggunakan iziToast, uncomment dan pastikan library-nya ada --}}
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css"> --}}
    {{-- <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script> --}}

    <style>
        /* Gaya untuk pesan error dari jquery-validate */
        .error-text,
        label.error {
            /* label.error untuk jquery-validate default, .error-text untuk custom small tags */
            color: red;
            font-size: 0.8rem;
            /* Disesuaikan agar tidak terlalu besar */
            /* margin-top: 0.25rem; */
            display: block;
        }

        .form-control.error {
            /* Untuk highlight field yang error */
            border-color: red;
        }

        .input-group .form-control.error {
            /* Fix untuk input-group */
            z-index: 2 !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            // Tampilkan atau sembunyikan field NIDN/NIM berdasarkan role
            $('#role').change(function() {
                var role = $(this).val();
                $('.error-text').text(''); // Bersihkan error sebelumnya

                if (role === 'dosen') {
                    $('#nidn-group').show();
                    $('#nim-group').hide();
                    $('#nim').val(''); // Kosongkan field nim jika tidak dipilih
                } else if (role === 'mahasiswa') {
                    $('#nidn-group').hide();
                    $('#nidn').val(''); // Kosongkan field nidn jika tidak dipilih
                    $('#nim-group').show();
                } else {
                    $('#nidn-group').hide();
                    $('#nim-group').hide();
                    $('#nidn').val('');
                    $('#nim').val('');
                }
            });
            // Trigger pertama kali saat halaman dimuat untuk memastikan state awal benar
            $('#role').trigger('change');


            $("#form-register").validate({
                rules: {
                    role: {
                        required: true
                    },
                    nidn: {
                        required: function() {
                            return $('#role').val() === 'dosen';
                        },
                        digits: true,
                        minlength: 10,
                        maxlength: 10
                    },
                    nim: {
                        required: function() {
                            return $('#role').val() === 'mahasiswa';
                        },
                        digits: true,
                        minlength: 8,
                        maxlength: 10 // Sesuaikan jika NIM bisa lebih dari 8
                    },
                    nama_lengkap: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    password: {
                        required: true,
                        minlength: 6,
                        maxlength: 20
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#password"
                    },
                    terms: {
                        required: true
                    }
                },
                messages: {
                    role: {
                        required: "Silakan pilih role Anda."
                    },
                    nidn: {
                        required: "NIDN wajib diisi untuk dosen.",
                        digits: "NIDN harus berupa angka.",
                        minlength: "NIDN harus terdiri dari 10 digit.",
                        maxlength: "NIDN harus terdiri dari 10 digit."
                    },
                    nim: {
                        required: "NIM wajib diisi untuk mahasiswa.",
                        digits: "NIM harus berupa angka.",
                        minlength: "NIM minimal 8 digit.",
                        maxlength: "NIM maksimal 10 digit." // Sesuaikan
                    },
                    nama_lengkap: {
                        required: "Nama lengkap tidak boleh kosong.",
                        minlength: "Nama lengkap minimal 3 karakter."
                    },
                    email: {
                        required: "Email tidak boleh kosong.",
                        email: "Format email tidak valid."
                    },
                    password: {
                        required: "Password tidak boleh kosong.",
                        minlength: "Password minimal 6 karakter.",
                        maxlength: "Password maksimal 20 karakter."
                    },
                    password_confirmation: {
                        required: "Konfirmasi password tidak boleh kosong.",
                        equalTo: "Konfirmasi password tidak cocok."
                    },
                    terms: {
                        required: "Anda harus menyetujui Syarat dan Ketentuan."
                    }
                },
                // Menggunakan errorPlacement bawaan jquery-validate atau custom
                errorPlacement: function(error, element) {
                    // Untuk checkbox, letakkan pesan error setelah labelnya
                    if (element.attr("name") == "terms") {
                        error.insertAfter(element.next("label"));
                    } else {
                        // Untuk input lain, letakkan di <small> tag yang sudah disiapkan
                        var errorContainerId = '#error-' + element.attr('name');
                        $(errorContainerId).html(error.text()); // Hanya teks error, tanpa tag label
                    }
                },
                // Fungsi highlight dan unhighlight bisa dikosongkan jika sudah ditangani CSS :invalid atau class .error
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('error').removeClass(validClass);
                    // Juga tambahkan class error ke container error textnya jika ada
                    $('#error-' + $(element).attr('name')).addClass('error');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('error').addClass(validClass);
                    $('#error-' + $(element).attr('name')).removeClass('error').text(
                    ''); // Bersihkan teks error
                },

                submitHandler: function(form) {
                    $('.error-text').text(
                    ''); // Bersihkan semua pesan error field spesifik sebelum submit
                    var formData = new FormData(form); // Menggunakan FormData seperti di Soft UI

                    $.ajax({
                        url: form.action, // Diambil dari action form: {{ url('register') }}
                        method: form.method, // Diambil dari method form: POST
                        data: formData,
                        processData: false, // Penting untuk FormData
                        contentType: false, // Penting untuk FormData
                        success: function(response) {
                            if (response.status) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registrasi Berhasil',
                                    text: response.message, // Pesan dari backend
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(function() {
                                    if (response.redirect) {
                                        window.location.href = response
                                        .redirect; // Redirect dari backend
                                    } else {
                                        window.location.href =
                                        "{{ url('login') }}"; // Fallback redirect
                                    }
                                });
                            } else {
                                // Menampilkan error umum
                                let errorMessage = response.message ||
                                    'Terjadi kesalahan saat registrasi.';

                                // Menampilkan error field spesifik jika ada dari backend (response.msgField atau response.errors)
                                if (response.errors) { // Laravel biasanya mengirim 'errors'
                                    let errorFieldsText = "";
                                    $.each(response.errors, function(key, value) {
                                        $('#error-' + key).text(value[
                                        0]); // Tampilkan error di bawah field
                                        errorFieldsText += value[0] +
                                        "<br>"; // Kumpulkan untuk Swal
                                    });
                                    if (errorFieldsText) errorMessage += "<br><br>" +
                                        errorFieldsText;
                                } else if (response.msgField) { // Jika formatnya msgField
                                    let errorFieldsText = "";
                                    $.each(response.msgField, function(prefix, val) {
                                        $('#error-' + prefix).text(val[0]);
                                        errorFieldsText += val[0] + "<br>";
                                    });
                                    if (errorFieldsText) errorMessage += "<br><br>" +
                                        errorFieldsText;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Registrasi Gagal',
                                    html: errorMessage // Menampilkan pesan error dari backend
                                });
                            }
                        },
                        error: function(xhr) {
                            // console.error(xhr.responseText);
                            let generalErrorMessage =
                                'Terjadi kesalahan koneksi atau server. Silakan coba lagi.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                generalErrorMessage = xhr.responseJSON.message;
                            }
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                let errorFieldsText = "";
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                    $('#error-' + key).text(value[0]);
                                    errorFieldsText += value[0] + "<br>";
                                });
                                if (errorFieldsText) generalErrorMessage += "<br><br>" +
                                    errorFieldsText;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                html: generalErrorMessage,
                                position: 'top-center' // Mirip iziToast
                            });
                        }
                    });
                    return false; // Mencegah submit form standar
                }
            });
        });
    </script>
@endsection
