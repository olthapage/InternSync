{{-- resources/views/admin_page/mahasiswa/verifikasi.blade.php --}}
<form id="formVerifikasi-{{ $akun->id }}"
      action="{{ route('validasi-akun.validate', $akun->id) }}"
      method="POST">
    @csrf
    @method('PUT')

    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Data Akun: {{ $akun->nama_lengkap }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="verifikasiAlertContainer-{{ $akun->id }}"></div>

                {{-- Informasi Dasar --}}
                <h6>Informasi Dasar:</h6>
                <table class="table table-sm table-borderless table-hover mb-4">
                    <tr><td width="35%"><strong>Nama Lengkap</strong></td><td>: {{ $akun->nama_lengkap }}</td></tr>
                    <tr><td><strong>NIM/NIDN</strong></td><td>: {{ $akun->username }}</td></tr>
                    <tr><td><strong>Email</strong></td><td>: {{ $akun->email }}</td></tr>
                    <tr><td><strong>Role</strong></td><td>: {{ $akun->perkiraan_role }}</td></tr>
                    <tr><td><strong>Status</strong></td><td>: {{ $akun->status_validasi }}</td></tr>
                </table>

                {{-- Form Verifikasi Admin --}}
                <h6 class="text-primary">Form Verifikasi Admin:</h6>
                <div class="form-group mb-3">
                    <label for="status_validasi_{{ $akun->id }}" class="form-label">Status Verifikasi Akun <span class="text-danger">*</span></label>
                    <select name="status_validasi" id="status_validasi_{{ $akun->id }}" class="form-select form-select-sm" required>
                        <option value="pending" {{ old('status_validasi', $akun->status_validasi) == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="approved" {{ old('status_validasi', $akun->status_validasi) == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ old('status_validasi', $akun->status_validasi) == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <small id="error-status_validasi-{{$akun->id}}" class="error-text text-danger d-block"></small>
                </div>

                @if ($akun->perkiraan_role === 'dosen')
                <div class="form-group mb-3" id="roleDosenGroup_{{ $akun->id }}">
                    <label for="role_dosen_{{ $akun->id }}" class="form-label">Tentukan Role Dosen <span class="text-danger">*</span></label>
                    <select name="role_dosen" id="role_dosen_{{ $akun->id }}" class="form-select form-select-sm">
                        <option value="">-- Pilih Role --</option>
                        <option value="pembimbing">Dosen Pembimbing</option>
                        <option value="dpa">Dosen Pembimbing Akademik (DPA)</option>
                    </select>
                    <small id="error-role_dosen-{{$akun->id}}" class="error-text text-danger d-block"></small>
                </div>
                @endif

                <div class="form-group mb-3" id="alasanGroup_{{ $akun->id }}" style="display: none;">
                    <label for="alasan_{{ $akun->id }}" class="form-label">Alasan Penolakan <span class="text-danger">*</span></label>
                    <textarea name="alasan" id="alasan_{{ $akun->id }}" class="form-control form-control-sm" rows="3" placeholder="Tuliskan alasan penolakan akun"></textarea>
                    <small id="error-alasan-{{ $akun->id }}" class="error-text text-danger d-block"></small>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i> Batal
                </button>
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-save me-1"></i> Simpan Verifikasi
                </button>
            </div>
        </div>
    </div>
</form>

<script>
$(function () {
    const akunId = '{{ $akun->id }}';
    const statusValidasiSelect = $('#status_validasi_' + akunId);
    const alasanGroupDiv = $('#alasanGroup_' + akunId);
    const alasanTextarea = $('#alasan_' + akunId);

    function toggleAlasan() {
        if (statusValidasiSelect.val() === 'rejected') {
            alasanGroupDiv.slideDown();
            alasanTextarea.prop('required', true);
        } else {
            alasanGroupDiv.slideUp();
            alasanTextarea.val('');
            alasanTextarea.prop('required', false);
        }
    }

    statusValidasiSelect.on('change', toggleAlasan);
    toggleAlasan(); // inisialisasi awal

    $('#formVerifikasi-' + akunId).on('submit', function (e) {
        e.preventDefault();

        const form = this;
        const url = $(form).attr('action');
        const formData = new FormData(form);
        formData.append('_method', 'PUT');

        const submitButton = $(form).find('button[type="submit"]');
        const originalButtonText = submitButton.html();
        const alertContainer = $('#verifikasiAlertContainer-' + akunId);

        alertContainer.html('').hide();
        $('.error-text', form).text('');
        $('.is-invalid', form).removeClass('is-invalid');

        // Validasi client side alasan penolakan
        if (statusValidasiSelect.val() === 'rejected' && !alasanTextarea.val().trim()) {
            Swal.fire('Perhatian!', 'Harap isi alasan penolakan jika status verifikasi adalah "rejected".', 'warning');
            alasanTextarea.addClass('is-invalid').focus();
            $('#error-alasan-' + akunId).text('Alasan penolakan wajib diisi.');
            return false;
        }

        Swal.fire({
            title: 'Konfirmasi Simpan',
            text: "Anda yakin ingin menyimpan perubahan verifikasi ini?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Simpan!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                submitButton.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...');

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        if (res.status) {  // sesuaikan dengan response controller

                            $('#myModal').modal('hide');
                            Swal.fire('Berhasil!', res.message, 'success');
                            dataAkun.ajax.reload();
                            if (typeof dataTableInstance !== 'undefined') {
                                dataTableInstance.ajax.reload(null, false);
                            } else if (typeof dataMhs !== 'undefined' && dataMhs.ajax) {
                                dataMhs.ajax.reload(null, false);
                            } else {
                                console.warn('DataTable instance tidak ditemukan.');
                            }
                        } else {
                            let errorsHtml = '<ul class="mb-0 ps-3">';
                            if (res.errors) {
                                $.each(res.errors, function (key, value) {
                                    errorsHtml += '<li>' + value[0] + '</li>';
                                    let inputField = $('[name="' + key + '"]', form);
                                    inputField.addClass('is-invalid');

                                    // perbaiki selector error message
                                    let errorSelector = $('#error-' + key.replace(/\./g, '_') + '-' + akunId);
                                    if (errorSelector.length) {
                                        errorSelector.text(value[0]);
                                    }
                                });
                            }
                            errorsHtml += '</ul>';
                            alertContainer.html('<div class="alert alert-danger py-2">' + (res.message || 'Validasi gagal') + errorsHtml + '</div>').slideDown();
                            Swal.fire('Gagal', res.message || 'Terjadi kesalahan validasi.', 'error');
                        }
                    },
                    error: function (xhr) {
                        alertContainer.html('<div class="alert alert-danger py-2">Terjadi kesalahan saat menyimpan data.</div>').slideDown();
                        Swal.fire('Gagal', 'Terjadi kesalahan sistem.', 'error');
                    },
                    complete: function () {
                        submitButton.prop('disabled', false).html(originalButtonText);
                    }
                });
            }
        });
    });
});
</script>
