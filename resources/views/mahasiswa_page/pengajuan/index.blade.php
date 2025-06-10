@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <div class="mb-4 d-flex flex-column align-items-start gap-4">
                <h2>Pengajuan Magang</h2>
                <p class="text-sm text-secondary">Sebelum mengajukan magang, lebih baik melengkapi portofoliomu dan usahakan sesuai dengan apa yang dibutuhkan perusahaan <br>
                untuk mendapatkan peluang diterima yang lebih besar!</p>

                @if (!$profilLengkap)
                    <div class="p-3 mb-3 rounded-xl shadow-sm w-100">
                        <strong>Profil belum lengkap atau invalid!</strong> Silakan lengkapi data verifikasi seperti KTP, KHS, Surat
                        Izin Orang Tua, dan CV sebelum mengajukan magang.
                        <p class="text-secondary mb-0">Lengkapi juga portofolio beserta skill yang kamu kuasai pada halaman <a href="{{ route('mahasiswa.portofolio.index') }}" class="text-info">Portofolio Saya</a></p>
                    </div>
                @else
                    {{-- Cek apakah bisa mengajukan magang baru --}}
                    @if ($statusPengajuanAktif == 'diterima')
                        <div class="alert alert-success text-white p-3 mb-3 rounded-xl shadow-sm w-100">
                            <strong><i class="fas fa-check-circle me-2"></i>Informasi:</strong> {{ $alasanTidakBisaAjukan }}
                        </div>
                    @elseif ($statusPengajuanAktif == 'belum')
                        <div class="alert text-light alert-info p-3 mb-3 rounded-xl shadow-sm w-100">
                            <strong><i class="fas fa-exclamation-triangle me-2"></i>Informasi:</strong> {{ $alasanTidakBisaAjukan }}
                        </div>
                    @else
                        {{-- Jika profil lengkap dan tidak ada pengajuan aktif yang menghalangi --}}
                        <a href="{{ route('mahasiswa.pengajuan.create') }}" class="btn btn-success btn-lg">
                            <i class="fas fa-plus me-2"></i>Tambah Pengajuan
                        </a>
                    @endif
                @endif
            </div>

            {{-- Tampilkan riwayat hanya jika profil lengkap --}}
            @if ($profilLengkap)
                <h5 class="mb-2">Riwayat Pengajuan</h5>
                @if ($pengajuan->isEmpty())
                    <p>Belum ada riwayat pengajuan.</p>
                @else
                    <div class="row mt-4 mb-4">
                        @foreach ($pengajuan as $item)
                            <div class="col-xl-3 col-md-6 mb-4 d-flex">
                                <div class="card card-blog card-plain shadow pengajuan-card rounded-xl py-3 w-100">
                                    <div class="position-relative">
                                        <div class="image-container py-3 pt-6">
                                            <img src="{{ $item->lowongan->industri->logo ? asset('storage/logo_industri/' . $item->lowongan->industri->logo) : asset('assets/default-industri.png') }}"
                                                alt="Lowongan Image" class="img-fluid border-radius-lg rounded">
                                        </div>
                                    </div>
                                    <div class="card-body px-3 d-flex flex-column justify-content-between"
                                        style="min-height: 280px;">
                                        <div>
                                            <p class="text-gradient text-primary mb-1 text-sm">
                                                {{ $item->lowongan->industri->industri_nama ?? '-' }}
                                            </p>
                                            <h5 class="font-weight-bold mb-2">
                                                {{ $item->lowongan->judul_lowongan }}
                                            </h5>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                                <span class="text-sm">
                                                    {{ \Carbon\Carbon::parse($item->tanggal_mulai)->format('d/m/Y') }} -
                                                    {{ \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') }}
                                                </span>
                                            </div>
                                            <div class="d-flex align-items-center mb-2">
                                                <i class="fas fa-info-circle text-primary me-2"></i>
                                                <span class="text-sm">
                                                    Status:
                                                    @if ($item->status == 'diproses')
                                                        <span class="badge bg-warning text-dark">Diproses</span>
                                                    @elseif ($item->status == 'diterima')
                                                        <span class="badge bg-success">Diterima</span>
                                                    @elseif ($item->status == 'ditolak')
                                                        <span class="badge bg-danger">Ditolak</span>
                                                    @elseif ($item->status == 'belum') {{-- Tambahkan jika 'belum' adalah status valid --}}
                                                        <span class="badge bg-secondary">Belum Diproses</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($item->status) }}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        </div>
                                        <div class="d-flex justify-content-between mt-3">
                                            <button
                                                onclick="modalAction('{{ url('/mahasiswa/pengajuan/' . $item->pengajuan_id . '/show') }}')"
                                                class="btn btn-white btn-sm mb-0 px-3 w-100 text-lg">
                                                Lihat Detail
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif {{-- End of @if ($profilLengkap) untuk riwayat --}}
        </div>
    </div>
    <div class="modal fade animate shake" id="myModal" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-md"> {{-- Anda bisa ganti modal-xl dengan modal-lg atau hapus untuk ukuran default --}}
    <div class="modal-content">
      {{-- Konten awal spinner, akan diganti oleh AJAX --}}
      <div class="modal-header"><h5 class="modal-title" id="myModalLabel">Loading...</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>
      <div class="modal-body text-center py-5">
          <div class="spinner-border text-primary" role="status">
              <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2">Memuat detail...</p>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>
    </div>
  </div>
