@empty($activeMenu)
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Data tidak tersedia.</h5>
                </div>
                <button class="btn btn-warning" onclick="$('#ajaxModal').modal('hide')">Tutup</button>
            </div>
        </div>
    </div>
@else
    <form id="form-create-prodi" action="{{ url('program-studi') }}" method="POST">
        @csrf
        <div class="modal-dialog modal-lg" role="document" style="max-width: 50vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Program Studi</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="kode_prodi">Kode Prodi</label>
                        <input type="text"
                               name="kode_prodi"
                               id="kode_prodi"
                               class="form-control"
                               value="{{ old('kode_prodi') }}"
                               required>
                        <small id="error-kode_prodi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="nama_prodi">Nama Prodi</label>
                        <input type="text"
                               name="nama_prodi"
                               id="nama_prodi"
                               class="form-control"
                               value="{{ old('nama_prodi') }}"
                               required>
                        <small id="error-nama_prodi" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            onclick="$('#ajaxModal').modal('hide')">Batal</button>
                    <button type="submit"
                            class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
    $(function () {
        $('#form-create-prodi').validate({
            rules: {
                kode_prodi: { required: true, minlength: 2 },
                nama_prodi: { required: true, minlength: 3 },
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    method: form.method,
                    data: $(form).serialize(),
                    success: function (res) {
                        if (res.success) {
                            $('#ajaxModal').modal('hide');
                            Swal.fire('Berhasil', res.success, 'success');
                            dataProdi.ajax.reload();
                        } else {
                            Swal.fire('Gagal', res.message ?? 'Terjadi kesalahan.', 'error');
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            let errs = xhr.responseJSON.errors;
                            $('.error-text').text('');
                            $.each(errs, (k, v) => {
                                $('#error-' + k).text(v[0]);
                            });
                        } else {
                            Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                        }
                    }
                });
                return false;
            },
            errorElement: 'span',
            errorPlacement: function (error, elem) {
                error.addClass('invalid-feedback');
                elem.closest('.form-group').append(error);
            },
            highlight: function (elem) {
                $(elem).addClass('is-invalid');
            },
            unhighlight: function (elem) {
                $(elem).removeClass('is-invalid');
            }
        });
    });
    </script>
@endempty
