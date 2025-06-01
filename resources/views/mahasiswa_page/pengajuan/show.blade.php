{{-- Konten untuk modal: mahasiswa_page.pengajuan.show.blade.php --}}
<div class="modal-header">
    <h5 class="modal-title" id="detailPengajuanModalLabel">Detail Pengajuan Magang</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">
    @if($pengajuan)
        <div class="row mb-3 align-items-center">
            <div class="col-md-3 text-center">
                <img src="{{ optional($pengajuan->mahasiswa)->foto ? asset('storage/mahasiswa/' . $pengajuan->mahasiswa->foto) : asset('assets/default-profile.png') }}"
                     alt="Foto Mahasiswa" class="img-thumbnail rounded-circle" style="width: 80px; height: 80px; object-fit: cover;">
            </div>
            <div class="col-md-9">
                <h6 class="mb-1">{{ optional($pengajuan->mahasiswa)->nama_lengkap }}</h6>
                <p class="text-muted small mb-1">NIM: {{ optional($pengajuan->mahasiswa)->nim }}</p>
                <p class="text-muted small mb-0">Prodi: {{ optional(optional($pengajuan->mahasiswa)->prodi)->nama_prodi }}</p>
            </div>
        </div>
        <hr class="my-2">

        <h6>Lowongan Dilamar:</h6>
        <p class="mb-1"><strong>{{ optional($pengajuan->lowongan)->judul_lowongan }}</strong></p>
        <p class="small text-muted mb-2">
            <i class="fas fa-building me-1"></i>{{ optional(optional($pengajuan->lowongan)->industri)->industri_nama }}
        </p>
        <p class="small text-muted mb-2">
            <i class="fas fa-map-marker-alt me-1"></i>{{ optional($pengajuan->lowongan)->getAlamatLengkapDisplayAttribute() }}
        </p>
         <p class="small text-muted mb-2">
            <i class="fas fa-tag me-1"></i>Kategori: {{ optional(optional($pengajuan->lowongan)->kategoriSkill)->kategori_nama }}
        </p>
        <p class="small text-muted mb-2">
            <i class="far fa-calendar-alt me-1"></i>Periode Lowongan:
            {{ optional($pengajuan->lowongan)->tanggal_mulai ? \Carbon\Carbon::parse(optional($pengajuan->lowongan)->tanggal_mulai)->isoFormat('D MMM YY') : 'N/A' }} -
            {{ optional($pengajuan->lowongan)->tanggal_selesai ? \Carbon\Carbon::parse(optional($pengajuan->lowongan)->tanggal_selesai)->isoFormat('D MMM YY') : 'N/A' }}
        </p>
        <hr class="my-2">

        <h6>Detail Pengajuan Anda:</h6>
        <p class="small mb-1"><strong>Tanggal Diajukan:</strong> {{ \Carbon\Carbon::parse($pengajuan->created_at)->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</p>
        <p class="small mb-1"><strong>Periode Magang Diajukan:</strong>
            {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->isoFormat('D MMMM YYYY') }} -
            {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->isoFormat('D MMMM YYYY') }}
        </p>
        <p class="mb-0"><strong>Status Pengajuan:</strong>
            @php
                $status = strtolower($pengajuan->status);
                $badgeClass = 'bg-secondary'; // default
                $statusText = ucfirst($pengajuan->status);

                if ($status == 'belum') { $badgeClass = 'bg-warning text-dark'; $statusText = 'Menunggu Review';}
                elseif ($status == 'diterima') { $badgeClass = 'bg-success'; $statusText = 'Diterima oleh Perusahaan';}
                elseif ($status == 'ditolak') { $badgeClass = 'bg-danger'; $statusText = 'Ditolak oleh Perusahaan';}
            @endphp
            <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
        </p>

        @if(strtolower($pengajuan->status) == 'ditolak' && $pengajuan->alasan_penolakan)
            <div class="mt-3 alert alert-light border py-2 px-3 small">
                <p class="fw-bold mb-1 text-danger"><i class="fas fa-comment-slash me-1"></i>Alasan Penolakan dari Industri:</p>
                <p class="mb-0 fst-italic">"{{ nl2br(htmlspecialchars($pengajuan->alasan_penolakan)) }}"</p>
            </div>
        @endif

        {{-- Tombol Aksi Mahasiswa (Ambil/Tolak Tawaran) SUDAH DIHAPUS --}}

    @else
        <p class="text-center text-danger">Gagal memuat detail pengajuan.</p>
    @endif
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
</div>
