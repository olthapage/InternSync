<div class="modal-dialog" role="document" style="max-width: 650px;">
    <div class="modal-content border-0 rounded-3 shadow-lg">
        <!-- HEADER dengan Status -->
        <div class="modal-header bg-white border-bottom-0 pt-3 pb-0 position-relative">
            <span class="badge
                @if($pengajuan->status == 'belum')
                    bg-warning bg-opacity-25 text-warning
                @elseif($pengajuan->status == 'diterima')
                    bg-success bg-opacity-25 text-success
                @elseif($pengajuan->status == 'ditolak')
                    bg-danger bg-opacity-25 text-danger
                @endif
                position-absolute text-white" style="right: 30px; top: 15px; z-index: 50;">
                {{ ucfirst($pengajuan->status) }}
            </span>
        </div>

        <!-- BODY -->
        <div class="modal-body bg-white px-4 pt-0 pb-4">
            <!-- Logo/Photo Centered -->
            <div class="text-center mb-4 pt-1">
                <div class="border rounded-3 d-inline-block p-3 mb-2" style="width: 120px; height: 120px;">
                    <img src="{{ $pengajuan->mahasiswa->foto ? asset('storage/foto_mahasiswa/' . $pengajuan->mahasiswa->foto) : asset('assets/default-profile.png') }}"
                         alt="Foto Mahasiswa"
                         class="img-fluid"
                         style="width: 100%; height: 100%; object-fit: contain;">
                </div>
            </div>

            <!-- Judul/Nama Mahasiswa -->
            <h3 class="fw-bold text-dark text-center mb-4">{{ $pengajuan->mahasiswa->nama_lengkap ?? 'Mahasiswa' }}</h3>

            <!-- Detail Information in 2 columns -->
            <div class="row g-3 mb-4">
                <!-- Kolom Kiri -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">NIM</p>
                        <p class="fw-medium mb-0">{{ $pengajuan->mahasiswa->nim ?? '-' }}</p>
                    </div>

                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Program Studi</p>
                        <p class="fw-medium mb-0">{{ $pengajuan->mahasiswa->program_studi ?? '-' }}</p>
                    </div>

                </div>

                <!-- Kolom Kanan -->
                <div class="col-md-6">
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Email</p>
                        <p class="fw-medium mb-0">{{ $pengajuan->mahasiswa->email ?? '-' }}</p>
                    </div>
                    <div class="mb-3">
                        <p class="text-muted mb-1 small">Tanggal Pengajuan</p>
                        <p class="fw-medium mb-0">{{ date('d M Y', strtotime($pengajuan->created_at)) }}</p>
                    </div>
                </div>
            </div>

            <!-- Horizontal Rule -->
            <hr class="my-4">

            <!-- Detail Lowongan yang Dilamar -->
            <div class="mb-4">
                <h5 class="fw-medium text-dark mb-3">Detail Lowongan yang Dilamar</h5>
                <div class="card border shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="border rounded me-3" style="width: 50px; height: 50px; overflow: hidden;">
                                        <img src="{{ $pengajuan->lowongan->industri->logo ? asset('storage/logo_industri/' . $pengajuan->lowongan->industri->logo) : asset('assets/default-industri.png') }}"
                                             alt="Logo Perusahaan"
                                             class="img-fluid"
                                             style="width: 100%; height: 100%; object-fit: contain;">
                                    </div>
                                    <div>
                                        <p class="text-muted mb-0 small">Perusahaan</p>
                                        <p class="fw-medium mb-0">{{ $pengajuan->lowongan->industri->industri_nama ?? '-' }}</p>
                                    </div>
                                </div>

                                <p class="text-muted mb-1 small">Judul Lowongan</p>
                                <p class="fw-medium mb-3">{{ $pengajuan->lowongan->judul_lowongan ?? '-' }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="text-muted mb-1 small">Periode Magang</p>
                                <p class="fw-medium mb-3">
                                    {{ date('d M Y', strtotime($pengajuan->tanggal_mulai)) }} -
                                    {{ date('d M Y', strtotime($pengajuan->tanggal_selesai)) }}
                                </p>

                                <p class="text-muted mb-1 small">Kategori</p>
                                <p class="fw-medium mb-0">{{ $pengajuan->lowongan->kategoriSkill->kategori_nama ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Keterampilan Section -->
            <div class="mb-4">
                <h5 class="fw-medium text-dark mb-3">Keterampilan yang Dibutuhkan</h5>
                @if ($pengajuan->lowongan->lowonganSkill->isEmpty())
                    <p class="text-muted">Belum ada keterampilan ditentukan.</p>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        @foreach ($pengajuan->lowongan->lowonganSkill as $low)
                            @if ($low->skill)
                                <span class="badge bg-light border text-dark px-3 py-2 mb-2">
                                    {{ $low->skill->skill_nama }}
                                </span>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- FOOTER -->
        <div class="modal-footer bg-white border-top-0 pt-0 px-4 pb-4">
            <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
        </div>
    </div>
</div>
