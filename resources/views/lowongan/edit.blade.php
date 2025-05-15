<form action="{{ url('/lowongan/' . $lowongan->lowongan_id . '/update') }}" method="POST" id="form-edit">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content"> 
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Lowongan</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Judul Lowongan</label>
                    <input type="text" name="judul_lowongan" id="judul_lowongan" class="form-control"
                        value="{{ $lowongan->judul_lowongan }}" required>
                    <small id="error-judul_lowongan" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Industri</label>
                    <select name="industri_id" id="industri_id" class="form-control" required>
                        <option value="">-- Pilih Industri --</option>
                        @foreach($industri as $i)
                            <option value="{{ $i->industri_id }}" {{ $lowongan->industri_id == $i->industri_id ? 'selected' : '' }}>
                                {{ $i->industri_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-industri_id" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="5" required>{{ $lowongan->deskripsi }}</textarea>
                    <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
                <button type="submit" class="btn btn-primary">Update</button>
            </div>
        </div>
    </div>
</form>

@push('js')
<script>
    $(document).ready(function () {
        $("#form-edit").validate({
            rules: {
                judul_lowongan: { required: true, minlength: 3 },
                industri_id: { required: true, number: true },
                deskripsi: { required: true, minlength: 10 }
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
                            dataLow.ajax.reload();
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
                        console.log(xhr.responseText);
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
