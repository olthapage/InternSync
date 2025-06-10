@extends('layouts.template')

@section('title', 'Ajukan Magang Baru - InternSync')

@push('css')
    <style>
        .lowongan-card .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .lowongan-card .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .lowongan-card .card-body .deskripsi-terbatas {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            line-height: 1.4em;
            max-height: 4.2em; /* 1.4em * 3 lines */
        }
        .portfolio-item { margin-bottom: 1.5rem; }
        .portfolio-item img,
        .portfolio-item iframe { max-width: 100%; border-radius: 0.25rem; }
        .form-label { font-weight: 500; }
    </style>
@endpush

@section('content')
    <div class="card card-outline card-info">
        <div class="card-body text-sm">
            <h2 class="mb-4">Ajukan Magang Baru</h2>

            <form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST" id="pengajuanMagangForm">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="industri_id" class="form-label">Filter Industri:</label>
                        <select id="industri_id" name="filter_industri_id" class="form-select form-select-sm" onchange="filterLowongan()">
                            <option value="">-- Semua Industri --</option>
                            @foreach ($industriList as $industri)
                                <option value="{{ $industri->industri_id }}">{{ $industri->industri_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="kategori_skill_id" class="form-label">Filter Kategori Skill:</label>
                        <select id="kategori_skill_id" name="filter_kategori_skill_id" class="form-select form-select-sm"
                            onchange="filterLowongan()">
                            <option value="">-- Semua Kategori --</option>
                            @foreach ($kategoriSkillList as $kategori)
                                <option value="{{ $kategori->kategori_skill_id }}">{{ $kategori->kategori_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div id="lowongan-container" class="row mt-4 mb-4">
                    @forelse ($lowonganList as $index => $lowongan)
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4 lowongan-card" data-industri="{{ $lowongan->industri_id }}"
                            data-kategori="{{ $lowongan->kategori_skill_id }}" data-index="{{ $index }}"
                            style="{{ $index >= 4 ? 'display: none;' : '' }}">
                            <div class="card card-blog card-plain shadow-sm rounded-lg py-3 h-100">
                                <div class="position-relative text-center px-3 pt-2" style="height: 100px;">
                                    <img src="{{ $lowongan->industri->logo ? asset('storage/logo_industri/' . $lowongan->industri->logo) : asset('assets/default-industri.png') }}"
                                        alt="Logo Industri" class="img-fluid border-radius-md"
                                        style="max-height: 100%; width: auto; display: inline-block;">
                                </div>
                                <div class="card-body px-3 pb-2">
                                    <div>
                                        <p class="text-gradient text-primary mb-1 text-sm">
                                            {{ $lowongan->industri->industri_nama ?? '-' }}
                                        </p>
                                        <h5 class="font-weight-bold mb-2 text-truncate" title="{{ $lowongan->judul_lowongan }}">
                                            {{ $lowongan->judul_lowongan }}
                                        </h5>
                                        <div class="d-flex align-items-center mb-1">
                                            <i class="fas fa-calendar-alt text-primary me-2"></i>
                                            <span class="text-xs">{{ \Carbon\Carbon::parse($lowongan->tanggal_mulai)->isoFormat('D MMM YY') }} - {{ \Carbon\Carbon::parse($lowongan->tanggal_selesai)->isoFormat('D MMM YY') }}</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-tag text-primary me-2"></i>
                                            <span class="text-xs">{{ $lowongan->kategoriSkill->kategori_nama ?? 'Umum' }}</span>
                                        </div>
                                        <div class="mb-2" style="min-height: 4.2em;"> {{-- Untuk tinggi deskripsi yg konsisten --}}
                                            <p class="text-xs mb-0 d-flex deskripsi-terbatas" title="{{ strip_tags($lowongan->deskripsi) }}">
                                                {{-- <i class="fas fa-info-circle text-primary me-2 mt-1"></i> --}}
                                                <span>{{ strip_tags($lowongan->deskripsi) }}</span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between mt-auto">
                                        <button type="button" class="btn btn-sm btn-dark mb-0 w-48"
                                            onclick="selectLowongan('{{ $lowongan->lowongan_id }}', '{{ htmlspecialchars($lowongan->judul_lowongan, ENT_QUOTES) }}', '{{ \Carbon\Carbon::parse($lowongan->tanggal_mulai)->format('Y-m-d') }}', '{{ \Carbon\Carbon::parse($lowongan->tanggal_selesai)->format('Y-m-d') }}')">
                                            <i class="fas fa-check me-1"></i> Pilih
                                        </button>
                                        <button type="button" class="btn btn-sm btn-white mb-0 w-48"
                                            onclick="showDetail('{{ $lowongan->lowongan_id }}')">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-center text-muted">Tidak ada lowongan tersedia saat ini.</p>
                        </div>
                    @endforelse
                </div>

                <div id="view-all-container" class="text-center mb-4" style="display: {{ count($lowonganList) > 4 ? 'block' : 'none' }};">
                    <button type="button" id="view-all-btn" class="btn btn-outline-info" onclick="toggleViewAll()">
                        <i class="fas fa-th-list me-1"></i> Lihat Semua Lowongan
                    </button>
                </div>

                <input type="hidden" id="lowongan_id" name="lowongan_id" value="{{ old('lowongan_id') }}">
                <div id="selected-lowongan" class="mb-3 p-3 border rounded" style="display: {{ old('lowongan_id') ? 'block' : 'none' }};">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Lowongan Terpilih:</strong>
                            <span id="selected-lowongan-title">{{ old('lowongan_id') ? ($lowonganList->firstWhere('lowongan_id', old('lowongan_id'))->judul_lowongan ?? '') : '' }}</span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearSelection()">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                </div>

                <div id="date-range-info" class="alert alert-info mb-3" style="display: none;">
                    <i class="fas fa-info-circle me-2"></i>
                    <span>Periode magang Anda harus dalam rentang waktu lowongan:</span>
                    <strong id="lowongan-date-range"></strong>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai Magang Anda <span class="text-danger">*</span></label>
                        <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control @error('tanggal_mulai') is-invalid @enderror" value="{{ old('tanggal_mulai') }}" required>
                        @error('tanggal_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai Magang Anda <span class="text-danger">*</span></label>
                        <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control @error('tanggal_selesai') is-invalid @enderror" value="{{ old('tanggal_selesai') }}" required>
                        @error('tanggal_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success"><i class="fas fa-paper-plane me-1"></i> Ajukan Magang</button>
                    <a href="{{ route('mahasiswa.pengajuan.index') }}" class="btn btn-secondary"><i class="fas fa-times me-1"></i> Batal</a>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Detail Lowongan --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Lowongan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailModalBody">
                    {{-- Konten akan diisi oleh JavaScript --}}
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Mengambil data...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-white" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-dark" id="pilihDariModalBtn">Pilih Lowongan Ini</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    let showAllLowongan = false;
    let initialCardDisplayCount = 4; // Jumlah kartu yang ditampilkan awal

    function updateLowonganVisibility() {
        const cards = document.querySelectorAll('.lowongan-card');
        let visibleCountInCurrentFilter = 0;
        const industriFilter = document.getElementById('industri_id').value;
        const kategoriFilter = document.getElementById('kategori_skill_id').value;

        cards.forEach(card => {
            const cardIndustriId = card.dataset.industri;
            const cardKategoriId = card.dataset.kategori;
            const matchesIndustri = !industriFilter || cardIndustriId === industriFilter;
            const matchesKategori = !kategoriFilter || cardKategoriId === kategoriFilter;

            if (matchesIndustri && matchesKategori) {
                visibleCountInCurrentFilter++;
                if (showAllLowongan || visibleCountInCurrentFilter <= initialCardDisplayCount) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            } else {
                card.style.display = 'none';
            }
        });

        const viewAllContainer = document.getElementById('view-all-container');
        if (visibleCountInCurrentFilter > initialCardDisplayCount) {
            viewAllContainer.style.display = 'block';
        } else {
            viewAllContainer.style.display = 'none';
        }
    }

    function toggleViewAll() {
        showAllLowongan = !showAllLowongan;
        const viewAllBtn = document.getElementById('view-all-btn');
        updateLowonganVisibility();
        viewAllBtn.innerHTML = showAllLowongan ? '<i class="fas fa-compress-alt me-1"></i> Tampilkan Lebih Sedikit' : '<i class="fas fa-th-list me-1"></i> Lihat Semua Lowongan';
    }

    function filterLowongan() {
        showAllLowongan = false;
        const viewAllBtn = document.getElementById('view-all-btn');
        if (viewAllBtn) { // Pastikan tombol ada sebelum mengubah innerHTML
             viewAllBtn.innerHTML = '<i class="fas fa-th-list me-1"></i> Lihat Semua Lowongan';
        }
        updateLowonganVisibility();
        // Tidak clear selection di sini agar user bisa filter tanpa kehilangan pilihan
        // clearSelection(); // Hapus atau sesuaikan jika ingin clear saat filter
    }

    function selectLowongan(id, title, lowonganStartDate, lowonganEndDate) {
        document.getElementById('lowongan_id').value = id;
        document.getElementById('selected-lowongan-title').textContent = title;
        document.getElementById('selected-lowongan').style.display = 'block';

        const cards = document.querySelectorAll('.lowongan-card');
        cards.forEach(card => {
            const pilihButton = card.querySelector('button[onclick^="selectLowongan"]');
            if (pilihButton) {
                const onclickAttr = pilihButton.getAttribute('onclick');
                const cardLowonganId = onclickAttr.split("'")[1];
                if (cardLowonganId === id) {
                    card.querySelector('.card').classList.add('border', 'border-primary', 'border-2');
                } else {
                    card.querySelector('.card').classList.remove('border', 'border-primary', 'border-2');
                }
            }
        });

        if (lowonganStartDate && lowonganEndDate) {
            document.getElementById('lowongan-date-range').textContent =
                `${new Date(lowonganStartDate).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })} s/d ${new Date(lowonganEndDate).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' })}`;
            document.getElementById('date-range-info').style.display = 'block';

            document.getElementById('tanggal_mulai').min = lowonganStartDate;
            document.getElementById('tanggal_mulai').max = lowonganEndDate;
            document.getElementById('tanggal_selesai').min = lowonganStartDate;
            document.getElementById('tanggal_selesai').max = lowonganEndDate;
        } else {
            // Jika dari modal tidak ada tanggal (seharusnya ada dari AJAX), sembunyikan info
            document.getElementById('date-range-info').style.display = 'none';
        }
    }

    function clearSelection() {
        document.getElementById('lowongan_id').value = '';
        document.getElementById('selected-lowongan').style.display = 'none';
        document.getElementById('date-range-info').style.display = 'none';

        document.getElementById('tanggal_mulai').min = '';
        document.getElementById('tanggal_mulai').max = '';
        document.getElementById('tanggal_selesai').min = '';
        document.getElementById('tanggal_selesai').max = '';
        document.getElementById('tanggal_mulai').value = '';
        document.getElementById('tanggal_selesai').value = '';

        const cards = document.querySelectorAll('.lowongan-card .card');
        cards.forEach(card => card.classList.remove('border', 'border-primary', 'border-2'));
    }

    function showDetail(lowonganId) {
        const detailModalEl = document.getElementById('detailModal');
        const detailModalInstance = bootstrap.Modal.getInstance(detailModalEl) || new bootstrap.Modal(detailModalEl);
        const modalBody = document.getElementById('detailModalBody');
        const modalTitle = document.getElementById('detailModalLabel');
        const pilihDariModalBtn = document.getElementById('pilihDariModalBtn'); // Ganti ID tombol modal

        pilihDariModalBtn.setAttribute('data-id', lowonganId);
        pilihDariModalBtn.setAttribute('data-title', '');
        pilihDariModalBtn.setAttribute('data-start-date', '');
        pilihDariModalBtn.setAttribute('data-end-date', '');


        modalTitle.textContent = 'Memuat Detail Lowongan...';
        modalBody.innerHTML = `
            <div class="text-center py-5">
                <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Mengambil data...</p>
            </div>`;
        detailModalInstance.show();

        const detailUrl = `/api/lowongan/${lowonganId}/details-json`; // Pastikan route ini ada
        fetch(detailUrl)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}, URL: ${response.url}`);
                }
                return response.json();
            })
            .then(result => {
                if (result.status && result.data) {
                    const lowongan = result.data;
                    modalTitle.textContent = 'Detail Lowongan: ' + lowongan.judul_lowongan;
                    pilihDariModalBtn.setAttribute('data-title', lowongan.judul_lowongan);
                    pilihDariModalBtn.setAttribute('data-start-date', lowongan.periode_magang_raw ? lowongan.periode_magang_raw.start : ''); // Asumsi ada format Y-m-d
                    pilihDariModalBtn.setAttribute('data-end-date', lowongan.periode_magang_raw ? lowongan.periode_magang_raw.end : '');


                    let skillsHtml = '<p class="text-muted small"><em>Tidak ada skill spesifik yang dicantumkan.</em></p>';
                    if (lowongan.required_skills && lowongan.required_skills.length > 0) {
                        skillsHtml = '<ul class="list-unstyled mb-0">';
                        lowongan.required_skills.forEach(skill => {
                            skillsHtml += `<li><span class="badge bg-info me-1 mb-1">${skill.nama_skill} (${skill.level_kompetensi})</span></li>`;
                        });
                        skillsHtml += '</ul>';
                    }

                    modalBody.innerHTML = `
                        <div class="row mb-2">
                            <div class="col-md-3 text-center">
                                <img src="${lowongan.logo_industri_url}" alt="Logo Industri" class="img-fluid rounded mb-2" style="max-height: 70px;">
                            </div>
                            <div class="col-md-9">
                                <h4 class="mb-1">${lowongan.judul_lowongan}</h4>
                                <p class="text-primary mb-1">${lowongan.industri_nama}</p>
                                <p class="text-xs text-muted mb-0"><i class="fas fa-map-marker-alt fa-fw me-1"></i>${lowongan.lokasi}</p>
                                <p class="text-xs text-muted mb-0"><i class="fas fa-tag fa-fw me-1"></i>${lowongan.kategori_nama}</p>
                            </div>
                        </div>
                        <hr class="my-2">
                        <div class="mb-2">
                             <span class="badge ${lowongan.status_pendaftaran_badge_class}">${lowongan.status_pendaftaran_text}</span>
                        </div>
                        <h6>Deskripsi Pekerjaan:</h6>
                        <div style="max-height: 150px; overflow-y: auto; font-size: 0.85rem; margin-bottom: 1rem; padding: 10px; background-color: #f8f9fa; border-radius: 5px;">
                           ${lowongan.deskripsi_lengkap}
                        </div>
                        <div class="row">
                            <div class="col-md-7">
                                <h6>Keahlian yang Dibutuhkan:</h6>
                                ${skillsHtml}
                            </div>
                            <div class="col-md-5">
                                <h6>Informasi Tambahan:</h6>
                                <ul class="list-unstyled" style="font-size: 0.85rem;">
                                    <li><i class="fas fa-calendar-alt fa-fw me-2 text-primary"></i><strong>Magang:</strong> ${lowongan.periode_magang}</li>
                                    <li><i class="fas fa-user-clock fa-fw me-2 text-primary"></i><strong>Daftar:</strong> ${lowongan.periode_pendaftaran}</li>
                                    <li><i class="fas fa-users fa-fw me-2 text-primary"></i><strong>Slot:</strong> ${lowongan.slot_tersedia} dari ${lowongan.total_slot}</li>
                                </ul>
                            </div>
                        </div>
                    `;
                } else {
                    modalTitle.textContent = 'Gagal Memuat Detail';
                    modalBody.innerHTML = `<p class="text-danger">${result.message || 'Tidak dapat memuat detail lowongan saat ini.'}</p>`;
                }
            })
            .catch(error => {
                console.error('Error fetching lowongan detail:', error);
                modalTitle.textContent = 'Error Jaringan';
                modalBody.innerHTML = `<p class="text-danger">Terjadi kesalahan saat mengambil data. Periksa koneksi Anda dan coba lagi. (${error.message})</p>`;
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const pilihDariModalBtn = document.getElementById('pilihDariModalBtn'); // Ganti ID
        if (pilihDariModalBtn) {
            pilihDariModalBtn.addEventListener('click', function() {
                const lowonganId = this.getAttribute('data-id');
                const lowonganTitle = this.getAttribute('data-title');
                const lowonganStartDate = this.getAttribute('data-start-date');
                const lowonganEndDate = this.getAttribute('data-end-date');

                if (lowonganId && lowonganTitle) {
                    selectLowongan(lowonganId, lowonganTitle, lowonganStartDate, lowonganEndDate);
                } else {
                    console.error("ID atau Judul Lowongan tidak ditemukan dari atribut data tombol modal.");
                    alert("Gagal memilih lowongan dari detail. Silakan coba pilih langsung dari kartu atau muat ulang detail.");
                }

                const detailModalEl = document.getElementById('detailModal');
                const detailModalInstance = bootstrap.Modal.getInstance(detailModalEl);
                if (detailModalInstance) {
                    detailModalInstance.hide();
                }
            });
        }

        if (typeof updateLowonganVisibility === "function") {
            updateLowonganVisibility();
        } else if (typeof filterLowongan === "function") {
            filterLowongan();
        }

        const tanggalMulai = document.getElementById('tanggal_mulai');
        const tanggalSelesai = document.getElementById('tanggal_selesai');
        if (tanggalMulai && tanggalSelesai) {
            tanggalMulai.addEventListener('change', function() {
                if (tanggalSelesai.value && tanggalSelesai.value < tanggalMulai.value) {
                    tanggalSelesai.value = tanggalMulai.value;
                }
                tanggalSelesai.min = tanggalMulai.value;
            });
        }

        const pengajuanForm = document.getElementById('pengajuanMagangForm');
        if(pengajuanForm){
            pengajuanForm.addEventListener('submit', function(e) {
                if (!document.getElementById('lowongan_id').value) {
                    e.preventDefault();
                    alert('Silakan pilih lowongan terlebih dahulu!');
                    return false;
                }
                // Hapus validasi tanggal min/max di sisi client sebelum submit jika sudah dihandle server,
                // atau pastikan min/max dari lowongan sudah benar di-set saat selectLowongan.
                // Validasi rentang tanggal vs. lowongan lebih baik di server.
                // Tapi validasi dasar (selesai >= mulai) bisa tetap di client.
                const startDate = new Date(tanggalMulai.value);
                const endDate = new Date(tanggalSelesai.value);

                if (endDate < startDate) {
                    e.preventDefault();
                    alert('Tanggal selesai tidak boleh sebelum tanggal mulai!');
                    return false;
                }
            });
        }

        @if (isset($selectedLowongan) && $selectedLowongan)
            selectLowongan('{{ $selectedLowongan->lowongan_id }}', '{{ htmlspecialchars($selectedLowongan->judul_lowongan, ENT_QUOTES) }}', '{{ \Carbon\Carbon::parse($selectedLowongan->tanggal_mulai)->format('Y-m-d') }}', '{{ \Carbon\Carbon::parse($selectedLowongan->tanggal_selesai)->format('Y-m-d') }}');
        @elseif (old('lowongan_id') && $lowonganList->firstWhere('lowongan_id', old('lowongan_id')))
            @php
                $oldLowongan = $lowonganList->firstWhere('lowongan_id', old('lowongan_id'));
            @endphp
            selectLowongan('{{ old('lowongan_id') }}', '{{ htmlspecialchars(optional($oldLowongan)->judul_lowongan, ENT_QUOTES) }}', '{{ optional($oldLowongan)->tanggal_mulai ? \Carbon\Carbon::parse($oldLowongan->tanggal_mulai)->format('Y-m-d') : '' }}', '{{ optional($oldLowongan)->tanggal_selesai ? \Carbon\Carbon::parse($oldLowongan->tanggal_selesai)->format('Y-m-d') : '' }}');
        @endif
    });
</script>
@endpush

@push('css')
<style>
    .w-48 { width: 48% !important; } /* Untuk tombol Pilih dan Detail sejajar */
    .card-blog .card-body h5 {
        min-height: 2.5em; /* Perkiraan 2 baris teks, sesuaikan dengan font size */
        line-height: 1.25em;
        overflow: hidden;
        text-overflow: ellipsis;
        /* display: -webkit-box; /* Non-standard, tapi sering dipakai */
        /* -webkit-line-clamp: 2; */
        /* -webkit-box-orient: vertical; */
    }
    .modal-dialog-scrollable .modal-body {
        font-size: 0.9rem;
    }
    .modal-body h4, .modal-body h5, .modal-body h6 {
        color: #344767; /* Warna teks default Soft UI */
    }
    .modal-body .badge {
        font-size: 0.8em;
    }
</style>
@endpush
