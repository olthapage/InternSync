<form action="{{ url('/dosen/' . $dosen->dosen_id . '/update') }}" method="POST" id="form-edit" enctype="multipart/form-data">
    @csrf
    @method('POST') {{-- Laravel akan menghandle ini sebagai PUT/PATCH jika route-nya demikian --}}

    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                {{-- Judul dinamis berdasarkan siapa yang login --}}
                @if (Auth::guard('web')->check())
                    <h5 class="modal-title">Edit Data Dosen: {{ $dosen->nama_lengkap }}</h5>
                @else
                    <h5 class="modal-title">Edit Profil Saya</h5>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                {{-- ============================================= --}}
                {{--      KONDISI UTAMA BERDASARKAN GUARD         --}}
                {{-- ============================================= --}}

                @if (Auth::guard('web')->check())

                    {{-- ======================================================= --}}
                    {{--         TAMPILAN UNTUK ADMIN (EDIT LENGKAP)           --}}
                    {{-- Ini adalah kode form asli Anda, tidak banyak berubah --}}
                    {{-- ======================================================= --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ $dosen->nama_lengkap }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="{{ $dosen->email }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $dosen->telepon) }}">
                            </div>
                             <div class="form-group mb-3">
                                <label>NIP</label>
                                <input type="text" name="nip" class="form-control" value="{{ $dosen->nip }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Program Studi</label>
                                <select name="prodi_id" class="form-select" required>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach($prodi as $prd)
                                        <option value="{{ $prd->prodi_id }}" {{ $dosen->prodi_id == $prd->prodi_id ? 'selected' : '' }}>
                                            {{ $prd->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="form-group mb-3">
                                <label>Foto (opsional)</label><br>
                                <img src="{{ $dosen->foto ? asset('storage/dosen/foto/' . $dosen->foto) : asset('assets/default-profile.png') }}" alt="Foto Dosen" width="100" class="mb-2 rounded img-thumbnail">
                                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
                                <small class="form-text text-muted">Kosongkan jika tidak diubah. Maks 2MB.</small>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <label>Password</label>
                        <div class="row g-2">
                            <div class="col-9">
                                <input type="password" class="form-control" value="********" disabled>
                            </div>
                            <div class="col-3">
                                <button type="button" class="btn btn-danger w-100" id="btn-reset-password">Reset Password</button>
                            </div>
                        </div>
                        <input type="hidden" name="reset_password" id="reset_password" value="0">
                        <small class="form-text text-muted">Klik tombol untuk mereset password ke default.</small>
                    </div>

                @else

                    {{-- ======================================================== --}}
                    {{--   TAMPILAN UNTUK DOSEN (EDIT PROFIL SENDIRI)           --}}
                    {{-- ======================================================== --}}
                    <div class="row">
                        {{-- Kolom Kiri: Data yang bisa diedit oleh Dosen --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ $dosen->nama_lengkap }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $dosen->telepon) }}">
                            </div>
                             <div class="form-group mb-3">
                                <label>Foto Profil (opsional)</label><br>
                                <img src="{{ $dosen->foto ? asset('storage/dosen/foto/' . $dosen->foto) : asset('assets/default-profile.png') }}" alt="Foto Dosen" width="100" class="mb-2 rounded img-thumbnail">
                                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
                                <small class="form-text text-muted">Kosongkan jika tidak diubah. Maks 2MB.</small>
                            </div>
                            <div class="form-group mb-3">
                                <label>Password Baru</label>
                                <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak ingin diubah">
                                <small class="form-text text-muted">Isi untuk mengubah password Anda.</small>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Data yang HANYA TAMPIL (read-only) --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" class="form-control" value="{{ $dosen->email }}" readonly>
                                <small class="form-text text-muted">Email tidak dapat diubah.</small>
                            </div>
                            <div class="form-group mb-3">
                                <label>NIP</label>
                                <input type="text" class="form-control" value="{{ $dosen->nip }}" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label>Program Studi</label>
                                <input type="text" class="form-control" value="{{ optional($dosen->prodi)->nama_prodi ?? 'N/A' }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

<script>
    jQuery.validator.addMethod("phoneID", function (value, element) {
        if (this.optional(element)) {
            return true;
        }

        // Jika ada isinya, baru jalankan validasi seperti biasa.
        const cleaned = value.replace(/\D/g, ''); // hanya angka
        return (value.startsWith("0") || value.startsWith("+62")) && cleaned.length >= 9 && cleaned.length <= 15;
    }, "Masukkan nomor telepon yang valid");
    
    $(document).ready(function() {
        // Show filename when selected
        $('#foto').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Pilih file...');

            // Simple validation for file size
            if (this.files[0] && this.files[0].size > 2 * 1024 * 1024) {
                $('#error-foto').text('File terlalu besar (maks. 2MB)');
                $(this).val('');
                $(this).next('.custom-file-label').html('Pilih file...');
            } else {
                $('#error-foto').text('');
            }
        });

        $("#form-edit").validate({
            rules: {
                nama_lengkap: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true
                },
                telepon: {
                    minlength: 9,
                    maxlength: 15,
                    phoneID: true
                },
                nip: {
                    required: true
                },
                prodi_id: {
                    required: true,
                    number: true
                }
            },
            submitHandler: function(form) {
                let formData = new FormData(form);

                // Debugging the FormData content
                console.log("Form data being sent:");
                for(let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1]));
                }

                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: formData,
                    processData: false, // Required for FormData
                    contentType: false, // Required for FormData
                    success: function(res) {
                        if (res.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');
                            dataDosen.ajax.reload(null, false);
                        } else {
                            $('.error-text').text('');
                            $.each(res.msgField, function(key, val) {
                                $('#error-' + key).text(val[0]);
                            });
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                        console.log(xhr.responseText);
                    }
                });

                return false;
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).removeClass('is-invalid');
            }
        });

        $('#btn-reset-password').click(function() {
            Swal.fire({
                title: 'Reset Password?',
                text: 'Password akan diset ulang.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#reset_password').val(1);
                    Swal.fire('Password Diset Ulang', 'Password akan direset saat disimpan.', 'info');
                }
            });
        });
    });
</script>
