    <form action="{{ route('kategori-industri.store') }}" method="POST" id="form-create-kategori">
        @csrf
        <div id="modal-master" class="modal-dialog modal-lg" role="document" style="max-width:60vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Kategori Industri</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label>Kode Kategori</label>
                        <input type="text" name="kategori_industri_kode" id="kategori_industri_kode" 
                               class="form-control" value="{{ $kodeKategori ?? '' }}" readonly>
                    </div>
                    <div class="form-group mb-3">
                        <label>Nama Kategori Industri</label>
                        <input type="text" name="kategori_nama" id="kategori_nama" class="form-control"
                               value="{{ old('kategori_nama') }}" required>
                        <small id="error-kategori_nama" class="error-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>

<script>
    $(document).ready(function () {
        $("#form-create-kategori").validate({
            rules: {
                kategori_nama: { required: true, minlength: 3 }
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
                            dataKategori.ajax.reload();
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