</div>
@endsection

@push('js')
    <script>
    function modalAction(url = '') {
        console.log('[modalAction] Function called. URL:', url); // LOG 1

        const myModalElement = document.getElementById('myModal');
        if (!myModalElement) {
            console.error('[modalAction] Modal element #myModal not found!');
            return;
        }
        console.log('[modalAction] Modal element #myModal found.'); // LOG 2

        // Pastikan Bootstrap 5 Modal class tersedia
        if (typeof bootstrap === 'undefined' || typeof bootstrap.Modal === 'undefined') {
            console.error('[modalAction] Bootstrap Modal class not found. Make sure Bootstrap 5 JS is loaded.');
            return;
        }
        console.log('[modalAction] Bootstrap Modal class is available.'); // LOG 3

        const modal = bootstrap.Modal.getInstance(myModalElement) || new bootstrap.Modal(myModalElement);
        console.log('[modalAction] Modal instance obtained/created.'); // LOG 4

        const modalContentTarget = $('#myModal .modal-content'); // Target injection
        if (modalContentTarget.length === 0) {
            console.error('[modalAction] Modal content target "#myModal .modal-content" not found!');
            return;
        }
        console.log('[modalAction] Modal content target "#myModal .modal-content" found.'); // LOG 5

        // Tampilkan spinner/loading awal di dalam .modal-content
        modalContentTarget.html('<div class="modal-header"><h5 class="modal-title">Loading...</h5></div><div class="modal-body text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Memuat detail...</p></div><div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button></div>');
        console.log('[modalAction] Spinner HTML injected.'); // LOG 6
        modal.show();
        console.log('[modalAction] modal.show() called.'); // LOG 7

        console.log('[modalAction] Initiating AJAX request to:', url); // LOG 8
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                console.log('[modalAction] AJAX success. Response received.'); // LOG 9 Success
                // console.log('[modalAction] Response HTML (awal 100 karakter):', response.substring(0, 100)); // Lihat potongan respons
                modalContentTarget.html(response);
                console.log('[modalAction] Response HTML injected into modal content.'); // LOG 10 Success
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('[modalAction] AJAX error. Status:', jqXHR.status, 'TextStatus:', textStatus, 'ErrorThrown:', errorThrown); // LOG 9 Error
                console.error('[modalAction] AJAX error. Response Text:', jqXHR.responseText); // LOG 10 Error

                let errorMessage = '<div class="modal-header"><h5 class="modal-title text-danger">Error</h5><button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></div>';
                errorMessage += '<div class="modal-body"><div class="alert alert-danger text-center">';
                errorMessage += '<i class="fas fa-exclamation-triangle fa-2x mb-2"></i>';
                if (jqXHR.status === 404) {
                    errorMessage += '<p>Data tidak ditemukan (Error 404).</p>';
                } else if (jqXHR.status === 403) {
                    errorMessage += '<p>Anda tidak diizinkan mengakses detail ini (Error 403).</p>';
                } else if (jqXHR.status === 0) {
                    errorMessage += '<p>Gagal terhubung ke server. Periksa koneksi internet Anda.</p>';
                } else {
                    errorMessage += '<p>Gagal memuat detail. Silakan coba lagi (Error ' + jqXHR.status + ').</p>';
                }
                errorMessage += '</div></div><div class="modal-footer"><button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Tutup</button></div>';
                modalContentTarget.html(errorMessage);
                console.log('[modalAction] Error message HTML injected into modal content.'); // LOG 11 Error
            }
        });
    }
</script>
@endpush

@push('css')
    <style>
        .pengajuan-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
        }

        .pengajuan-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .pengajuan-card .image-container img {
            max-height: 120px;
            width: auto;
            display: block;
            margin: 0 auto;
        }

        .deskripsi-terbatas { /* Tidak terpakai di kode ini, tapi biarkan jika ada di tempat lain */
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .alert-warning.text-white strong, .alert-warning.text-white { /* Memastikan teks kontras di alert warning */
            color: #664d03 !important; /* Bootstrap default, atau sesuaikan */
        }
        .alert-warning.text-white i {
            color: #664d03 !important;
        }
    </style>
@endpush
