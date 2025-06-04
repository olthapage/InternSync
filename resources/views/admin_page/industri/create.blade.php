@empty($kota)
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Data kota tidak tersedia.</h5>
                </div>
                <button class="btn btn-warning" onclick="$('#myModal').modal('hide')">Tutup</button>
            </div>
        </div>
    </div>
@else
    <form id="form-create-industri" action="{{ route('industri.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-dialog modal-lg" role="document" style="max-width: 60vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Industri</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="industri_nama">Nama Industri</label>
                        <input type="text" name="industri_nama" id="industri_nama" class="form-control"
                            value="{{ old('industri_nama') }}" required>
                        <small id="error-industri_nama" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                            required>
                        <small id="error-email" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="telepon">Telepon</label>
                        <input type="text" name="telepon" id="telepon" class="form-control"
                            value="{{ old('telepon') }}" required>
                        <small id="error-telepon" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="kota_id">Kota</label>
                        <select name="kota_id" id="kota_id" class="form-control" required>
                            <option value="">-- Pilih Kota --</option>
                            @foreach ($kota as $k)
                                <option value="{{ $k->kota_id }}">{{ $k->kota_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-kota_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="kategori_industri_id">Kategori Industri</label>
                        <select name="kategori_industri_id" id="kategori_industri_id" class="form-control" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($kategori as $c)
                                <option value="{{ $c->kategori_industri_id }}">{{ $c->kategori_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-kategori_industri_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="logo">Logo Industri</label>
                        <input type="file" name="logo" id="logo" class="form-control" accept="image/*">
                        <small id="error-logo" class="error-text form-text text-danger"></small>
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
        $(function() {
            $('#form-create-industri').validate({
                rules: {
                    industri_nama: {
                        required: true,
                        minlength: 3
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    telepon: {
                        required: true,
                        minlength: 6
                    },
                    kota_id: {
                        required: true,
                        number: true
                    },
                    kategori_industri_id: {
                        required: true,
                        number: true
                    }
                },
                submitHandler: function(form) {
                    let formData = new FormData(form);

                    $.ajax({
                        url: form.action,
                        method: form.method,
                        data: formData,
                        processData: false, // penting agar FormData tidak diubah jadi query string
                        contentType: false, // penting agar browser mengatur Content-Type sesuai file
                        success: function(res) {
                            if (res.status) {
                                $('#myModal').modal('hide');
                                Swal.fire('Berhasil', res.message, 'success');
                                dataTable.ajax.reload();
                            } else {
                                $('.error-text').text('');
                                $.each(res.msgField ?? res.errors, function(key, val) {
                                    $('#error-' + key).text(val[0]);
                                });
                                Swal.fire('Gagal', res.message, 'error');
                            }
                        },
                        error: function(xhr) {
                            if (xhr.status === 422) {
                                let errs = xhr.responseJSON.errors;
                                $('.error-text').text('');
                                $.each(errs, (k, v) => {
                                    $('#error-' + k).text(v[0]);
                                });
                            } else {
                                Swal.fire('Error', 'Terjadi kesalahan pada server.',
                                    'error');
                            }
                        }
                    });

                    return false;
                },
                errorElement: 'span',
                errorPlacement: function(error, elem) {
                    error.addClass('invalid-feedback');
                    elem.closest('.form-group').append(error);
                },
                highlight: function(elem) {
                    $(elem).addClass('is-invalid');
                },
                unhighlight: function(elem) {
                    $(elem).removeClass('is-invalid');
                }
            });
        });
    </script>
@endempty
