{{-- register.blade.php --}}
@extends('layouts.authTemplate')

@section('title', 'Register Pengguna - Soft UI Style')

@section('overlay-title', 'Selamat Datang')
@section('overlay-description', 'Buat akun baru untuk memulai perjalanan Anda. Bergabunglah dengan kami dan nikmati
    pengalaman mencatri tempat magang yang lebih baik!')

    {{-- Form title --}}

@section('form-title', 'Register with')

@section('form-content')
    {{-- MODIFIED: Added action and removed onsubmit --}}
    <form role="form text-left" id="form-register" method="POST" action="{{ route('post.signup') }}">
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
            <input type="email" id="email" name="email" class="form-control form-control-lg" placeholder="Email">
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
        {{-- Error for terms will be displayed by errorPlacement logic below, or can use a dedicated small tag if preferred --}}
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

    <style>
        .error-text,
        label.error {
            color: red;
            font-size: 0.8rem;
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
                // Clear values and errors for conditional fields
                $('#nidn').val('').removeClass('error');
                $('#error-nidn').text('');
                $('#nim').val('').removeClass('error');
                $('#error-nim').text('');


                if (role === 'dosen') {
                    $('#nidn-group').show();
                    $('#nim-group').hide();
                } else if (role === 'mahasiswa') {
                    $('#nidn-group').hide();
                    $('#nim-group').show();
                } else {
                    $('#nidn-group').hide();
                    $('#nim-group').hide();
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
                        maxlength: 10 // Sesuai deskripsi awal, backend akan memvalidasi lebih ketat jika perlu
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
                        maxlength: "NIM maksimal 10 digit."
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
                errorPlacement: function(error, element) {
                    var errorContainerId = '#error-' + element.attr('name');
                    if (element.attr("name") == "terms") {
                        // For checkbox, place error in its dedicated small tag or after the label if no small tag
                        $(errorContainerId).html(error.text()); // Place in <small id="error-terms">
                    } else {
                        $(errorContainerId).html(error.text());
                    }
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('error').removeClass(validClass);
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('error').addClass(validClass);
                    $('#error-' + $(element).attr('name')).removeClass('error').text('');
                },

                submitHandler: function(form) {
                    $('.error-text').text(''); // Clear previous errors displayed in <small> tags
                    var formData = new FormData(form);

                    $.ajax({
                        url: $(form).attr('action'), // Use form's action attribute
                        method: $(form).attr('method'), // Use form's method attribute
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            if (response.status) {
                                // iziToast is optional, ensure it's loaded if you use it
                                if (typeof iziToast !== 'undefined') {
                                    iziToast.success({
                                        title: 'Berhasil',
                                        message: response.message || 'Registrasi berhasil.',
                                        position: 'topCenter',
                                        timeout: 2500,
                                        onClosed: function() {
                                            if (response.redirect) {
                                                window.location.href = response.redirect;
                                            } else {
                                                window.location.href = "{{ url('login') }}"; // Default redirect
                                            }
                                        }
                                    });
                                } else {
                                    alert(response.message || 'Registrasi berhasil.');
                                    if (response.redirect) {
                                        window.location.href = response.redirect;
                                    } else {
                                        window.location.href = "{{ url('login') }}";
                                    }
                                }
                            } else {
                                let generalErrorMessage = response.message || 'Terjadi kesalahan saat registrasi.';
                                let errorFieldsText = "";

                                if (response.errors) { // Check for Laravel validation errors object
                                    $.each(response.errors, function(key, value) {
                                        $('#error-' + key).text(value[0]); // Display first error for the field
                                        // errorFieldsText += value[0] + "<br>"; // Accumulate for general message if needed
                                    });
                                    // If specific field errors are shown, a general toast might be less necessary or just simpler
                                    generalErrorMessage = "Silakan periksa kembali data yang Anda masukkan.";
                                } else if (response.msgField) { // Fallback for other error structures
                                     $.each(response.msgField, function(prefix, val) {
                                         $('#error-' + prefix).text(val[0]);
                                        // errorFieldsText += val[0] + "<br>";
                                     });
                                     generalErrorMessage = "Silakan periksa kembali data yang Anda masukkan.";
                                }

                                if (typeof iziToast !== 'undefined') {
                                    iziToast.error({
                                        title: 'Gagal',
                                        message: generalErrorMessage, // Potentially with errorFieldsText appended
                                        position: 'topCenter',
                                        timeout: 5000
                                    });
                                } else {
                                    alert(generalErrorMessage);
                                }
                            }
                        },
                        error: function(xhr) {
                            // Handle server errors (500, 403, etc.) or network issues
                            let errorMessage = 'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function(key, value) {
                                     $('#error-' + key).text(value[0]);
                                });
                                errorMessage = "Data yang dimasukkan tidak valid. Periksa kembali isian Anda.";
                            }

                            if (typeof iziToast !== 'undefined') {
                                iziToast.error({
                                    title: 'Error',
                                    message: errorMessage,
                                    position: 'topCenter',
                                    timeout: 5000
                                });
                            } else {
                                alert(errorMessage);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endsection
