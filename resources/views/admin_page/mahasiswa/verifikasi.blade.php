{{-- resources/views/admin_page/mahasiswa/verifikasi.blade.php --}}
<form id="formVerifikasi-{{ $mahasiswa->mahasiswa_id }}"
      action="{{ route('mahasiswa.verifikasi', $mahasiswa->mahasiswa_id) }}" {{-- Pastikan nama route ini benar --}}
      method="PUT">
    @csrf
    @method('PUT')

    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Data Mahasiswa: {{ $mahasiswa->nama_lengkap }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <div id="verifikasiAlertContainer-{{ $mahasiswa->mahasiswa_id }}"></div>
                <div class="row">
                    {{-- Kolom Kiri: Info Mahasiswa & Dokumen --}}
                    <div class="col-md-7 border-end pe-md-3">
                        <h6>Informasi Dasar:</h6>
                        <table class="table table-sm table-borderless table-hover mb-3">
                            <tr><td width="35%"><strong>Nama Lengkap</strong></td><td>: {{ $mahasiswa->nama_lengkap }}</td></tr>
                            <tr><td><strong>NIM</strong></td><td>: {{ $mahasiswa->nim }}</td></tr>
                            <tr><td><strong>Email</strong></td><td>: {{ $mahasiswa->email }}</td></tr>
                            <tr><td><strong>IPK</strong></td><td>: {{ $mahasiswa->ipk ?? '-' }}</td></tr>
                            <tr><td><strong>Prodi</strong></td><td>: {{ optional($mahasiswa->prodi)->nama_prodi ?? 'N/A' }}</td></tr>
                            <tr><td><strong>DPA</strong></td><td>: {{ optional($mahasiswa->dpa)->nama_lengkap ?? 'Belum diatur' }}</td></tr>
                        </table>

                        <h6 class="mt-3">Aktivitas Non-Akademik:</h6>
                        <table class="table table-sm table-borderless table-hover mb-3">
                            <tr>
                                <td width="35%"><strong>Organisasi</strong></td>
                                <td>:
                                    @if(optional($mahasiswa)->organisasi == 'tidak_ikut') Belum/Tidak Ikut
                                    @elseif(optional($mahasiswa)->organisasi == 'aktif') Aktif
                                    @elseif(optional($mahasiswa)->organisasi == 'sangat_aktif') Sangat Aktif
                                    @else - @endif
                                </td>
                            </tr>
                             <tr>
                                <td><strong>Lomba/Kompetisi</strong></td>
                                <td>:
                                    @if(optional($mahasiswa)->lomba == 'tidak_ikut') Belum/Tidak Ikut
                                    @elseif(optional($mahasiswa)->lomba == 'aktif') Pernah Ikut/Finalis
                                    @elseif(optional($mahasiswa)->lomba == 'sangat_aktif') Sering Ikut & Juara
                                    @else - @endif
                                </td>
                            </tr>
                        </table>

                        <h6 class="mt-3">Dokumen Pendukung:</h6>
                        <div class="list-group list-group-flush">
                            @php
                                $dokumenFields = [
                                    'sertifikat_kompetensi' => 'Sertifikat Kompetensi',
                                    'sertifikat_organisasi' => 'Sertifikat Organisasi', // BARU
                                    'sertifikat_lomba'      => 'Sertifikat Lomba',      // BARU
                                    'pakta_integritas'      => 'Pakta Integritas',
                                    'daftar_riwayat_hidup'  => 'CV/Daftar Riwayat Hidup',
                                    'khs'                   => 'KHS',
                                    'ktp'                   => 'KTP',
                                    'ktm'                   => 'KTM',
                                    'surat_izin_ortu'       => 'Surat Izin Orang Tua',
                                    'bpjs'                  => 'BPJS/Asuransi',
                                    'sktm_kip'              => 'SKTM/KIP',
                                    'proposal'              => 'Proposal Magang',
                                ];
                            @endphp
                            @foreach($dokumenFields as $field => $label)
                                <div class="list-group-item list-group-item-light py-2 px-3 d-flex justify-content-between align-items-center">
                                    <span>
                                        @if(optional($mahasiswa)->$field)
                                            <i class="fas fa-check-circle fa-fw me-2 text-success"></i>
                                        @else
                                            <i class="fas fa-times-circle fa-fw me-2 text-danger"></i>
                                        @endif
                                        {{ $label }}
                                    </span>
                                    @if(optional($mahasiswa)->$field)
                                        <a href="{{ Storage::url($mahasiswa->$field) }}" target="_blank" class="btn btn-sm btn-outline-primary py-0 px-2" title="Lihat {{ $label }}">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">Belum Ada</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Kolom Kanan: Form Verifikasi Admin --}}
                    <div class="col-md-5 ps-md-3">
                        <h6 class="text-primary">Form Verifikasi Admin:</h6>
                        <div class="form-group mb-3">
                            <label for="status_verifikasi_{{ $mahasiswa->mahasiswa_id }}" class="form-label">Status Verifikasi Profil <span class="text-danger">*</span></label>
                            <select name="status_verifikasi" id="status_verifikasi_{{ $mahasiswa->mahasiswa_id }}" class="form-select form-select-sm" required>
                                <option value="pending" {{ old('status_verifikasi', $mahasiswa->status_verifikasi) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="valid" {{ old('status_verifikasi', $mahasiswa->status_verifikasi) == 'valid' ? 'selected' : '' }}>Valid</option>
                                <option value="invalid" {{ old('status_verifikasi', $mahasiswa->status_verifikasi) == 'invalid' ? 'selected' : '' }}>Invalid</option>
                            </select>
                            <small id="error-status_verifikasi-{{$mahasiswa->mahasiswa_id}}" class="error-text text-danger d-block"></small>
                        </div>

                        <div class="form-group mb-3" id="alasanGroup_{{ $mahasiswa->mahasiswa_id }}" style="{{ (old('status_verifikasi', $mahasiswa->status_verifikasi) ?? $mahasiswa->status_verifikasi) === 'invalid' ? '' : 'display: none;' }}">
                            <label for="alasan_{{ $mahasiswa->mahasiswa_id }}" class="form-label">Alasan Penolakan (jika Invalid) <span class="text-danger">*</span></label>
                            <textarea name="alasan" id="alasan_{{ $mahasiswa->mahasiswa_id }}" class="form-control form-control-sm" rows="3" placeholder="Masukkan alasan penolakan verifikasi...">{{ old('alasan', $mahasiswa->alasan) }}</textarea>
                            <small id="error-alasan-{{$mahasiswa->mahasiswa_id}}" class="error-text text-danger d-block"></small>
                        </div>

                        <hr>
                        <h6 class="mt-3">Data Tambahan (Input Admin):</h6>
                        <div class="form-group mb-3">
                            <label for="skor_ais_{{ $mahasiswa->mahasiswa_id }}" class="form-label">Skor AIS (0-1000)</label>
                            <input type="number" name="skor_ais" id="skor_ais_{{ $mahasiswa->mahasiswa_id }}" class="form-control form-control-sm"
                                   value="{{ old('skor_ais', optional($mahasiswa)->skor_ais) }}" min="0" max="1000" placeholder="Contoh: 750">
                            <small id="error-skor_ais-{{$mahasiswa->mahasiswa_id}}" class="error-text text-danger d-block"></small>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">Memiliki Kasus Pelanggaran? <span class="text-danger">*</span></label>
                            <div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kasus" id="kasus_tidak_ada_{{ $mahasiswa->mahasiswa_id }}" value="tidak_ada"
                                           {{ (old('kasus', optional($mahasiswa)->kasus) ?? 'tidak_ada') == 'tidak_ada' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="kasus_tidak_ada_{{ $mahasiswa->mahasiswa_id }}">Tidak Ada</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kasus" id="kasus_ada_{{ $mahasiswa->mahasiswa_id }}" value="ada"
                                           {{ old('kasus', optional($mahasiswa)->kasus) == 'ada' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="kasus_ada_{{ $mahasiswa->mahasiswa_id }}">Ada Kasus</label>
                                </div>
                            </div>
                             <small id="error-kasus-{{$mahasiswa->mahasiswa_id}}" class="error-text text-danger d-block"></small>
                        </div>
                    </div>
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
    // Skrip ini akan dijalankan setiap kali modal dimuat
    $(function() {
        const mahasiswaId = '{{ $mahasiswa->mahasiswa_id }}';
        const statusVerifikasiSelect = $('#status_verifikasi_' + mahasiswaId);
        const alasanGroupDiv = $('#alasanGroup_' + mahasiswaId);
        const alasanTextarea = $('textarea[name="alasan"]', alasanGroupDiv);

        function toggleAlasan() {
            if (statusVerifikasiSelect.val() === 'invalid') {
                alasanGroupDiv.slideDown();
                alasanTextarea.prop('required', true);
            } else {
                alasanGroupDiv.slideUp();
                alasanTextarea.val('');
                alasanTextarea.prop('required', false);
            }
        }

        statusVerifikasiSelect.on('change', toggleAlasan);
        toggleAlasan(); // Panggil saat load

        $('#formVerifikasi-' + mahasiswaId).on('submit', function(e) {
            e.preventDefault();
            let form = this;
            let url = $(form).attr('action');
            let formData = new FormData(form);
            let submitButton = $(form).find('button[type="submit"]');
            let originalButtonText = submitButton.html();
            let alertContainer = $('#verifikasiAlertContainer-' + mahasiswaId);

            alertContainer.html('').hide();
            $('.error-text', form).text(''); // Clear previous specific errors
            $('.is-invalid', form).removeClass('is-invalid');


            if (statusVerifikasiSelect.val() === 'invalid' && !alasanTextarea.val().trim()) {
                Swal.fire('Perhatian!', 'Harap isi alasan penolakan jika status verifikasi adalah "Invalid".', 'warning');
                alasanTextarea.addClass('is-invalid').focus();
                $('#error-alasan-' + mahasiswaId).text('Alasan penolakan wajib diisi.');
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
                        success: function(res) {
                            if (res.success) {
                                $('#myModal').modal('hide');
                                Swal.fire('Berhasil!', res.message, 'success');
                                if (typeof dataTableInstance !== 'undefined') {
                                    dataTableInstance.ajax.reload(null, false);
                                } else if (typeof dataMhs !== 'undefined' && dataMhs.ajax) {
                                     dataMhs.ajax.reload(null, false);
                                } else {
                                    console.warn('DataTable instance tidak ditemukan untuk reload.');
                                    // Pertimbangkan untuk reload halaman jika tidak ada DataTables
                                    // setTimeout(function(){ window.location.reload(); }, 1500);
                                }
                            } else {
                                let errorsHtml = '<ul class="mb-0 ps-3">';
                                if (res.errors) {
                                    $.each(res.errors, function(key, value) {
                                        errorsHtml += '<li>' + value[0] + '</li>';
                                        $('[name="' + key + '"]', form).addClass('is-invalid');
                                        $('#error-' + key + '-' + mahasiswaId).text(value[0]);
                                    });
                                }
                                errorsHtml += '</ul>';
                                alertContainer.html('<div class="alert alert-danger py-2">' + (res.message || 'Validasi gagal') + errorsHtml + '</div>').slideDown();
                                Swal.fire('Gagal', res.message || 'Terjadi kesalahan validasi.', 'error');
                            }
                        },
                        error: function(xhr) {
                            // ... (error handling AJAX Anda yang sudah ada) ...
                        },
                        complete: function() {
                            submitButton.prop('disabled', false).html('<i class="fas fa-save me-1"></i> Simpan Verifikasi');
                        }
                    });
                }
            });
        });
    });
</script>
