<form action="{{ url('program-studi/' . $prodi->prodi_id . '/update') }}" method="POST" id="form-edit-prodi">
    @csrf
    <div class="modal-dialog modal-lg" role="document" style="max-width: 50vw;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Program Studi</h5>
            </div>
            <div class="modal-body">
                <div class="form-group mb-3">
                    <label for="kode_prodi">Kode Prodi</label>
                    <input type="text"
                           name="kode_prodi"
                           id="kode_prodi"
                           class="form-control"
                           value="{{ $prodi->kode_prodi }}"
                           required>
                    <small id="error-kode_prodi" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group mb-3">
                    <label for="nama_prodi">Nama Prodi</label>
                    <input type="text"
                           name="nama_prodi"
                           id="nama_prodi"
                           class="form-control"
                           value="{{ $prodi->nama_prodi }}"
                           required>
                    <small id="error-nama_prodi" class="error-text form-text text-danger"></small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button"
                        class="btn btn-secondary"
                        onclick="$('#myModal').modal('hide')">Tutup</button>
                <button type="submit"
                        class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</form>

<script>
$(document).ready(function () {
    $("#form-edit-prodi").validate({
        rules: {
            kode_prodi: { required: true, minlength: 2 },
            nama_prodi: { required: true, minlength: 3 }
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
                        dataProdi.ajax.reload();
                    } else {
                        $('.error-text').text('');
                        $.each(res.msgField ?? res.errors, function (key, val) {
                            $('#error-' + key).text(val[0]);
                        });
                        Swal.fire('Gagal', res.message ?? 'Gagal menyimpan data.', 'error');
                    }
                },
                error: function (xhr) {
                    if (xhr.status === 422) {
                        let errs = xhr.responseJSON.errors;
                        $('.error-text').text('');
                        $.each(errs, function (key, val) {
                            $('#error-' + key).text(val[0]);
                        });
                    } else {
                        Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                        console.log(xhr.responseText);
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
