<div class="modal-dialog modal-xl">
    <div class="modal-content">
        <form id="formVerifikasi" action="{{ url('/mahasiswa/' . $mahasiswa->mahasiswa_id . '/verifikasi') }}">
            @csrf
            @method('PUT')

            <div class="modal-header">
                <h5 class="modal-title">Verifikasi Data Mahasiswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3 border">
                            <div class="card-header bg-white text-white border-bottom">
                                <h6 class="mb-0">Informasi Dasar</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong>Nama Lengkap:</strong> {{ $mahasiswa->nama_lengkap }}
                                </div>
                                <div class="mb-2">
                                    <strong>NIM:</strong> {{ $mahasiswa->nim }}
                                </div>
                                <div class="mb-2">
                                    <strong>Email:</strong> {{ $mahasiswa->email }}
                                </div>
                                <div class="mb-2">
                                    <strong>IPK:</strong> {{ $mahasiswa->ipk }}
                                </div>
                                <div class="mb-2">
                                    <strong>Program Studi:</strong> {{ $mahasiswa->prodi->nama_prodi ?? 'Belum diisi' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Dosen Pembimbing:</strong> {{ $mahasiswa->dosen->nama_lengkap ?? 'Belum dipilih' }}
                                </div>
                                <div class="mb-2">
                                    <strong>Status:</strong>
                                    <span class="badge {{ $mahasiswa->status == 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                        {{ ucfirst($mahasiswa->status) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="card mb-3 border">
                            <div class="card-header bg-white text-white border-bottom">
                                <h6 class="mb-0">Skill dan Preferensi</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <strong>Skill:</strong>
                                    @if($mahasiswa->skill && $mahasiswa->skill->count() > 0)
                                        <ul class="list-group">
                                            @foreach($mahasiswa->skill as $skill)
                                                <li class="list-group-item">{{ $skill->nama_skill }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted">Belum ada skill yang ditambahkan</p>
                                    @endif
                                </div>
                                <div class="mb-2">
                                    <strong>Preferensi Lokasi:</strong>
                                    @if($mahasiswa->preferensiLokasi && $mahasiswa->preferensiLokasi->count() > 0)
                                        <ul class="list-group">
                                            @foreach($mahasiswa->preferensiLokasi as $lokasi)
                                                <li class="list-group-item">{{ $lokasi->nama_lokasi }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted">Belum ada preferensi lokasi yang ditambahkan</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card mb-3 border">
                            <div class="card-header bg-white text-white border-bottom">
                                <h6 class="mb-0">Foto dan Identitas</h6>
                            </div>
                            <div class="card-body text-center">
                                @if($mahasiswa->foto)
                                    <img src="{{ Storage::url($mahasiswa->foto) }}" class="img-thumbnail mb-3" style="max-height: 150px;" alt="Foto Mahasiswa">
                                @else
                                    <div class="alert alert-light">Foto profil belum diunggah</div>
                                @endif
                            </div>
                        </div>

                        <div class="card mb-3 border">
                            <div class="card-header bg-white text-white border-bottom">
                                <h6 class="mb-0">Dokumen Pendukung</h6>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <tbody>
                                        <tr>
                                            <td>Sertifikat Kompetensi</td>
                                            <td>
                                                @if($mahasiswa->sertifikat_kompetensi)
                                                    <a href="{{ Storage::url($mahasiswa->sertifikat_kompetensi) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger">Belum Ada</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Pakta Integritas</td>
                                            <td>
                                                @if($mahasiswa->pakta_integritas)
                                                    <a href="{{ Storage::url($mahasiswa->pakta_integritas) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger">Belum Ada</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>CV/Daftar Riwayat Hidup</td>
                                            <td>
                                                @if($mahasiswa->daftar_riwayat_hidup)
                                                    <a href="{{ Storage::url($mahasiswa->daftar_riwayat_hidup) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger">Belum Ada</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>KHS</td>
                                            <td>
                                                @if($mahasiswa->khs)
                                                    <a href="{{ Storage::url($mahasiswa->khs) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger">Belum Ada</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>KTP</td>
                                            <td>
                                                @if($mahasiswa->ktp)
                                                    <a href="{{ Storage::url($mahasiswa->ktp) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger">Belum Ada</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>KTM</td>
                                            <td>
                                                @if($mahasiswa->ktm)
                                                    <a href="{{ Storage::url($mahasiswa->ktm) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger">Belum Ada</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Surat Izin Orang Tua</td>
                                            <td>
                                                @if($mahasiswa->surat_izin_ortu)
                                                    <a href="{{ Storage::url($mahasiswa->surat_izin_ortu) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger">Belum Ada</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>BPJS</td>
                                            <td>
                                                @if($mahasiswa->bpjs)
                                                    <a href="{{ Storage::url($mahasiswa->bpjs) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger">Belum Ada</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>SKTM/KIP</td>
                                            <td>
                                                @if($mahasiswa->sktm_kip)
                                                    <a href="{{ Storage::url($mahasiswa->sktm_kip) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger">Belum Ada</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Proposal</td>
                                            <td>
                                                @if($mahasiswa->proposal)
                                                    <a href="{{ Storage::url($mahasiswa->proposal) }}" target="_blank" class="btn btn-sm btn-info">
                                                        <i class="fa fa-eye"></i> Lihat
                                                    </a>
                                                @else
                                                    <span class="badge bg-danger">Belum Ada</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4 border">
                    <div class="card-header bg-white text-white border-bottom">
                        <h6 class="mb-0">Status Verifikasi</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_verifikasi" id="valid" value="valid"
                                    {{ $mahasiswa->status_verifikasi === 'valid' ? 'checked' : '' }}>
                                <label class="form-check-label" for="valid">Valid</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status_verifikasi" id="invalid" value="invalid"
                                    {{ $mahasiswa->status_verifikasi === 'invalid' ? 'checked' : '' }}>
                                <label class="form-check-label" for="invalid">Invalid</label>
                            </div>
                        </div>

                        <div class="form-group mt-3" id="alasanGroup" style="{{ $mahasiswa->status_verifikasi === 'invalid' ? '' : 'display: none;' }}">
                            <label>Alasan Penolakan</label>
                            <textarea name="alasan" class="form-control" rows="3" placeholder="Masukkan alasan penolakan...">{{ $mahasiswa->alasan }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn btn-success">
                    <i class="fa fa-save"></i> Simpan Perubahan
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i> Batal
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    $(function() {
        // Tampilkan/ Sembunyikan alasan penolakan
        $('input[name="status_verifikasi"]').on('change', function() {
            if ($(this).val() === 'invalid') {
                $('#alasanGroup').show();
            } else {
                $('#alasanGroup').hide();
                $('textarea[name="alasan"]').val(''); // Kosongkan alasan jika status bukan invalid
            }
        });

        // Form submission dengan AJAX
        $('#formVerifikasi').on('submit', function(e) {
            e.preventDefault();
            let url = $(this).attr('action');
            let data = $(this).serialize();

            // Validasi jika status invalid tapi alasan kosong
            if ($('input[name="status_verifikasi"]:checked').val() === 'invalid' && !$('textarea[name="alasan"]').val().trim()) {
                Swal.fire('Perhatian', 'Harap isi alasan penolakan jika status invalid', 'warning');
                return false;
            }

            // Konfirmasi sebelum submit
            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menyimpan status verifikasi ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Lakukan AJAX request
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: data,
                        beforeSend: function() {
                            $('button[type="submit"]').prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');
                        },
                        success: function(res) {
                            if (res.success) {
                                $('#myModal').modal('hide');
                                Swal.fire('Berhasil!', res.message, 'success');
                                if (typeof table !== 'undefined') {
                                    table.ajax.reload(); // reload table jika variabel table sudah didefinisikan
                                } else if ($('#datatable').length) {
                                    $('#datatable').DataTable().ajax.reload(); // reload table jika pakai DataTable
                                } else {
                                    // Jika tidak ada DataTable, reload halaman
                                    setTimeout(function() {
                                        window.location.reload();
                                    }, 1500);
                                }
                            } else {
                                Swal.fire('Peringatan', res.message || 'Terjadi kesalahan', 'warning');
                            }
                        },
                        error: function(xhr) {
                            let errorMessage = 'Terjadi kesalahan saat memproses.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            }
                            Swal.fire('Error', errorMessage, 'error');
                        },
                        complete: function() {
                            $('button[type="submit"]').prop('disabled', false).html('<i class="fa fa-save"></i> Simpan Perubahan');
                        }
                    });
                }
            });
        });
    });
</script>
