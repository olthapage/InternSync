@empty($kategori)
    <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Data kategori skill tidak tersedia.</h5>
                </div>
                <button class="btn btn-warning" onclick="$('#myModal').modal('hide')">Tutup</button>
            </div>
        </div>
    </div>
@else
    <form id="form-create-skill" action="{{ url('detail-skill.store') }}" method="POST">
        @csrf
        <div class="modal-dialog modal-md" role="document"  style="max-width: 50vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Skill</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group mb-3">
                        <label for="skill_nama">Nama Skill</label>
                        <input type="text"
                               name="skill_nama"
                               id="skill_nama"
                               class="form-control"
                               value="{{ old('skill_nama') }}"
                               required>
                        <small id="error-skill_nama" class="error-text form-text text-danger"></small>
                    </div>

                    <div class="form-group mb-3">
                        <label for="kategori_skill_id">Kategori Skill</label>
                        <select name="kategori_skill_id"
                                id="kategori_skill_id"
                                class="form-control"
                                required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategori as $item)
                                <option value="{{ $item->kategori_skill_id }}">{{ $item->kategori_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-kategori_skill_id" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button"
                            class="btn btn-secondary"
                            onclick="$('#myModal').modal('hide')">Batal</button>
                    <button type="submit"
                            class="btn btn-success">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
    $(function () {
        $('#form-create-skill').validate({
            rules: {
                skill_nama: { required: true, minlength: 2 },
                kategori_skill_id: { required: true, number: true }
            },
            submitHandler: function (form) {
                $.ajax({
                    url: form.action,
                    method: form.method,
                    data: $(form).serialize(),
                    success: function (res) {
                        if (res.status) {
                            $('#ajaxModal').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');
                            dataSkill.ajax.reload();
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
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
