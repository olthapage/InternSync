<form action="{{ url('/dosen/' . $dosen->dosen_id . '/update') }}" method="POST" id="form-edit" enctype="multipart/form-data">
    @csrf
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Dosen</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                        value="{{ $dosen->nama_lengkap }}" required>
                    <small id="error-nama_lengkap" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" id="email" class="form-control"
                        value="{{ $dosen->email }}" required>
                    <small id="error-email" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>NIP</label>
                    <input type="text" name="nip" id="nip" class="form-control"
                        value="{{ $dosen->nip }}" required>
                    <small id="error-nip" class="error-text form-text text-danger"></small>
                </div>

                <!-- New Photo Upload Field -->
                <div class="form-group">
                    <label>Foto (opsional)</label><br>
                    @if ($dosen->foto)
                        <img src="{{ asset('storage/foto/' . $dosen->foto) }}" alt="Foto Dosen"
                            width="100" class="mb-2 rounded">
                    @endif
                    <div class="custom-file">
                        <input type="file" name="foto" id="foto" class="custom-file-input" accept="image/jpeg,image/png,image/jpg">
                        <label class="custom-file-label" for="foto">Pilih file...</label>
                        <small class="form-text text-muted">Format: JPG, JPEG, PNG. Maksimal 2MB.</small>
                        <small id="error-foto" class="error-text form-text text-danger"></small>
                    </div>
                </div>

                <div class="form-group">
                    <label>Level</label>
                    <select name="level_id" id="level_id" class="form-control" required>
                        <option value="">-- Pilih Level --</option>
                        @foreach($level as $lvl)
                            <option value="{{ $lvl->level_id }}" {{ $dosen->level_id == $lvl->level_id ? 'selected' : '' }}>
                                {{ $lvl->level_nama }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-level_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Program Studi</label>
                    <select name="prodi_id" id="prodi_id" class="form-control" required>
                        <option value="">-- Pilih Prodi --</option>
                        @foreach($prodi as $prd)
                            <option value="{{ $prd->prodi_id }}" {{ $dosen->prodi_id == $prd->prodi_id ? 'selected' : '' }}>
                                {{ $prd->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                    <small id="error-prodi_id" class="error-text form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="row g-2">
                        <div class="col-9">
                            <input type="password" class="form-control" id="password" value="********" disabled>
                        </div>
                        <div class="col-3">
                            <button type="button" class="btn btn-danger" id="btn-reset-password">Reset Password</button>
                        </div>
                    </div>
                    <input type="hidden" name="reset_password" id="reset_password" value="0">
                    <small id="error-reset_password" class="error-text form-text text-danger"></small>
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
                nip: {
                    required: true
                },
                level_id: {
                    required: true,
                    number: true
                },
                prodi_id: {
                    required: true,
                    number: true
                }
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
                    type: form.method,
                    data: formData,
                    processData: false, // Required for FormData
                    contentType: false, // Required for FormData
                    success: function(res) {
                        if (res.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');
                            dataDosen.ajax.reload(null, false);
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

        $('#btn-reset-password').click(function() {
            Swal.fire({
                title: 'Reset Password?',
                text: 'Password akan diset ulang.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, reset!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#reset_password').val(1);
                    Swal.fire('Password Diset Ulang', 'Password akan direset saat disimpan.', 'info');
                }
            });
        });
    });
</script>
