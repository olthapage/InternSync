<form action="{{ route('admin.store') }}" method="POST" id="form-create-admin">
    @csrf
    {{-- Karena modalAction Anda me-load seluruh konten, wrapper ini WAJIB ADA --}}
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Admin Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                    <input type="text" name="nama_lengkap" class="form-control" required>
                    <small id="error-nama_lengkap" class="error-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" required>
                    <small id="error-email" class="error-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Telepon <span class="text-danger">*</span></label>
                    <input type="text" name="telepon" class="form-control" required>
                    <small id="error-telepon" class="error-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label class="form-label">Password <span class="text-danger">*</span></label>
                    <input type="password" name="password" id="password" class="form-control" required>
                    <small id="error-password" class="error-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

{{-- Ganti script lama dengan script yang lebih baik ini --}}
<script>
    $("#form-create-admin").validate({
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
                required: true,
                digits: true, // Memastikan hanya angka
                minlength: 9,
                maxlength: 15
            },
            password: {
                required: true,
                minlength: 6
            },
        },
        messages: {
            telepon: {
                digits: "Nomor telepon hanya boleh berisi angka."
            }
            // Tambahkan pesan error kustom lainnya jika perlu
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
                        $('#table_admin').DataTable().ajax.reload();
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
