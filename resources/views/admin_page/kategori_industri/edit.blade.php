<form action="{{ url('kategori-industri/' . $kategori->kategori_industri_id . '/update') }}" method="POST" id="form-edit" enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori Industri</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Kode Kategori</label>
                    <input type="text" name="kategori_industri_kode" id="kategori_industri_kode" class="form-control"
                        value="{{ $kategori->kategori_industri_kode }}" required>
                    <small id="error-kategori_industri_kode" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Nama Kategori</label>
                    <input type="text" name="kategori_nama" id="kategori_nama" class="form-control"
                        value="{{ $kategori->kategori_nama }}" required>
                    <small id="error-kategori_nama" class="error-text form-text text-danger"></small>
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
    $(document).ready(function () {
        $("#form-edit").validate({
            rules: {
                kategori_industri_kode: {
                    required: true,
                    minlength: 2
                },
                kategori_nama: {
                    required: true,
                    minlength: 3
                }
            },
            messages: {
                kategori_industri_kode: {
                    required: "Kode kategori wajib diisi",
                    minlength: "Minimal 2 karakter"
                },
                kategori_nama: {
                    required: "Nama kategori wajib diisi",
                    minlength: "Minimal 3 karakter"
                }
            },
            submitHandler: function (form) {
                let formData = new FormData(form);

                $.ajax({
                    url: form.action,
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (res) {
                        if (res.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');
                            $('#datatable').DataTable().ajax.reload(null, false);
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    },
                    error: function (xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $('.error-text').text('');
                            $('.form-control').removeClass('is-invalid');

                            $.each(errors, function (key, value) {
                                $('#error-' + key).text(value[0]);
                                $('#' + key).addClass('is-invalid');
                            });
                        } else {
                            Swal.fire('Error', 'Terjadi kesalahan saat mengupdate data.', 'error');
                            console.error(xhr.responseText);
                        }
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
