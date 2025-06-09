    <form action="{{ route('admin.store') }}" method="POST" id="form-create-admin">
        @csrf
        <div id="modal-master" class="modal-dialog modal-lg" role="document" style="max-width:60vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Admin</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                               value="{{ old('nama_lengkap') }}" required>
                        <small id="error-nama_lengkap" class="error-text text-danger"></small>
                    </div>
                    <div class="form-group mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                               value="{{ old('email') }}" required>
                        <small id="error-email" class="error-text text-danger"></small>
                    </div>
                    <div class="form-group">
                            <label>Telepon</label>
                            <input type="text" name="telepon" class="form-control"
                                value="{{ old('telepon') }}" required pattern="^(\+62|0)[0-9]{8,15}$" title="Masukkan nomor telepon yang valid, contoh: 081234567890">
                            <small id="error-telepon" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group mb-3">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        <small id="error-password" class="error-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            onclick="$('#myModal').modal('hide')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
    $(document).ready(function () {
        $("#form-create-admin").validate({
            rules: {
                nama_lengkap: { required: true, minlength: 3 },
                email:        { required: true, email: true },
                telepon:      { required: true, minlength: 9, maxlength: 15},
                password:     { required: true, minlength: 6 },
            },
            submitHandler: function (form) {
                $.ajax({
                    url:    form.action,
                    type:   form.method,
                    data:   $(form).serialize(),
                    success: function (res) {
                        if (res.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');
                            dataAdmin.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(res.msgField, function (key, val) {
                                $('#error-' + key).text(val[0]);
                            });
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    },
                    error: function () {
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element) {
                $(element).removeClass('is-invalid');
            }
        });
    });
    </script>
@endempty
