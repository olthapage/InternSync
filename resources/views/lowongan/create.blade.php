@empty($industri)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data industri tidak tersedia.
                </div>
                <button class="btn btn-warning" onclick="$('#myModal').modal('hide')">Tutup</button>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('lowongan.store') }}" method="POST" id="form-create">
        @csrf
        <div id="modal-master" class="modal-dialog modal-lg" role="document" style="max-width: 60vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Lowongan</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="judul_lowongan">Judul Lowongan</label>
                        <input type="text" name="judul_lowongan" id="judul_lowongan" class="form-control" required>
                        <small id="error-judul_lowongan" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required></textarea>
                        <small id="error-deskripsi" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label for="industri_id">Industri</label>
                        <select name="industri_id" id="industri_id" class="form-control" required>
                            <option value="">-- Pilih Industri --</option>
                            @foreach ($industri as $i)
                                <option value="{{ $i->industri_id }}">{{ $i->industri_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-industri_id" class="error-text form-text text-danger"></small>
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
            $("#form-create").validate({
                rules: {
                    judul_lowongan: { required: true, minlength: 3 },
                    deskripsi: { required: true },
                    industri_id: { required: true, number: true }
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
                                dataLowongan.ajax.reload();
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