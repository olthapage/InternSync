<form action="{{ url('/mahasiswa/' . $mahasiswa->mahasiswa_id . '/update') }}" method="POST" id="form-edit-mahasiswa"
    enctype="multipart/form-data">
    @csrf
    <div class="modal-dialog modal-lg" role="document" style="max-width: 70%;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Mahasiswa</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    {{-- Kolom Kiri --}}
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                                value="{{ $mahasiswa->nama_lengkap }}" required>
                            <small id="error-nama_lengkap" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label>Email</label>
                            <input type="email" name="email" id="email" class="form-control"
                                value="{{ $mahasiswa->email }}" required>
                            <small id="error-email" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label>NIM</label>
                            <input type="text" name="nim" id="nim" class="form-control"
                                value="{{ $mahasiswa->nim }}" required>
                            <small id="error-nim" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label>IPK</label>
                            <input type="number" step="0.01" name="ipk" id="ipk" class="form-control"
                                value="{{ $mahasiswa->ipk }}">
                            <small id="error-ipk" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label>Password <small>(Kosongkan jika tidak diganti)</small></label>
                            <input type="password" name="password" id="password" class="form-control">
                            <small id="error-password" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <label>Foto (opsional)</label><br>
                            @if ($mahasiswa->foto)
                                <img src="{{ asset('storage/foto/' . $mahasiswa->foto) }}" alt="Foto Mahasiswa"
                                    width="100" class="mb-2 rounded">
                            @endif
                            <div class="custom-file">
                                <input type="file" name="foto" id="foto" class="custom-file-input"
                                    accept="image/jpeg,image/png,image/jpg">
                                <label class="custom-file-label" for="foto">Pilih file...</label>
                                <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                                <small id="error-foto" class="error-text form-text text-danger"></small>
                            </div>
                        </div>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Status Magang</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="">-- Pilih Status --</option>
                                <option value="1" {{ $mahasiswa->status == 1 ? 'selected' : '' }}>Sudah Magang
                                </option>
                                <option value="0" {{ $mahasiswa->status == 0 ? 'selected' : '' }}>Belum Magang
                                </option>
                            </select>
                            <small id="error-status" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label>Program Studi</label>
                            <select name="prodi_id" id="prodi_id" class="form-select" required>
                                <option value="">-- Pilih Prodi --</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->prodi_id }}"
                                        {{ $mahasiswa->prodi_id == $p->prodi_id ? 'selected' : '' }}>
                                        {{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                            <small id="error-prodi_id" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label>Level</label>
                            <select name="level_id" id="level_id" class="form-select" required>
                                <option value="">-- Pilih Level --</option>
                                @foreach ($level as $l)
                                    <option value="{{ $l->level_id }}"
                                        {{ $mahasiswa->level_id == $l->level_id ? 'selected' : '' }}>
                                        {{ $l->level_nama }}</option>
                                @endforeach
                            </select>
                            <small id="error-level_id" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label>Dosen Pembimbing</label>
                            <select name="dosen_id" id="dosen_id" class="form-select">
                                <option value="">-- Tidak Ada --</option>
                                @foreach ($dosen as $d)
                                    <option value="{{ $d->dosen_id }}"
                                        {{ $mahasiswa->dosen_id == $d->dosen_id ? 'selected' : '' }}>
                                        {{ $d->nama_lengkap }}</option>
                                @endforeach
                            </select>
                            <small id="error-dosen_id" class="error-text text-danger"></small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</form>

<script>
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

        // Form validation and submission
        $('#form-edit-mahasiswa').validate({
            rules: {
                nama_lengkap: {
                    required: true,
                    minlength: 3
                },
                email: {
                    required: true,
                    email: true
                },
                nim: {
                    required: true
                },
                ipk: {
                    number: true
                },
                password: {
                    minlength: 6
                },
                level_id: {
                    required: true
                },
                prodi_id: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            submitHandler: function(form) {
                let formData = new FormData(form);

                // Debugging the FormData content
                console.log("Form data being sent:");
                for (let pair of formData.entries()) {
                    console.log(pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[
                    1]));
                }

                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: formData,
                    processData: false, // WAJIB: agar FormData tidak diubah jadi string
                    contentType: false, // WAJIB: agar browser setting header secara otomatis
                    success: function(res) {
                        if (res.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');
                            dataMahasiswa.ajax.reload(null, false);
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
    });
</script>
