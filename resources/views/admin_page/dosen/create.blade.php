@empty($level)
    <div id="modal-master" class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kesalahan</h5>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <h5><i class="icon fas fa-ban"></i> Kesalahan!!!</h5>
                    Data level tidak tersedia.
                </div>
                <a href="{{ url('/dosen') }}" class="btn btn-warning">Kembali</a>
            </div>
        </div>
    </div>
@else
    <form action="{{ route('dosen.store') }}" method="POST" id="form-create">
        @csrf
        <div id="modal-master" class="modal-dialog modal-lg" role="document" style="max-width:60vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Dosen</h5>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                               value="{{ old('nama_lengkap') }}" required>
                        <small id="error-nama_lengkap" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                               value="{{ old('email') }}" required>
                        <small id="error-email" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                            <label>Telepon</label>
                            <input type="text" name="telepon" class="form-control" 
                                value="{{ old('telepon') }}" required pattern="^(\+62|0)[0-9]{8,15}$" title="Masukkan nomor telepon yang valid, contoh: 081234567890">
                            <small id="error-telepon" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" id="password" class="form-control" required>
                        <small id="error-password" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>NIP</label>
                        <input type="text" name="nip" id="nip" class="form-control"
                               value="{{ old('nip') }}" required>
                        <small id="error-nip" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Level</label>
                        <select name="level_id" id="level_id" class="form-control" required>
                            <option value="">-- Pilih Level --</option>
                            @foreach($level as $lvl)
                                <option value="{{ $lvl->level_id }}">{{ $lvl->level_nama }}</option>
                            @endforeach
                        </select>
                        <small id="error-level_id" class="error-text form-text text-danger"></small>
                    </div>
                    <div class="form-group">
                        <label>Program Studi</label>
                        <select name="prodi_id" id="prodi_id" class="form-control" required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach($prodi as $prd)
                                <option value="{{ $prd->prodi_id }}">{{ $prd->nama_prodi }}</option>
                            @endforeach
                        </select>
                        <small id="error-prodi_id" class="error-text form-text text-danger"></small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            onclick="$('#myModal').modal('hide')">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </form>

    <script>
    $(document).ready(function () {
        $("#form-create").validate({
            rules: {
                nama_lengkap: { required: true, minlength: 3 },
                email:        { required: true, email: true },
                telepon:      { required: true, minlength: 9, maxlength: 15}, 
                password:     { required: true, minlength: 6 },
                nip:          { required: true },
                level_id:     { required: true, number: true },
                prodi_id:     { required: true, number: true }
            },
            submitHandler: function (form) {
                $.ajax({
                    url:    form.action,
                    type:   form.method,
                    data:   $(form).serialize(),
                    success: function (res) {
                        if (res.status) {
                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil', res.message, 'success');
                            dataDosen.ajax.reload();
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
