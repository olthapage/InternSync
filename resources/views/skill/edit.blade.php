<form action="{{ url('detail-skill/' . $detail->skill_id . '/update') }}" method="POST" id="form-edit">
    @csrf
    <div class="modal-dialog modal-md" role="document" style="max-width: 50vw;">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Skill</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="skill_nama">Nama Skill</label>
                    <input type="text" class="form-control" name="skill_nama" id="skill_nama"
                        value="{{ $detail->skill_nama }}" required>
                    <small id="error-skill_nama" class="error-text form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="kategori_skill_id">Kategori Skill</label>
                    <select name="kategori_skill_id" id="kategori_skill_id" class="form-control" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($kategori as $item)
                            <option value="{{ $item->kategori_skill_id }}"
                                {{ $item->kategori_skill_id == $detail->kategori_skill_id ? 'selected' : '' }}>
                                {{ $item->kategori_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-kategori_skill_id" class="error-text form-text text-danger"></small>
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
                skill_nama: { required: true, minlength: 2 },
                kategori_skill_id: { required: true }
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
                            dataSkill.ajax.reload();
                        } else {
                            $('.error-text').text('');
                            $.each(res.msgField, function (key, val) {
                                $('#error-' + key).text(val[0]);
                            });
                            Swal.fire('Gagal', res.message, 'error');
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
