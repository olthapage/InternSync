<form action="{{ route('mahasiswa.store') }}" method="POST" id="form-create-mahasiswa">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    {{-- Kiri --}}
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            {{-- Penyesuaian: Menambahkan class 'form-label' dan penanda '*' untuk konsistensi --}}
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control"
                                value="{{ old('nama_lengkap') }}" required>
                            <small id="error-nama_lengkap" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                required>
                            <small id="error-email" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                            <small id="error-password" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">NIM <span class="text-danger">*</span></label>
                            <input type="text" name="nim" class="form-control" value="{{ old('nim') }}"
                                required>
                            <small id="error-nim" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">IPK</label>
                            <input type="number" step="0.01" name="ipk" class="form-control"
                                value="{{ old('ipk') }}">
                            <small id="error-ipk" class="error-text text-danger"></small>
                        </div>
                    </div>
                    {{-- Kolom Kanan --}}
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                            <select name="prodi_id" id="prodi_id_create" class="form-select" required>
                                <option value="">-- Pilih Prodi --</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->prodi_id }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <small id="error-prodi_id" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Dosen Pembimbing Akademik (DPA)</label>
                            <select name="dpa_id" id="dpa_id_create" class="form-select" disabled>
                                <option value="">-- Pilih Prodi Terlebih Dahulu --</option>
                            </select>
                            <small id="error-dpa_id" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Dosen Pembimbing</label>
                            <select name="dosen_id" id="dosen_id_create" class="form-select">
                                <option value="">-- Tidak Ada --</option>
                                {{-- Loop ini sekarang menggunakan $dosenPembimbing dari controller --}}
                                @foreach ($dosenPembimbing as $pembimbing)
                                    <option value="{{ $pembimbing->dosen_id }}">{{ $pembimbing->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            <small id="error-dosen_id" class="error-text text-danger"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

{{-- ========================================================================= --}}
{{-- SCRIPT VALIDASI BARU (Mengadopsi dari form edit) --}}
{{-- ========================================================================= --}}
<script>
    $("#form-create-mahasiswa").validate({
        rules: {
            nama_lengkap: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true, // Di form create, password wajib diisi
                minlength: 6
            },
            nim: {
                required: true,
                digits: true // <-- GANTI/TAMBAHKAN 'digits: true'
            },
            ipk: {
                number: true,
                min: 0,
                max: 4
            },
            prodi_id: {
                required: true,
            },
            dpa_id: {
                required: false // Opsional
            },
            dosen_id: {
                required: false // Opsional
            }
        },
        messages: {
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
                minlength: "Password minimal 6 karakter."
            },
            nim: {
                required: "NIM tidak boleh kosong.",
                digits: "NIM hanya boleh berisi angka." // <-- PESAN UNTUK ATURAN 'digits'
            },
            prodi_id: {
                required: "Program studi wajib dipilih."
            }
        },
        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form)
                    .serialize(), // Menggunakan serialize() sudah cukup karena tidak ada file upload
                success: function(res) {
                    if (res.status) {
                        $('#myModal').modal('hide');
                        Swal.fire('Berhasil', res.message, 'success');
                        dataTableInstance.ajax.reload();
                    } else {
                        // Penanganan error dari server (lebih baik)
                        $('.error-text').text('');
                        if (res.msgField) {
                            $.each(res.msgField, function(key, val) {
                                $('#error-' + key).text(val[0]);
                                $('[name="' + key + '"]').addClass('is-invalid');
                            });
                        }
                        Swal.fire('Gagal', res.message || 'Terjadi kesalahan validasi.',
                            'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Terjadi kesalahan pada server: ' + (xhr.responseJSON ?
                        xhr.responseJSON.message : xhr.statusText), 'error');
                }
            });
            return false;
        },
        // Opsi di bawah ini meniru cara form Edit menampilkan error
        errorElement: 'small',
        errorClass: 'error-text text-danger d-block',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
            // Menghapus pesan error di <small> juga saat unhighlight
            $('#error-' + $(element).attr('name')).text('');
        },
        errorPlacement: function(error, element) {
            // Menempatkan pesan error di dalam tag <small id="error-...">
            var errorContainerId = '#error-' + element.attr('name');
            if ($(errorContainerId).length) {
                $(errorContainerId).text(error.text());
            } else {
                error.insertAfter(element); // Fallback jika tidak ada small tag
            }
        }
    });
    $(document).ready(function() {
        const prodiSelect = $('#prodi_id_create'); // Gunakan ID unik untuk form create
        const dpaSelect = $('#dpa_id_create'); // Gunakan ID unik untuk form create

        function loadDosenDpa(prodiId) {
            // Tampilkan status memuat dan nonaktifkan dropdown
            dpaSelect.html('<option value="">Memuat dosen...</option>').prop('disabled', true);

            if (!prodiId) {
                dpaSelect.html('<option value="">-- Pilih prodi terlebih dahulu --</option>').prop('disabled',
                    true);
                return;
            }

            // Gunakan URL helper yang aman
            const baseUrl = "{{ url('/') }}";
            const url = `${baseUrl}/mahasiswa/get-dosen-by-prodi/${prodiId}`;

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    dpaSelect.empty(); // Kosongkan dropdown sebelum diisi

                    if (data && data.length > 0) {
                        dpaSelect.append('<option value="">-- Pilih Dosen DPA --</option>');
                        $.each(data, function(key, dosen) {
                            dpaSelect.append(
                                `<option value="${dosen.dosen_id}">${dosen.nama_lengkap}</option>`
                            );
                        });
                        dpaSelect.prop('disabled', false); // Aktifkan dropdown
                    } else {
                        dpaSelect.append(
                            '<option value="">-- Tidak ada DPA untuk prodi ini --</option>');
                        dpaSelect.prop('disabled', true); // Tetap nonaktif jika tidak ada data
                    }
                },
                error: function() {
                    console.error("Gagal mengambil data Dosen DPA.");
                    dpaSelect.html('<option value="">-- Gagal memuat --</option>').prop('disabled',
                        true);
                }
            });
        }

        // Pasang event listener ke dropdown prodi
        prodiSelect.on('change', function() {
            loadDosenDpa($(this).val());
        });
    });
</script>
