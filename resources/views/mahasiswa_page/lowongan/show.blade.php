@php
    $mahasiswa = auth()->user();
    $profilLengkap = $mahasiswa && $mahasiswa->status_verifikasi == 'valid';
@endphp


<div class="modal-dialog" role="document" style="max-width: 650px;">
    <div class="modal-content border-0 rounded-3 shadow-lg">
        <!-- HEADER dengan Status -->
        <div class="modal-header bg-white border-bottom-0 pt-3 pb-0 position-relative">
            <span class="badge bg-success bg-opacity-25 text-warning position-absolute text-white"
                style="right: 30px; top: 15px; z-index: 50;">
                Sedang Dibuka
            </span>
        </div>

        <!-- BODY -->
        <div class="modal-body bg-white px-4 pt-0 pb-4">
            <!-- Logo Centered -->
            <div class="text-center mb-4 pt-1">
                <div class="border rounded-3 d-inline-block p-3 mb-2" style="width: 120px; height: 120px;">
                    <img src="{{ $lowongan->industri->logo ? asset('storage/logo_industri/' . $lowongan->industri->logo) : asset('assets/default-industri.png') }}"
                        alt="Logo Industri" class="img-fluid" style="width: 100%; height: 100%; object-fit: contain;">
                </div>
            </div>

            <!-- Order ID / Judul Lowongan -->
            <h3 class="fw-bold text-dark text-center mb-4">{{ $lowongan->judul_lowongan }}</h3>

            <!-- Detail Information in 2 columns -->
            <div class="row g-3 mb-4">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <p class="text-muted mb-1 small text-bold">Industri</p>
                        <p class="fw-medium mb-0">{{ $lowongan->industri->industri_nama ?? '-' }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted mb-1 small text-bold">Start time</p>
                        <p class="fw-medium mb-0">{{ date('d M Y', strtotime($lowongan->tanggal_mulai)) }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted mb-1 small text-bold text-bold">Kategori</p>
                        <p class="fw-medium mb-0">{{ $lowongan->kategoriSkill->kategori_nama ?? '-' }}</p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <p class="text-muted mb-1 small text-bold">Lokasi</p>
                        <p class="fw-medium mb-0">{{ $lowongan->alamat_lengkap_display }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1 small text-bold">End time</p>
                        <p class="fw-medium mb-0">{{ date('d M Y', strtotime($lowongan->tanggal_selesai)) }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Slot Tersedia</p>
                        <p class="fw-medium mb-0">{{ $lowongan->slotTersedia() }}</p>
                    </div>
                </div>
            </div>

            <!-- Horizontal Rule -->
            <hr class="my-4">

            <!-- Keterampilan Section -->
            <div class="mb-4">
                <h5 class="fw-medium text-dark mb-3">Keterampilan yang Dibutuhkan</h5>
                @if ($lowongan->lowonganSkill->isEmpty())
                    <p class="text-muted">Belum ada keterampilan ditentukan.</p>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($lowongan->lowonganSkill as $low)
                            @if ($low->skill)
                                <span class="badge bg-light border text-dark px-3 py-2 mb-2">
                                    {{ $low->skill->skill_nama }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
            <hr class="my-4">

            <div class="mb-4">
                <h5 class="fw-medium text-dark mb-3">Benefit dan Lingkungan Kerja</h5>
                <div class="row">
                    {{-- Tipe Kerja --}}
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-briefcase fa-lg text-primary mt-1 me-3"></i>
                            <div>
                                <p class="text-muted mb-1 small text-bold">Tipe Kerja</p>
                                @if ($lowongan->tipeKerja->isNotEmpty())
                                    @foreach ($lowongan->tipeKerja as $tipe)
                                        <span
                                            class="badge bg-primary bg-opacity-75 me-1">{{ $tipe->nama_tipe_kerja }}</span>
                                    @endforeach
                                @else
                                    <p class="fw-medium mb-0">-</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Upah / Uang Saku --}}
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-start">
                            <i class="fas fa-money-bill-wave fa-lg text-success mt-1 me-3"></i>
                            <div>
                                <p class="text-muted mb-1 small text-bold">Uang Saku</p>
                                <p class="fw-medium mb-0">
                                    @if ($lowongan->upah > 0)
                                        Rp {{ number_format($lowongan->upah, 0, ',', '.') }} / bulan
                                    @else
                                        Tidak disebutkan
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if ($lowongan->fasilitas->isNotEmpty())
                <div class="mb-4">
                    <h5 class="fw-medium text-dark mb-3">Fasilitas yang Didapat</h5>
                    <div class="row">
                        @foreach ($lowongan->fasilitas as $fasilitas)
                            <div class="col-md-6">
                                <p class="mb-2">
                                    <i class="fas fa-check-circle text-success me-2"></i>
                                    <span class="text-muted">{{ $fasilitas->nama_fasilitas }}</span>
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            <!-- Deskripsi Section -->
            <div>
                <h5 class="fw-medium text-dark mb-3">Deskripsi Lowongan</h5>
                <div class="text-muted">
                    {!! nl2br(e($lowongan->deskripsi)) !!}
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div
            class="modal-footer bg-white border-top-0 pt-0 px-4 pb-4 d-flex justify-content-between align-items-center">
            @if (!$profilLengkap)
                <small class="text-danger">
                    <i class="fas fa-info-circle me-1"></i>
                    Belum bisa mengajukan magang karena profil belum lengkap
                </small>
            @endif

            <div class="ms-auto">
                <button type="button" class="btn btn-secondary me-2"
                    onclick="$('#myModal').modal('hide')">Tutup</button>

                @if ($profilLengkap)
                    <a href="{{ url('pengajuan/' . $lowongan->lowongan_id . '/create') }}"
                        class="btn btn-primary px-4">Lamar Sekarang</a>
                @endif
            </div>
        </div>

    </div>
</div>
