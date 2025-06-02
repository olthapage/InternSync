@extends('layouts.template')

@section('title', 'Kelola Magang Mahasiswa - ' . ($magang->mahasiswa->nama_lengkap ?? 'Mahasiswa'))

@push('css')
    <style>
        .progress-bar-label {
            font-size: 0.9em;
            font-weight: 600;
            color: #343a40;
        }

        .log-item {
            border-left: 3px solid #007bff;
            /* Garis biru di kiri */
            padding-left: 15px;
            margin-bottom: 15px;
        }

        .log-item.approved {
            border-left-color: #28a745;
            /* Hijau untuk disetujui */
        }

        .log-item.rejected {
            border-left-color: #dc3545;
            /* Merah untuk ditolak */
        }

        .log-item.pending {
            border-left-color: #ffc107;
            /* Kuning untuk pending */
        }

        .log-actions .btn {
            margin-top: 5px;
        }

        .card-log .card-header {
            background-color: #f8f9fa;
        }

        .catatan-box {
            background-color: #e9ecef;
            border-radius: .25rem;
            padding: .5rem .75rem;
            font-size: .875em;
            margin-top: .5rem;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="row">
            {{-- Kolom Kiri: Info Mahasiswa & Kontrol Status --}}
            <div class="col-lg-4 col-md-5 mb-4">
                <div class="card shadow-sm sticky-top" style="top: 20px;">
                    <div class="card-header py-3 border-bottom">
                        <h5 class="mb-0 text-dark-blue">
                            <i class="fas fa-user-graduate me-2"></i>Profil Mahasiswa Magang
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            <img src="{{ $magang->mahasiswa->foto ? asset('storage/foto/' . $magang->mahasiswa->foto) : asset('assets/default-profile.png') }}"
                                alt="Foto {{ $magang->mahasiswa->nama_lengkap }}" class="img-thumbnail rounded-circle"
                                style="width: 120px; height: 120px; object-fit: cover;">
                        </div>
                        <h4 class="text-center mb-1">{{ $magang->mahasiswa->nama_lengkap ?? 'N/A' }}</h4>
                        <p class="text-center text-muted mb-0">NIM: {{ $magang->mahasiswa->nim ?? 'N/A' }}</p>
                        <p class="text-center text-muted mb-3">
                            Prodi: {{ optional($magang->mahasiswa->prodi)->nama_prodi ?? 'N/A' }}
                        </p>
                        <hr>
                        <p class="mb-1">
                            <i class="fas fa-briefcase me-2 text-primary"></i><strong>Lowongan:</strong>
                            {{ $magang->lowongan->judul_lowongan ?? 'N/A' }}
                        </p>
                        @if ($tanggalMulaiMagang && $tanggalSelesaiMagang)
                            <p class="mb-1">
                                <i class="fas fa-calendar-alt me-2 text-primary"></i><strong>Periode Magang:</strong>
                                {{ $tanggalMulaiMagang->isoFormat('D MMM YYYY') }} -
                                {{ $tanggalSelesaiMagang->isoFormat('D MMM YYYY') }}
                            </p>
                        @else
                            <p class="mb-1 text-warning">
                                <i class="fas fa-calendar-alt me-2"></i><strong>Periode Magang:</strong> Belum ditentukan.
                            </p>
                        @endif

                        <div class="mt-3">
                            <p class="mb-1 progress-bar-label">Progres Magang: {{ $pesanProgress }}</p>
                            <div class="progress" style="height: 20px; border-radius: 0.5rem; overflow: hidden;">
                                <div class="progress-bar progress-bar-striped progress-bar-animated
            @if ($progres == 100) bg-success @else bg-primary @endif"
                                    role="progressbar"
                                    style="width: {{ $progres }}%; height: 100%; line-height: 20px; font-size: 0.85rem;"
                                    aria-valuenow="{{ $progres }}" aria-valuemin="0" aria-valuemax="100">
                                    {{ $progres }}%
                                </div>
                            </div>
                        </div>




                        <hr class="mt-4">
                        <h6 class="text-dark-blue"><i class="fas fa-sliders-h me-2"></i>Kontrol Status Magang Mahasiswa</h6>
                        <form action="{{ route('industri.magang.updateStatus', $magang->mahasiswa_magang_id) }}"
                            method="POST">
                            @csrf
                            <div class="mb-2">
                                <label for="current_status_magang" class="form-label">Status Saat Ini:</label>
                                @php
                                    // Gunakan $statusOptions yang sudah ada dari controller
                                    $currentStatusValue = strtolower($magang->status ?? 'belum'); // Default ke 'belum' jika null
                                    $currentStatusLabel =
                                        $statusOptions[$currentStatusValue] ?? ucfirst($currentStatusValue); // Ambil label dari array
                                    $currentBadgeClass = 'bg-secondary'; // Default
                                    $currentTextClass = '';

                                    switch ($currentStatusValue) {
                                        case 'belum':
                                            $currentBadgeClass = 'bg-warning';
                                            $currentTextClass = 'text-dark';
                                            break;
                                        case 'sedang':
                                            $currentBadgeClass = 'bg-success';
                                            break;
                                        case 'selesai':
                                            $currentBadgeClass = 'bg-primary';
                                            break;
                                    }
                                @endphp
                                <p><span
                                        class="badge {{ $currentBadgeClass }} {{ $currentTextClass }} fs-6">{{ $currentStatusLabel }}</span>
                                </p>
                            </div>
                            <div class="mb-3">
                                <label for="status_magang_baru" class="form-label">Ubah Status Menjadi:</label>
                                <select name="status_magang_baru" id="status_magang_baru"
                                    class="form-select form-select-sm">
                                    {{-- Iterasi dari $statusOptions yang dikirim dari controller --}}
                                    @foreach ($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{-- Nonaktifkan pilihan jika itu adalah status saat ini --}}
                                            {{ strtolower($magang->status ?? '') == strtolower($value) ? 'disabled' : '' }}
                                            {{-- Logika tambahan untuk menonaktifkan opsi berdasarkan alur (opsional) --}} {{-- @if ($magang->status == 'belum' && !in_array($value, ['sedang'])) disabled @endif --}} {{-- @if ($magang->status == 'sedang' && !in_array($value, ['selesai'])) disabled @endif --}}
                                            {{-- @if ($magang->status == 'selesai' && $value != 'selesai') disabled @endif --}}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @if ($errors->has('status_magang_baru'))
                                    <div class="text-danger text-xs mt-1">{{ $errors->first('status_magang_baru') }}</div>
                                @endif
                            </div>
                            <button type="submit" class="btn btn-primary btn-sm w-100"
                                {{ $magang->status == 'selesai' ? 'disabled' : '' }}>
                                <i class="fas fa-save me-2"></i>Simpan Perubahan Status
                            </button>
                            @if (session('error') && !$errors->any())
                                {{-- Error umum dari controller --}}
                                <div class="alert alert-danger text-xs mt-2 py-1 px-2">{{ session('error') }}</div>
                            @endif
                        </form>

                    </div>
                </div>
            </div>

            {{-- Kolom Kanan: Log Harian --}}
            <div class="col-lg-8 col-md-7">
                <div class="card shadow-sm">
                    <div class="card-header py-3 border-bottom">
                        <h5 class="mb-0 text-dark-blue">
                            <i class="fas fa-book-reader me-2"></i>Log Harian Mahasiswa
                        </h5>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session()->has('error_reject_' . old('error_reject_id')))
                            {{-- Untuk error reject spesifik --}}
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error_reject_' . old('error_reject_id')) }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @elseif (session('error') && !$errors->any())
                            {{-- Untuk error umum dari update status atau lainnya --}}
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif


                        @if ($logHarian->isEmpty())
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle me-2"></i>Belum ada log harian yang diisi oleh mahasiswa ini.
                            </div>
                        @else
                            @foreach ($logHarian as $logGroup)
                                <div class="card card-log mb-3 shadow-sm">
                                    <div class="card-header">
                                        <h6 class="mb-0"><strong>Tanggal Log:</strong>
                                            {{ Carbon\Carbon::parse($logGroup->tanggal)->isoFormat('dddd, D MMMM YYYY') }}
                                        </h6>
                                    </div>
                                    <div class="card-body">
                                        @if ($logGroup->detail->isEmpty())
                                            <p class="text-muted">Tidak ada detail kegiatan untuk tanggal ini.</p>
                                        @else
                                            @foreach ($logGroup->detail as $detail)
                                                @php
                                                    $logStatusClass = '';
                                                    if (strtolower($detail->status_approval_industri) == 'disetujui') {
                                                        $logStatusClass = 'approved';
                                                    } elseif (
                                                        strtolower($detail->status_approval_industri) == 'ditolak'
                                                    ) {
                                                        $logStatusClass = 'rejected';
                                                    } else {
                                                        $logStatusClass = 'pending';
                                                    }
                                                @endphp
                                                <div class="log-item {{ $logStatusClass }}">
                                                    <p class="mb-1"><strong><i
                                                                class="fas fa-tasks me-1"></i>Kegiatan:</strong></p>
                                                    <div class="ps-3">{!! nl2br(e($detail->isi)) !!}</div>
                                                    <p class="small text-muted mt-1 mb-0">
                                                        <i
                                                            class="fas fa-map-marker-alt me-1"></i>{{ $detail->lokasi ?? 'Tidak ada lokasi' }}
                                                        | <i class="far fa-calendar-alt me-1"></i>Tgl Kegiatan:
                                                        {{ Carbon\Carbon::parse($detail->tanggal_kegiatan)->isoFormat('D MMM YY, HH:mm') }}
                                                    </p>

                                                    <div class="mt-2">
                                                        <small><strong>Status Industri:</strong>
                                                            @if (strtolower($detail->status_approval_industri) == 'disetujui')
                                                                <span class="badge bg-success">Disetujui</span>
                                                            @elseif(strtolower($detail->status_approval_industri) == 'ditolak')
                                                                <span class="badge bg-danger">Ditolak</span>
                                                            @else
                                                                <span class="badge bg-warning text-dark">Menunggu
                                                                    Persetujuan</span>
                                                            @endif
                                                        </small>
                                                        @if ($detail->catatan_industri)
                                                            <div class="catatan-box">
                                                                <strong>Catatan Anda:</strong>
                                                                {{ $detail->catatan_industri }}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    {{-- Tombol Aksi untuk Industri (jika belum final) --}}
                                                    @if (strtolower($detail->status_approval_industri) != 'disetujui' &&
                                                            strtolower($detail->status_approval_industri) != 'ditolak')
                                                        <hr class="my-2">
                                                        <div class="log-actions">
                                                            <button type="button" class="btn btn-success btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#approveModal_{{ $detail->logHarianDetail_id }}">
                                                                <i class="fas fa-check me-1"></i> Setujui
                                                            </button>
                                                            <button type="button" class="btn btn-danger btn-sm"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#rejectModal_{{ $detail->logHarianDetail_id }}">
                                                                <i class="fas fa-times me-1"></i> Tolak
                                                            </button>
                                                        </div>

                                                        <div class="modal fade"
                                                            id="approveModal_{{ $detail->logHarianDetail_id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="approveModalLabel_{{ $detail->logHarianDetail_id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <form
                                                                    action="{{ route('industri.logHarian.approve', $detail->logHarianDetail_id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="approveModalLabel_{{ $detail->logHarianDetail_id }}">
                                                                                Setujui Log Harian</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>Anda akan menyetujui log kegiatan ini. Anda
                                                                                bisa menambahkan catatan (opsional).</p>
                                                                            <div class="mb-3">
                                                                                <label
                                                                                    for="catatan_industri_approve_{{ $detail->logHarianDetail_id }}"
                                                                                    class="form-label">Catatan
                                                                                    (Opsional)
                                                                                    :</label>
                                                                                <textarea name="catatan_industri_approve_{{ $detail->logHarianDetail_id }}"
                                                                                    id="catatan_industri_approve_{{ $detail->logHarianDetail_id }}" class="form-control form-control-sm"
                                                                                    rows="3"></textarea>
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary btn-sm"
                                                                                data-bs-dismiss="modal">Batal</button>
                                                                            <button type="submit"
                                                                                class="btn btn-success btn-sm">Ya,
                                                                                Setujui</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>

                                                        <div class="modal fade"
                                                            id="rejectModal_{{ $detail->logHarianDetail_id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="rejectModalLabel_{{ $detail->logHarianDetail_id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <form
                                                                    action="{{ route('industri.logHarian.reject', $detail->logHarianDetail_id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <input type="hidden" name="error_reject_id"
                                                                        value="{{ $detail->logHarianDetail_id }}">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="rejectModalLabel_{{ $detail->logHarianDetail_id }}">
                                                                                Tolak Log Harian</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <p>Anda akan menolak log kegiatan ini. Mohon
                                                                                berikan alasan atau catatan penolakan.</p>
                                                                            <div class="mb-3">
                                                                                <label
                                                                                    for="catatan_industri_reject_{{ $detail->logHarianDetail_id }}"
                                                                                    class="form-label">Catatan Penolakan
                                                                                    <span
                                                                                        class="text-danger">*</span></label>
                                                                                <textarea name="catatan_industri_reject_{{ $detail->logHarianDetail_id }}"
                                                                                    id="catatan_industri_reject_{{ $detail->logHarianDetail_id }}"
                                                                                    class="form-control form-control-sm @if (
                                                                                        $errors->has('catatan_industri_reject_' . $detail->logHarianDetail_id) &&
                                                                                            old('error_reject_id') == $detail->logHarianDetail_id) is-invalid @endif" rows="3" required>{{ old('catatan_industri_reject_' . $detail->logHarianDetail_id) }}</textarea>
                                                                                @if (
                                                                                    $errors->has('catatan_industri_reject_' . $detail->logHarianDetail_id) &&
                                                                                        old('error_reject_id') == $detail->logHarianDetail_id)
                                                                                    <div class="invalid-feedback">
                                                                                        {{ $errors->first('catatan_industri_reject_' . $detail->logHarianDetail_id) }}
                                                                                    </div>
                                                                                @endif
                                                                            </div>
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button"
                                                                                class="btn btn-secondary btn-sm"
                                                                                data-bs-dismiss="modal">Batal</button>
                                                                            <button type="submit"
                                                                                class="btn btn-danger btn-sm">Ya,
                                                                                Tolak</button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @endif

                                                </div>
                                                @if (!$loop->last)
                                                    <hr class="my-2">
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                </div>
                            @endforeach

                            <div class="mt-4 d-flex justify-content-center">
                                {{ $logHarian->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            // Jika ada error validasi untuk modal reject, buka modalnya kembali
            @if (session()->has('error_reject_' . old('error_reject_id')))
                var errorModalId = '#rejectModal_{{ old('error_reject_id') }}';
                if ($(errorModalId).length) {
                    var modalInstance = new bootstrap.Modal(document.getElementById(errorModalId.substring(1)));
                    modalInstance.show();
                }
            @endif
        });
    </script>
@endpush
