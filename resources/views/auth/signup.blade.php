{{-- signup.blade.php --}}
@extends('layouts.authTemplate')

@section('title', 'Register Pengguna - InternSync') {{-- Mengganti title --}}

@section('overlay-title', 'Selamat Datang di InternSync') {{-- Mengganti title --}}
@section('overlay-description',
    'Buat akun baru untuk memulai perjalanan Anda. Bergabunglah dengan kami dan nikmati
    pengalaman mencari tempat magang yang lebih baik!')

@section('form-title', 'Buat Akun Baru') {{-- Mengganti title form --}}

@section('form-content')
    <form role="form text-left" id="form-register" method="POST" action="{{ route('post.signup') }}">
        @csrf
        <div class="mb-3">
            <select id="role" name="role" class="form-control form-control-lg @error('role') is-invalid @enderror">
                <option value="">-- Pilih Role Utama --</option>
                <option value="dosen" {{ old('role') == 'dosen' ? 'selected' : '' }}>Dosen</option>
                <option value="mahasiswa" {{ old('role') == 'mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
            </select>
            {{-- Pesan error untuk role utama akan ditangani oleh jQuery Validate di bawah --}}
            <small id="error-role" class="error-text text-danger d-block mt-1"></small>
        </div>


        {{-- TAMBAHAN: Dropdown untuk Role Dosen (DPA atau Pembimbing) --}}
        <div class="mb-3" id="role-dosen-group" style="display:none;">
            <label for="role_dosen_signup" class="form-label">Peran Dosen <span class="text-danger">*</span></label>
            <select id="role_dosen_signup" name="role_dosen_signup"
                class="form-control form-control-lg @error('role_dosen_signup') is-invalid @enderror">
                <option value="">-- Pilih Peran Dosen --</option>
                <option value="dpa" {{ old('role_dosen_signup') == 'dpa' ? 'selected' : '' }}>DPA (Dosen Penasehat
                    Akademik)</option>
                <option value="pembimbing" {{ old('role_dosen_signup') == 'pembimbing' ? 'selected' : '' }}>Dosen Pembimbing
                </option>
            </select>
            <small id="error-role_dosen_signup" class="error-text text-danger d-block mt-1"></small>
        </div>
        {{-- AKHIR TAMBAHAN --}}

        <div class="mb-3" id="nidn-group" style="display:none;">
            <label for="nidn" class="form-label">NIDN/NIP <span class="text-danger">*</span></label>
            <input type="text" name="nidn" id="nidn"
                class="form-control form-control-lg @error('nidn') is-invalid @enderror" placeholder="NIDN/NIP Dosen"
                value="{{ old('nidn') }}">
            <small id="error-nidn" class="error-text text-danger d-block mt-1"></small>
        </div>

        <div class="mb-3" id="nim-group" style="display:none;">
            <label for="nim" class="form-label">NIM <span class="text-danger">*</span></label>
            <input type="text" name="nim" id="nim"
                class="form-control form-control-lg @error('nim') is-invalid @enderror" placeholder="NIM Mahasiswa"
                value="{{ old('nim') }}">
            <small id="error-nim" class="error-text text-danger d-block mt-1"></small>
        </div>

        <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
            <input type="text" id="nama_lengkap" name="nama_lengkap"
                class="form-control form-control-lg @error('nama_lengkap') is-invalid @enderror"
                placeholder="Masukkan Nama Lengkap Anda" value="{{ old('nama_lengkap') }}">
            <small id="error-nama_lengkap" class="error-text text-danger d-block mt-1"></small>
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email"
                class="form-control form-control-lg @error('email') is-invalid @enderror" placeholder="Masukkan Email Anda"
                value="{{ old('email') }}">
            <small id="error-email" class="error-text text-danger d-block mt-1"></small>
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
            <input type="password" id="password" name="password"
                class="form-control form-control-lg @error('password') is-invalid @enderror"
                placeholder="Buat Password Anda">
            <small id="error-password" class="error-text text-danger d-block mt-1"></small>
        </div>

        <div class="mb-3">
            <label for="password_confirmation" class="form-label">Konfirmasi Password <span
                    class="text-danger">*</span></label>
            <input type="password" id="password_confirmation" name="password_confirmation"
                class="form-control form-control-lg" placeholder="Ulangi Password Anda">
            {{-- Error untuk password_confirmation akan ditangani oleh rule equalTo --}}
            <small id="error-password_confirmation" class="error-text text-danger d-block mt-1"></small>
        </div>

        <div class="form-check form-check-info text-left mb-3">
            <input class="form-check-input @error('terms') is-invalid @enderror" type="checkbox" name="terms"
                id="terms" value="agree" {{ old('terms') ? 'checked' : '' }}>
            <label class="form-check-label" for="terms">
                Saya setuju dengan <button type="button" onclick="modalAction('{{ route('syaratketentuan') }}')"
                    class="text-dark font-weight-bolder"
                    style="background:none; border:none; padding:0; display:inline; vertical-align:baseline; cursor:pointer;">Syarat
                    dan Ketentuan</button>
            </label>
            <small id="error-terms" class="error-text text-danger d-block mt-1"></small>
        </div>

        <div class="row align-items-center mt-4">
            <div class="col-7">
                <a href="{{ url('login') }}" class="link-text text-decoration-none hover-blue">Sudah punya akun? Login
                    di sini</a>
            </div>
            <div class="col-5 text-end">
                <button type="submit" class="btn btn-dark rounded-pill">Sign up</button> {{-- Mengganti style tombol --}}
            </div>
        </div>
    </form>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@section('bottom-link')
    <a href="{{ route('landing') }}" class="link-text text-decoration-none hover-blue">Kembali ke Beranda</a>
@endsection

@section('scripts')
    {{-- Pastikan iziToast sudah di-load oleh authTemplate Anda --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>

    <style>
        .error-text,
        label.error {
            /* jQuery validate default error class is 'error' */
            color: #dc3545;
            /* Bootstrap danger color */
            font-size: 0.875em;
            /* Slightly smaller than default form text */
            display: block;
            margin-top: .25rem;
        }

        .form-control.error {
            border-color: #dc3545;
        }

        .input-group .form-control.error {
            z-index: 2 !important;
        }

        .form-label {
            /* Sedikit styling untuk label agar konsisten */
            font-weight: 500;
            margin-bottom: .5rem;
        }
    </style>

    <script>
        function modalAction(url = '') {
            // Kosongkan modal dulu untuk menghindari konten lama muncul saat loading
            $('#myModal').html(
                '<div class="modal-dialog modal-lg"><div class="modal-content"><div class="modal-body text-center p-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Memuat konten...</p></div></div></div>'
            ).modal('show');
            $('#myModal').load(url, function() {});
        }

        $(document).ready(function() {
            // Tampilkan atau sembunyikan field NIDN/NIM dan Role Dosen berdasarkan role utama
            $('#role').change(function() {
                var role = $(this).val();
                $('.error-text').text(''); // Bersihkan error sebelumnya
                $('input.error, select.error').removeClass('error'); // Hapus kelas error dari input

                $('#nidn').val(role === 'dosen' ? $('#nidn').val() : '').removeClass('error');
                $('#error-nidn').text('');
                $('#nim').val(role === 'mahasiswa' ? $('#nim').val() : '').removeClass('error');
                $('#error-nim').text('');
                $('#role_dosen_signup').val('').removeClass('error'); // Reset role dosen
                $('#error-role_dosen_signup').text('');


                if (role === 'dosen') {
                    $('#nidn-group').slideDown();
                    $('#role-dosen-group').slideDown(); // Tampilkan dropdown peran dosen
                    $('#nim-group').slideUp();
                } else if (role === 'mahasiswa') {
                    $('#nidn-group').slideUp();
                    $('#role-dosen-group').slideUp(); // Sembunyikan dropdown peran dosen
                    $('#nim-group').slideDown();
                } else {
                    $('#nidn-group').slideUp();
                    $('#role-dosen-group').slideUp(); // Sembunyikan dropdown peran dosen
                    $('#nim-group').slideUp();
                }
            });
            // Trigger pertama kali saat halaman dimuat
            $('#role').trigger('change');


            $("#form-register").validate({
                rules: {
                    role: {
                        required: true
                    },
                    // TAMBAHAN: Validasi untuk role_dosen_signup
                    role_dosen_signup: {
                        required: function() {
                            return $('#role').val() === 'dosen';
                        }
                    },
                    // AKHIR TAMBAHAN
                    nidn: {
                        required: function() {
                            return $('#role').val() === 'dosen';
                        },
                        digits: true,
                        minlength: 10, // Sesuaikan dengan NIDN/NIP yang valid
                        maxlength: 20 // Sesuaikan
                    },
                    nim: {
                        required: function() {
                            return $('#role').val() === 'mahasiswa';
                        },
                        // digits: true, // NIM bisa jadi ada huruf
                        minlength: 8,
                        maxlength: 15 // Sesuaikan
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
                        required: "Silakan pilih role utama Anda."
                    },
                    // TAMBAHAN: Pesan untuk role_dosen_signup
                    role_dosen_signup: {
                        required: "Silakan pilih peran dosen Anda (DPA/Pembimbing)."
                    },
                    // AKHIR TAMBAHAN
                    nidn: {
                        required: "NIDN/NIP wajib diisi untuk dosen.",
                        digits: "NIDN/NIP harus berupa angka.",
                        minlength: "NIDN/NIP minimal 10 digit.",
                        maxlength: "NIDN/NIP maksimal 20 digit."
                    },
                    nim: {
                        required: "NIM wajib diisi untuk mahasiswa.",
                        // digits: "NIM harus berupa angka.",
                        minlength: "NIM minimal 8 digit.",
                        maxlength: "NIM maksimal 15 digit."
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
                errorElement: 'small', // Gunakan tag <small> untuk pesan error
                errorClass: 'error-text text-danger d-block', // Kelas yang sama dengan <small> manual Anda
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid').removeClass(validClass);
                    // Hapus pesan error dari small tag manual jika jQuery Validate mengambil alih
                    $('#error-' + $(element).attr('name')).text('');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid').addClass(validClass);
                    $('#error-' + $(element).attr('name')).text(''); // Bersihkan juga small tag manual
                },
                errorPlacement: function(error, element) {
                    // Tempatkan error di dalam <small id="error-FIELD_NAME">
                    var errorContainerId = '#error-' + element.attr('name');
                    $(errorContainerId).html(error.text()).addClass(
                        'error'); // Tambahkan kelas error juga ke small tag
                },

                submitHandler: function(form) {
                    $('.error-text').text(''); // Clear previous errors displayed in <small> tags
                    var formData = new FormData(form);

                    // Tampilkan loading (opsional)
                    var submitButton = $(form).find('button[type="submit"]');
                    var originalButtonText = submitButton.html();
                    submitButton.html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
                    ).prop('disabled', true);


                    $.ajax({
                        url: $(form).attr('action'),
                        method: $(form).attr('method'),
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            submitButton.html(originalButtonText).prop('disabled', false);
                            if (response.status) {
                                iziToast.success({
                                    title: 'Berhasil',
                                    message: response.message ||
                                        'Registrasi berhasil.',
                                    position: 'topCenter',
                                    timeout: 2500,
                                    onClosed: function() {
                                        if (response.redirect) {
                                            window.location.href = response
                                                .redirect;
                                        } else {
                                            window.location.href =
                                                "{{ url('login') }}";
                                        }
                                    }
                                });
                            } else {
                                let generalErrorMessage = response.message ||
                                    'Terjadi kesalahan saat registrasi.';
                                if (response.errors) {
                                    $.each(response.errors, function(key, value) {
                                        $('#error-' + key.replace(/\./g, '_')).text(
                                            value[0]).addClass(
                                            'error'); // Handle nested errors if any
                                        $('#' + key.replace(/\./g, '_')).addClass(
                                            'is-invalid');
                                    });
                                    generalErrorMessage =
                                        "Silakan periksa kembali data yang Anda masukkan.";
                                }
                                iziToast.error({
                                    title: 'Gagal',
                                    message: generalErrorMessage,
                                    position: 'topCenter',
                                    timeout: 5000
                                });
                            }
                        },
                        error: function(xhr) {
                            submitButton.html(originalButtonText).prop('disabled', false);
                            let errorMessage =
                                'Terjadi kesalahan pada server. Silakan coba lagi nanti.';
                            if (xhr.responseJSON) {
                                if (xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                if (xhr.responseJSON.errors) {
                                    $.each(xhr.responseJSON.errors, function(key, value) {
                                        $('#error-' + key.replace(/\./g, '_')).text(
                                            value[0]).addClass('error');
                                        $('#' + key.replace(/\./g, '_')).addClass(
                                            'is-invalid');
                                    });
                                    if (!xhr.responseJSON
                                        .message
                                        ) { // Hanya jika tidak ada pesan general dari server
                                        errorMessage =
                                            "Data yang dimasukkan tidak valid. Periksa kembali isian Anda.";
                                    }
                                }
                            }
                            iziToast.error({
                                title: 'Error',
                                message: errorMessage,
                                position: 'topCenter',
                                timeout: 5000
                            });
                        }
                    });
                }
            });
        });
    </script>
@endsection
