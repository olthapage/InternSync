    <form action="{{ route('mahasiswa.store') }}" method="POST" id="form-create-mahasiswa">
        @csrf
        <div id="modal-master" class="modal-dialog modal-lg" role="document" style="max-width:60vw;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Mahasiswa</h5>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Kiri --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control"
                                       value="{{ old('nama_lengkap') }}" required>
                                <small id="error-nama_lengkap" class="error-text text-danger"></small>
                            </div>
                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                       value="{{ old('email') }}" required>
                                <small id="error-email" class="error-text text-danger"></small>
                            </div>
                            <div class="form-group mb-3">
                                <label>Password</label>
                                <input type="password" name="password" id="password" class="form-control" required>
                                <small id="error-password" class="error-text text-danger"></small>
                            </div>
                            <div class="form-group mb-3">
                                <label>NIM</label>
                                <input type="text" name="nim" id="nim" class="form-control"
                                       value="{{ old('nim') }}" required>
                                <small id="error-nim" class="error-text text-danger"></small>
                            </div>
                            <div class="form-group mb-3">
                                <label>IPK</label>
                                <input type="number" step="0.01" name="ipk" id="ipk" class="form-control"
                                       value="{{ old('ipk') }}">
                                <small id="error-ipk" class="error-text text-danger"></small>
                            </div>
                        </div>
                        {{-- Kanan --}}
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Status Magang</label>
                                <select name="status" id="status" class="form-select" required>
                                    <option value="">-- Pilih Status --</option>
                                    <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>Sudah Magang</option>
                                    <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>Belum Magang</option>
                                </select>
                                <small id="error-status" class="error-text text-danger"></small>
                            </div>
                            <div class="form-group mb-3">
                                <label>Program Studi</label>
                                <select name="prodi_id" id="prodi_id" class="form-select" required>
                                    <option value="">-- Pilih Prodi --</option>
                                    @foreach($prodi as $p)
                                        <option value="{{ $p->prodi_id }}"
                                            {{ old('prodi_id') == $p->prodi_id ? 'selected' : '' }}>
                                            {{ $p->nama_prodi }}
                                        </option>
                                    @endforeach
                                </select>
                                <small id="error-prodi_id" class="error-text text-danger"></small>
                            </div>
                            <div class="form-group mb-3">
                                <label>Dosen Pembimbing</label>
                                <select name="dosen_id" id="dosen_id" class="form-select">
                                    <option value="">-- Tidak Ada --</option>
                                    @foreach($dosen as $d)
                                        <option value="{{ $d->dosen_id }}"
                                            {{ old('dosen_id') == $d->dosen_id ? 'selected' : '' }}>
                                            {{ $d->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                                <small id="error-dosen_id" class="error-text text-danger"></small>
                            </div>
                        </div>
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
        $("#form-create-mahasiswa").validate({
            rules: {
                nama_lengkap: { required: true, minlength: 3 },
                email:        { required: true, email: true },
                password:     { required: true, minlength: 6 },
                nim:          { required: true },
                ipk:          { number: true, min: 0, max: 4 },
                status:       { required: true },
                prodi_id:     { required: true, number: true },
                dosen_id:     { number: true }
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
                            dataMhs.ajax.reload();
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
