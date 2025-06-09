<form action="{{ url('/admin/' . $admin->user_id . '/update') }}" method="POST" id="form-edit"enctype="multipart/form-data">
    @csrf
    @method('POST')
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Admin</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                        value="{{ $admin->nama_lengkap }}" required>
                    <small id="error-nama_lengkap" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $admin->email }}"
                        required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                            <label class="form-label">Telepon <span class="text-danger">*</span></label>
                            <input type="text" name="telepon" class="form-control"
                                value="{{ old('telepon', $admin->telepon) }}" pattern="^(\+62|0)[0-9]{8,15}$" title="Masukkan nomor telepon yang valid, contoh: 081234567890">
                            <small id="error-telepon" class="error-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Password <small>(kosongkan jika tak diganti)</small></label>
                    <input type="password" name="password" id="password" class="form-control">
                    <small id="error-password" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Foto (opsional)</label><br>
                    @if ($admin->foto)
                        <img src="{{ asset('storage/foto/' . $admin->foto) }}" alt="Foto Admin"
                            width="100" class="mb-2 rounded">
                    @endif
                    <div class="custom-file">
                        <input type="file" name="foto" id="foto" class="custom-file-input" accept="image/jpeg,image/png,image/jpg">
                        <label class="custom-file-label" for="foto">Pilih file...</label>
                        <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                        <small id="error-foto" class="error-text form-text text-danger"></small>
                    </div>
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
    jQuery.validator.addMethod("phoneID", function (value, element) {
        const cleaned = value.replace(/\D/g, ''); // hanya angka
        return (value.startsWith("0") || value.startsWith("+62")) && cleaned.length >= 9 && cleaned.length <= 15;
    }, "Masukkan nomor telepon yang valid");
    $(document).ready(function() {
    // Show filename when selected
        $('#foto').on('change', function() {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Pilih file...');

            // Simple validation for file size
            if (this.files[0] && this.files[0].size > 2 * 1024 * 1024) {
                $('#error-foto').text('File terlalu besar (maks. 2MB)');
                $(this).val('');
                $(this).next('.custom-file-label').html('Pilih file...');
            } else {
                $('#error-foto').text('');
            }
        });
    $("#form-edit").validate({
        rules: {
            nama_lengkap: {
                required: true,
                minlength: 3
            },
            email: {
                required: true,
                email: true
            },
            telepon: {
                    minlength: 9,
                    maxlength: 15,
                    phoneID: true
            },
            password: {
                minlength: 6
            },
        },
        submitHandler: function(form) {
            let formData = new FormData(form);

            // Debugging the FormData content
            console.log("Form data being sent:");
            for(let pair of formData.entries()) {
                console.log(pair[0] + ': ' + (pair[1] instanceof File ? pair[1].name : pair[1]));
            }

            $.ajax({
                url: form.action,
                type: 'POST',
                data: formData,
                processData: false, // WAJIB: agar FormData tidak diubah jadi string
                contentType: false, // WAJIB: agar browser setting header secara otomatis
                success: function(res) {
                    if (res.status) {
                        $('#myModal').modal('hide');
                        Swal.fire('Berhasil', res.message, 'success');
                        dataAdmin.ajax.reload(null, false);
                    } else {
                        $('.error-text').text('');
                        $.each(res.msgField, function(key, val) {
                            $('#error-' + key).text(val[0]);
                        });
                        Swal.fire('Gagal', res.message, 'error');
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Terjadi kesalahan pada server.', 'error');
                    console.log(xhr.responseText);
                }
            });

            return false;
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element) {
            $(element).removeClass('is-invalid');
        }
    });
});
</script>
