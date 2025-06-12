<form action="{{ route('mahasiswa.update', $mahasiswa->mahasiswa_id) }}" method="POST" id="form-edit-mahasiswa" enctype="multipart/form-data">
    @csrf
    @method('POST')

    <div class="modal-dialog modal-lg" role="document" style="max-width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                {{-- Judul dinamis berdasarkan siapa yang login --}}
                @if (Auth::guard('web')->check())
                    <h5 class="modal-title">Edit Data Mahasiswa: {{ $mahasiswa->nama_lengkap }}</h5>
                @else
                    <h5 class="modal-title">Edit Profil Saya</h5>
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                {{-- ====================================================== --}}
                {{--                 KONDISI UTAMA DIMULAI DI SINI         --}}
                {{-- ====================================================== --}}

                @if (Auth::guard('web')->check())

                    {{-- ============================================= --}}
                    {{--                  TAMPILAN UNTUK ADMIN         --}}
                    {{-- (Kode form asli Anda, tidak ada perubahan) --}}
                    {{-- ============================================= --}}
                    <div class="row">
                        {{-- Kolom Kiri (Data Pribadi & Akademik) --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $mahasiswa->email) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $mahasiswa->telepon) }}">
                            </div>
                             <div class="form-group mb-3">
                                <label class="form-label">NIM <span class="text-danger">*</span></label>
                                <input type="text" name="nim" class="form-control" value="{{ old('nim', $mahasiswa->nim) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">IPK</label>
                                <input type="number" step="0.01" name="ipk" class="form-control" value="{{ old('ipk', $mahasiswa->ipk) }}">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Password <small class="text-muted">(Kosongkan jika tidak diubah)</small></label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Foto <small class="text-muted">(Kosongkan jika tidak diubah)</small></label><br>
                                @if ($mahasiswa->foto)
                                    <img src="{{ asset('storage/mahasiswa/foto/' . $mahasiswa->foto) }}" alt="Foto Mahasiswa" width="100" class="mb-2 rounded img-thumbnail">
                                @else
                                    <img src="{{ asset('assets/images/default-avatar.png') }}" alt="Default Foto" width="100" class="mb-2 rounded img-thumbnail">
                                @endif
                                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
                                <small class="form-text text-muted">Format: JPG, PNG. Maks 2MB.</small>
                            </div>
                        </div>

                        {{-- Kolom Kanan (Status, Prodi, Dosen) --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Status Akun <span class="text-danger">*</span></label>
                                <select name="status" class="form-select" required>
                                    <option value="1" {{ old('status', $mahasiswa->status) == 1 ? 'selected' : '' }}>Aktif</option>
                                    <option value="0" {{ old('status', $mahasiswa->status) == 0 ? 'selected' : '' }}>Tidak Aktif</option>
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                                <select name="prodi_id" class="form-select" required>
                                    @foreach ($prodiList as $p)
                                        <option value="{{ $p->prodi_id }}" {{ old('prodi_id', $mahasiswa->prodi_id) == $p->prodi_id ? 'selected' : '' }}>{{ $p->nama_prodi }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Dosen Penasehat Akademik (DPA)</label>
                                <select name="dpa_id" class="form-select">
                                    <option value="">-- Pilih DPA --</option>
                                    @foreach ($dosenDpaList as $dpa)
                                        <option value="{{ $dpa->dosen_id }}" {{ old('dpa_id', $mahasiswa->dpa_id) == $dpa->dosen_id ? 'selected' : '' }}>{{ $dpa->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Field Dosen Pembimbing untuk Admin --}}
                             <div class="form-group mb-3">
                                <label class="form-label">Dosen Pembimbing Magang</label>
                                <select name="dosen_id" class="form-select">
                                    <option value="">-- Pilih Dosen Pembimbing --</option>
                                    @foreach ($dosenPembimbingList as $pembimbing)
                                        <option value="{{ $pembimbing->dosen_id }}" {{ old('dosen_id', $mahasiswa->dosen_id) == $pembimbing->dosen_id ? 'selected' : '' }}>{{ $pembimbing->nama_lengkap }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                @else

                    {{-- ============================================= --}}
                    {{-- TAMPILAN UNTUK SELAIN ADMIN (EDIT PROFIL) --}}
                    {{-- ============================================= --}}
                    <div class="row">
                        {{-- Kolom Kiri: Data yang bisa diedit --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $mahasiswa->email) }}" required>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Telepon <span class="text-danger">*</span></label>
                                <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $mahasiswa->telepon) }}">
                            </div>
                             <div class="form-group mb-3">
                                <label class="form-label">Password <small class="text-muted">(Kosongkan jika tidak ingin diubah)</small></label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Foto Profil <small class="text-muted">(Kosongkan jika tidak diubah)</small></label><br>
                                @if ($mahasiswa->foto)
                                    <img src="{{ asset('storage/mahasiswa/foto/' . $mahasiswa->foto) }}" alt="Foto Mahasiswa" width="100" class="mb-2 rounded img-thumbnail">
                                @else
                                    <img src="{{ asset('assets/images/default-avatar.png') }}" alt="Default Foto" width="100" class="mb-2 rounded img-thumbnail">
                                @endif
                                <input type="file" name="foto" class="form-control" accept="image/jpeg,image/png,image/jpg">
                                <small class="form-text text-muted">Format: JPG, PNG. Maks 2MB.</small>
                            </div>
                        </div>

                        {{-- Kolom Kanan: Data yang HANYA TAMPIL (read-only) --}}
                        <div class="col-md-6">
                             <div class="form-group mb-3">
                                <label class="form-label">NIM</label>
                                <input type="text" class="form-control" value="{{ $mahasiswa->nim }}" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Program Studi</label>
                                {{-- Menggunakan null-safe operator (?) dan null coalescing (??) untuk keamanan --}}
                                <input type="text" class="form-control" value="{{ $mahasiswa->prodi?->nama_prodi ?? 'Tidak ada data' }}" readonly>
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label">Dosen Penasehat Akademik (DPA)</label>
                                <input type="text" class="form-control" value="{{ $mahasiswa->dpa?->nama_lengkap ?? 'Belum ditentukan' }}" readonly>
                            </div>
                             <div class="form-group mb-3">
                                <label class="form-label">Dosen Pembimbing Magang</label>
                                <input type="text" class="form-control" value="{{ $mahasiswa->dosenPembimbing?->nama_lengkap ?? 'Belum ditentukan' }}" readonly>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- ====================================================== --}}
                {{--                   KONDISI UTAMA SELESAI               --}}
                {{-- ====================================================== --}}

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
            </div>
        </div>
    </div>
