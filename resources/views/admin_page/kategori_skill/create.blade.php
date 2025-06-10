<form action="{{ url('kategori_skill/store') }}" method="POST" id="form-create">
    @csrf
    <div class="modal-dialog modal-md" role="document" style="max-width: 50vw;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori Skill</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="kategori_nama">Nama Kategori</label>
                    <input type="text" class="form-control" name="kategori_nama" id="kategori_nama" required>
                    <small id="error-kategori_nama" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function () {
        $("#form-create").validate({
            rules: {
                kategori_nama: { required: true, minlength: 2 }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    type: form.method,
                    data: $(form).serialize(),
                    success: function (res) {
                        if (res.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');
                            dataKategori.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(res.msgField, function (key, val) {
                                $('#error-' + key).text(val[0]);
                            });
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    },
                    error: function (xhr) {
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
