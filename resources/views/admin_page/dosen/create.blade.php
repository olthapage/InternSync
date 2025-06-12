<form action="{{ route('dosen.store') }}" method="POST" id="form-create-dosen">
    @csrf
    <div class="modal-dialog modal-lg" role="document"> {{-- Wrapper ini WAJIB ADA --}}
        <div class="modal-content"> {{-- Wrapper ini WAJIB ADA --}}
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Dosen</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text" name="nama_lengkap" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" required>
                            <small id="error-email" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Telepon</label>
                            <input type="tel" name="telepon" class="form-control">
                            <small id="error-telepon" class="error-text text-danger"></small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label class="form-label">NIP <span class="text-danger">*</span></label>
                            <input type="text" name="nip" class="form-control" required>
                            <small id="error-nip" class="error-text text-danger"></small>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Password <span class="text-danger">*</span></label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Program Studi <span class="text-danger">*</span></label>
                            <select name="prodi_id" class="form-select" required>
                                <option value="">-- Pilih Prodi --</option>
                                @foreach ($prodi as $p)
                                    <option value="{{ $p->prodi_id }}">{{ $p->nama_prodi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mb-3">
                            <label class="form-label">Role Dosen <span class="text-danger">*</span></label>
                            <select name="role_dosen" class="form-select" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="dpa">Dosen DPA</option>
                                <option value="pembimbing">Dosen Pembimbing</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    // Script sederhana untuk submit form via AJAX
    $("#form-create-dosen").validate({
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
                required: true,
                minlength: 6
            },
            nip: {
                required: true,
                digits: true // Hanya memperbolehkan angka
            },
            telepon: {
                digits: true // Hanya memperbolehkan angka
            },
            prodi_id: {
                required: true
            },
            role_dosen: {
                required: true
            }
        },
        messages: {
            nip: {
                digits: "NIP hanya boleh berisi angka."
            },
            telepon: {
                digits: "Nomor telepon hanya boleh berisi angka."
            },
            // Tambahkan pesan lain jika perlu
        },
        submitHandler: function(form) {
            $.ajax({
                url: $(form).attr('action'),
                type: $(form).attr('method'),
                data: $(form).serialize(),
                success: function(res) {
                    if (res.status) {
                        $('#myModal').modal('hide');
                        Swal.fire('Berhasil', res.message, 'success');
                        dataDosen.ajax.reload();
                    } else {
                        $('.error-text').text('');
                        if (res.msgField) {
                            $.each(res.msgField, function(key, val) {
                                $('#error-' + key).text(val[0]);
                                $('[name="' + key + '"]').addClass('is-invalid');
                            });
                        }
                        Swal.fire('Gagal', res.message || 'Terjadi kesalahan.', 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                }
            });
            return false;
        },
        errorElement: 'small',
        errorClass: 'error-text text-danger d-block',
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
            $('#error-' + $(element).attr('name')).text('');
        },
        errorPlacement: function(error, element) {
            var errorContainerId = '#error-' + element.attr('name');
            if ($(errorContainerId).length) {
                $(errorContainerId).text(error.text());
            } else {
                error.insertAfter(element);
            }
        }
    });
</script>
