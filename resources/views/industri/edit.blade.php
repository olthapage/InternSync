<form action="{{ url('/industri/', $industri->industri_id . '/update') }}" method="POST" id="form-edit">
    @csrf
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Industri</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Industri</label>
                    <input type="text" name="industri_nama" id="industri_nama" class="form-control"
                        value="{{ $industri->industri_nama }}" required>
                    <small id="error-industri_nama" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Kota</label>
                    <select name="kota_id" id="kota_id" class="form-control" required>
                        <option value="">-- Pilih Kota --</option>
                        @foreach ($kota as $k)
                            <option value="{{ $k->kota_id }}"
                                {{ $industri->kota_id == $k->kota_id ? 'selected' : '' }}>
                                {{ $k->kota_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-kota_id" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label>Kategori Industri</label>
                    <select name="kategori_industri_id" id="kategori_industri_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach ($kategori as $kat)
                            <option value="{{ $kat->kategori_industri_id }}"
                                {{ $industri->kategori_industri_id  == $kat->kategori_industri_id ? 'selected' : '' }}>
                                {{ $kat->kategori_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-kategori_industri_id" class="error-text form-text text-danger"></small>
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
            industri_nama: { required: true, minlength: 3},
            kota_id: { required: true},
            kategori_industri_id: { required: true }
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
                        dataIndustri.ajax.reload(); 
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