</form>

{{-- Script untuk menampilkan/menyembunyikan field Dosen Pembimbing berdasarkan status --}}
{{-- Ini hanya contoh sederhana, Anda bisa membuatnya lebih dinamis jika status diupdate via AJAX di halaman ini --}}
<script>
    // document.addEventListener('DOMContentLoaded', function() {
    //     const statusMagangSelect = document.getElementById('status_magang_di_form_edit'); // Jika ada select status magang di form ini
    //     const dosenPembimbingGroup = document.getElementById('dosen-pembimbing-group');

    //     function toggleDosenPembimbingField() {
    //         if (statusMagangSelect && dosenPembimbingGroup) {
    //             // Sesuaikan kondisi ini dengan nilai status yang menandakan mahasiswa sudah diterima/sedang magang
    //             if (['diterima', 'sedang', 'akan_magang'].includes(statusMagangSelect.value)) {
    //                 dosenPembimbingGroup.style.display = 'block';
    //             } else {
    //                 dosenPembimbingGroup.style.display = 'none';
    //                 // document.getElementById('dosen_id').value = ''; // Opsional: reset pilihan jika disembunyikan
    //             }
    //         }
    //     }
    //     if(statusMagangSelect) {
    //        statusMagangSelect.addEventListener('change', toggleDosenPembimbingField);
    //        toggleDosenPembimbingField(); // Panggil saat load
    //     }
    // });
     jQuery.validator.addMethod("phoneID", function (value, element) {
        if (this.optional(element)) {
            return true;
        }

        // Jika ada isinya, baru jalankan validasi seperti biasa.
        const cleaned = value.replace(/\D/g, ''); // hanya angka
        return (value.startsWith("0") || value.startsWith("+62")) && cleaned.length >= 9 && cleaned.length <= 15;
    }, "Masukkan nomor telepon yang valid");

    // Untuk jQuery Validate dan file input:
    $(document).ready(function() {
        $('#foto').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Pilih file...');
            if (this.files[0] && this.files[0].size > 2 * 1024 * 1024) { // 2MB
                $('#error-foto').text('File terlalu besar (maks. 2MB)'); $(this).val('');
                $(this).next('.custom-file-label').html('Pilih file...');
            } else { $('#error-foto').text(''); }
        });

        $('#form-edit-mahasiswa').validate({
            // ... (rules dan messages Anda, pastikan ada untuk dpa_id (nullable) dan dosen_id (nullable)) ...
             rules: {
                nama_lengkap: { required: true, minlength: 3 },
                email: { required: true, email: true },
                telepon: {
                    minlength: 9,
                    maxlength: 15,
                    phoneID: true
                },
                nim: { required: true },
                ipk: { number: true, min: 0, max: 4 },
                password: { minlength: 6, maxlength: 20 }, // Tidak required, hanya jika diisi
                prodi_id: { required: true },
                status: { required: true },
                dpa_id: { required: false }, // Atau true jika wajib punya DPA
                dosen_id: { required: false } // Pembimbing bisa null jika belum magang
            },
            // Tambahkan messages jika perlu
            submitHandler: function(form) {
                let formData = new FormData(form);
                // Tambahkan _method PUT secara manual jika form method POST tapi rute PUT
                // formData.append('_method', 'PUT'); // Tidak perlu jika form method="POST" dan route Route::put(...)

                $.ajax({
                    url: form.action,
                    type: 'POST', // Karena form method POST, @method('PUT') akan dihandle Laravel
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(res) {
                        if (res.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');
                            if (typeof dataTableInstance !== 'undefined') { // Cek jika dataTableInstance ada
                                dataTableInstance.ajax.reload(null, false);
                            } else if (typeof dataMhs !== 'undefined') { // Fallback ke dataMhs
                                dataTableInstance.ajax.reload(null,false);
                            } else {
                                location.reload(); // fallback paling akhir
                            }
                        } else {
                            // Menampilkan error validasi dari server (jika ada msgField)
                            $('.error-text').text(''); // Bersihkan error lama
                             if(res.msgField){
                                $.each(res.msgField, function(key, val) {
                                    $('#error-' + key).text(val[0]);
                                    $('[name="'+key+'"]').addClass('is-invalid');
                                });
                             }
                            Swal.fire('Gagal', res.message || 'Terjadi kesalahan validasi.', 'error');
                        }
                    },
                    error: function(xhr) {
                        Swal.fire('Error', 'Terjadi kesalahan pada server: ' + (xhr.responseJSON ? xhr.responseJSON.message : xhr.statusText) , 'error');
                        console.log(xhr.responseText);
                    }
                });
                return false; // Mencegah submit form standar
            },
            // ... (errorPlacement, highlight, unhighlight Anda) ...
            errorElement: 'small',
            errorClass: 'error-text text-danger d-block',
            highlight: function(element) { $(element).addClass('is-invalid'); $('#error-' + $(element).attr('name')).text(''); },
            unhighlight: function(element) { $(element).removeClass('is-invalid'); $('#error-' + $(element).attr('name')).text('');},
            errorPlacement: function(error, element) {
                var errorContainerId = '#error-' + element.attr('name');
                if ($(errorContainerId).length) {
                    $(errorContainerId).html(error.text()).addClass('error');
                } else {
                     error.insertAfter(element); // Fallback jika tidak ada small tag
                }
            }
        });
    });
</script>
