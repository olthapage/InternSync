@extends('layouts.template')

@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Ajukan Magang Baru</h2>

            {{-- Tombol Lihat Rekomendasi --}}
            <div class="mb-3">
                <a href="#" class="btn btn-sm btn-outline-info">Lihat Hasil Rekomendasi!</a>
            </div>
            {{-- Form Pengajuan --}}
            <form action="{{ route('mahasiswa.pengajuan.store') }}" method="POST">
                @csrf

                {{-- Filter Section --}}
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="industri_id" class="form-label">Filter Industri</label>
                        <select id="industri_id" name="industri_id" class="form-select" onchange="filterLowongan()">
                            <option value="">-- Semua Industri --</option>
                            @foreach ($industriList as $industri)
                                <option value="{{ $industri->industri_id }}">{{ $industri->industri_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label for="kategori_skill_id" class="form-label">Filter Kategori Skill</label>
                        <select id="kategori_skill_id" name="kategori_skill_id" class="form-select"
                            onchange="filterLowongan()">
                            <option value="">-- Semua Kategori --</option>
                            @foreach ($kategoriSkillList as $kategori)
                                <option value="{{ $kategori->kategori_skill_id }}">{{ $kategori->kategori_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Container untuk menampilkan daftar lowongan --}}
                <div id="lowongan-container" class="row mt-4 mb-4">
                    @foreach ($lowonganList as $index => $lowongan)
                        <div class="col-xl-3 col-md-6 mb-4 lowongan-card" data-industri="{{ $lowongan->industri_id }}"
                            data-kategori="{{ $lowongan->kategori_skill_id }}" data-index="{{ $index }}"
                            style="{{ $index >= 5 ? 'display: none;' : '' }}">
                            <div class="card card-blog card-plain border rounded">
                                <div class="position-relative">
                                    <div class="image-container">
                                        <img src="{{ $lowongan->industri->logo ? asset('storage/logo_industri/' . $lowongan->industri->logo) : asset('assets/default-industri.png') }}"
                                            alt="Lowongan Image" class="img-fluid border-radius-lg px-3 pt-4 rounded"
                                            style="max-height: 120px; width: auto; display: block; margin: 0 auto;">
                                    </div>
                                </div>
                                <div class="card-body px-3 pb-2">
                                    <p class="text-gradient text-primary mb-1 text-sm">
                                        {{ $lowongan->industri->industri_nama ?? '-' }}</p>
                                    <h5 class="font-weight-bold mb-2">
                                        {{ $lowongan->judul_lowongan }}
                                    </h5>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-calendar-alt text-primary me-2"></i>
                                        <span
                                            class="text-sm">{{ \Carbon\Carbon::parse($lowongan->tanggal_mulai)->format('d/m/Y') }}
                                            -
                                            {{ \Carbon\Carbon::parse($lowongan->tanggal_selesai)->format('d/m/Y') }}</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="fas fa-tag text-primary me-2"></i>
                                        <span
                                            class="text-sm">{{ $lowongan->kategoriSkill->kategori_skill_nama ?? 'Umum' }}</span>
                                    </div>
                                    <div class="mb-3">
                                        <p class="text-sm mb-2 d-flex">
                                            <i class="fas fa-info-circle text-primary me-2 mt-1"></i>
                                            <span class="deskripsi-terbatas">{{ $lowongan->deskripsi }}</span>
                                        </p>
                                    </div>

                                    <div class="d-flex align-items-center justify-content-between">
                                        <button type="button" class="btn btn-primary btn-sm mb-0"
                                            onclick="selectLowongan('{{ $lowongan->lowongan_id }}', '{{ $lowongan->judul_lowongan }}')">
                                            <i class="fas fa-check me-1"></i> Pilih
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm mb-0"
                                            onclick="showDetail('{{ $lowongan->lowongan_id }}')">
                                            <i class="fas fa-eye me-1"></i> Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Tombol Lihat Semua Lowongan --}}
                <div id="view-all-container" class="text-center mb-4"
                    style="display: {{ count($lowonganList) > 5 ? 'block' : 'none' }};">
                    <button type="button" id="view-all-btn" class="btn btn-outline-primary" onclick="toggleViewAll()">
                        <i class="fas fa-th-list me-1"></i> Lihat Semua Lowongan
                    </button>
                </div>

                {{-- Hidden field untuk menyimpan ID lowongan yang dipilih --}}
                <input type="hidden" id="lowongan_id" name="lowongan_id" required>
                <div id="selected-lowongan" class="mb-3 p-3 border rounded" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>Lowongan Terpilih:</strong>
                            <span id="selected-lowongan-title"></span>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="clearSelection()">
                            <i class="fas fa-times"></i> Batal
                        </button>
                    </div>
                </div>

                {{-- Date Range Info --}}
                <div id="date-range-info" class="alert alert-info mb-3" style="display: none;">
                    <i class="fas fa-info-circle me-2"></i>
                    <span>Periode magang Anda harus dalam rentang waktu lowongan:</span>
                    <strong id="lowongan-date-range"></strong>
                </div>

                {{-- Tanggal Mulai & Selesai --}}
                <div class="mb-3">
                    <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="tanggal_mulai" name="tanggal_mulai" class="form-control" required>
                    <small class="text-muted">Tanggal mulai magang Anda</small>
                </div>

                <div class="mb-3">
                    <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
                    <input type="date" id="tanggal_selesai" name="tanggal_selesai" class="form-control" required>
                    <small class="text-muted">Tanggal selesai magang Anda</small>
                </div>

                <button type="submit" class="btn btn-primary">Ajukan</button>
                <a href="{{ route('mahasiswa.pengajuan.index') }}" class="btn btn-dark">Kembali</a>
            </form>
        </div>
    </div>

    {{-- Modal Detail Lowongan --}}
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Detail Lowongan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="detailModalBody">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" id="pilihDariModal">Pilih Lowongan Ini</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Status untuk tracking apakah semua lowongan ditampilkan
        let showAllLowongan = false;
        let filteredCount = 0;

        // Fungsi untuk toggle tampilan semua lowongan
        function toggleViewAll() {
            showAllLowongan = !showAllLowongan;
            const viewAllBtn = document.getElementById('view-all-btn');

            // Update tampilan kartu lowongan
            updateLowonganVisibility();

            // Update teks tombol
            if (showAllLowongan) {
                viewAllBtn.innerHTML = '<i class="fas fa-compress-alt me-1"></i> Tampilkan Lebih Sedikit';
            } else {
                viewAllBtn.innerHTML = '<i class="fas fa-th-list me-1"></i> Lihat Semua Lowongan';
            }
        }

        // Fungsi untuk update tampilan kartu lowongan
        function updateLowonganVisibility() {
            const cards = document.querySelectorAll('.lowongan-card');
            let visibleCount = 0;

            cards.forEach((card, index) => {
                const industriId = document.getElementById('industri_id').value;
                const kategoriId = document.getElementById('kategori_skill_id').value;
                const cardIndustriId = card.dataset.industri;
                const cardKategoriId = card.dataset.kategori;
                const cardIndex = parseInt(card.dataset.index);

                // Logic untuk menampilkan/menyembunyikan berdasarkan filter
                const matchesIndustri = !industriId || cardIndustriId === industriId;
                const matchesKategori = !kategoriId || cardKategoriId === kategoriId;

                if (matchesIndustri && matchesKategori) {
                    visibleCount++;

                    if (showAllLowongan || visibleCount <= 4) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                } else {
                    card.style.display = 'none';
                }
            });

            filteredCount = visibleCount;

            // Update tampilan tombol view all
            const viewAllContainer = document.getElementById('view-all-container');
            if (visibleCount > 5) {
                viewAllContainer.style.display = 'block';
            } else {
                viewAllContainer.style.display = 'none';
            }
        }

        // Fungsi untuk filter lowongan berdasarkan industri dan kategori skill
        function filterLowongan() {
            // Reset showAllLowongan ketika filter berubah
            showAllLowongan = false;

            // Update tombol ke status awal
            const viewAllBtn = document.getElementById('view-all-btn');
            viewAllBtn.innerHTML = '<i class="fas fa-th-list me-1"></i> Lihat Semua Lowongan';

            // Update tampilan lowongan
            updateLowonganVisibility();

            // Clear selection when filter changes
            clearSelection();
        }

        // Fungsi untuk memilih lowongan
        function selectLowongan(id, title) {
            document.getElementById('lowongan_id').value = id;
            document.getElementById('selected-lowongan-title').textContent = title;
            document.getElementById('selected-lowongan').style.display = 'block';

            // Highlight selected card
            const cards = document.querySelectorAll('.lowongan-card');
            cards.forEach(card => {
                const cardLowonganId = card.querySelector('button').getAttribute('onclick').split("'")[1];
                if (cardLowonganId === id) {
                    card.querySelector('.card').classList.add('border-primary');

                    // Ambil tanggal dari kartu yang dipilih
                    const dateText = card.querySelector('.d-flex .text-sm').textContent;
                    const dates = dateText.match(/(\d{2}\/\d{2}\/\d{4})/g);

                    if (dates && dates.length === 2) {
                        // Tampilkan informasi rentang tanggal
                        document.getElementById('lowongan-date-range').textContent =
                            ` ${dates[0]} sampai ${dates[1]}`;
                        document.getElementById('date-range-info').style.display = 'block';

                        // Konversi format tanggal DD/MM/YYYY ke YYYY-MM-DD untuk input date
                        const startParts = dates[0].split('/');
                        const endParts = dates[1].split('/');

                        const startDate = `${startParts[2]}-${startParts[1]}-${startParts[0]}`;
                        const endDate = `${endParts[2]}-${endParts[1]}-${endParts[0]}`;

                        // Set min dan max untuk input date
                        document.getElementById('tanggal_mulai').min = startDate;
                        document.getElementById('tanggal_mulai').max = endDate;
                        document.getElementById('tanggal_selesai').min = startDate;
                        document.getElementById('tanggal_selesai').max = endDate;
                    }
                } else {
                    card.querySelector('.card').classList.remove('border-primary');
                }
            });
        }

        // Fungsi untuk membatalkan pilihan
        function clearSelection() {
            document.getElementById('lowongan_id').value = '';
            document.getElementById('selected-lowongan').style.display = 'none';
            document.getElementById('date-range-info').style.display = 'none';

            // Reset date inputs
            document.getElementById('tanggal_mulai').min = '';
            document.getElementById('tanggal_mulai').max = '';
            document.getElementById('tanggal_selesai').min = '';
            document.getElementById('tanggal_selesai').max = '';

            // Remove highlights
            const cards = document.querySelectorAll('.lowongan-card .card');
            cards.forEach(card => card.classList.remove('border-primary'));
        }

        // Fungsi untuk menampilkan detail lowongan
        function showDetail(lowonganId) {
            // Store the lowongan ID for later use
            document.getElementById('pilihDariModal').setAttribute('data-id', lowonganId);

            // Show the modal
            const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));
            detailModal.show();

            // Here you would typically load the detail via AJAX
            // For this example, I'll just simulate it with setTimeout
            setTimeout(() => {
                // Fetch the lowongan data from your data source
                // This is a simplified example, you'd need to implement actual data fetching
                fetchLowonganDetail(lowonganId);
            }, 500);
        }

        // Simulate fetching lowongan detail
        function fetchLowonganDetail(lowonganId) {
            // In a real implementation, you'd use fetch or axios to get data from the server
            // For now, we'll just find the lowongan in our existing data
            const lowonganCards = document.querySelectorAll('.lowongan-card');
            let lowonganTitle = '';

            lowonganCards.forEach(card => {
                const cardLowonganId = card.querySelector('button').getAttribute('onclick').split("'")[1];
                if (cardLowonganId === lowonganId) {
                    lowonganTitle = card.querySelector('h5').textContent.trim();
                }
            });

            // Update modal content
            document.getElementById('detailModalBody').innerHTML = `
                <h4>${lowonganTitle}</h4>
                <p>Detail lengkap lowongan akan ditampilkan di sini. Dalam implementasi sebenarnya,
                Anda perlu mengambil data dari server menggunakan AJAX.</p>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <h5>Persyaratan:</h5>
                        <ul>
                            <li>Persyaratan 1</li>
                            <li>Persyaratan 2</li>
                            <li>Persyaratan 3</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5>Keahlian yang Dibutuhkan:</h5>
                        <div class="mb-2">
                            <span class="badge bg-info me-1">Skill 1</span>
                            <span class="badge bg-info me-1">Skill 2</span>
                            <span class="badge bg-info me-1">Skill 3</span>
                        </div>
                    </div>
                </div>
            `;
        }

        // Event listener for the "Pilih Lowongan Ini" button in modal
        document.getElementById('pilihDariModal').addEventListener('click', function() {
            const lowonganId = this.getAttribute('data-id');
            const lowonganTitle = document.getElementById('detailModalBody').querySelector('h4').textContent;

            selectLowongan(lowonganId, lowonganTitle);

            // Close the modal
            bootstrap.Modal.getInstance(document.getElementById('detailModal')).hide();
        });

        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            // Run the filter on page load
            filterLowongan();

            // Add validation for date inputs
            const tanggalMulai = document.getElementById('tanggal_mulai');
            const tanggalSelesai = document.getElementById('tanggal_selesai');

            tanggalMulai.addEventListener('change', function() {
                // Ensure tanggal_selesai is after or equal to tanggal_mulai
                if (tanggalSelesai.value && tanggalSelesai.value < tanggalMulai.value) {
                    tanggalSelesai.value = tanggalMulai.value;
                }

                // Set min value for tanggal_selesai
                tanggalSelesai.min = tanggalMulai.value;
            });

            // Form validation before submit
            document.querySelector('form').addEventListener('submit', function(e) {
                if (!document.getElementById('lowongan_id').value) {
                    e.preventDefault();
                    alert('Silakan pilih lowongan terlebih dahulu!');
                    return false;
                }

                const startDate = new Date(tanggalMulai.value);
                const endDate = new Date(tanggalSelesai.value);

                if (startDate > endDate) {
                    e.preventDefault();
                    alert('Tanggal selesai harus setelah tanggal mulai!');
                    return false;
                }

                // Check if dates are within the allowed range
                if (tanggalMulai.min && tanggalMulai.value < tanggalMulai.min) {
                    e.preventDefault();
                    alert('Tanggal mulai harus dalam rentang tanggal lowongan!');
                    return false;
                }

                if (tanggalMulai.max && tanggalMulai.value > tanggalMulai.max) {
                    e.preventDefault();
                    alert('Tanggal mulai harus dalam rentang tanggal lowongan!');
                    return false;
                }

                if (tanggalSelesai.min && tanggalSelesai.value < tanggalSelesai.min) {
                    e.preventDefault();
                    alert('Tanggal selesai harus dalam rentang tanggal lowongan!');
                    return false;
                }

                if (tanggalSelesai.max && tanggalSelesai.value > tanggalSelesai.max) {
                    e.preventDefault();
                    alert('Tanggal selesai harus dalam rentang tanggal lowongan!');
                    return false;
                }
            });
        });
        @if (isset($selectedLowongan))
            // Auto-pilih lowongan dari controller
            document.addEventListener('DOMContentLoaded', function() {
                selectLowongan('{{ $selectedLowongan->lowongan_id }}', '{{ $selectedLowongan->judul_lowongan }}');
            });
        @endif
    </script>
@endsection

@push('css')
    <style>
        /* CSS untuk konsistensi tinggi card */
        .lowongan-card .card {
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Card body mengisi ruang kosong */
        .lowongan-card .card-body {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Deskripsi terpotong dengan "..." */
        .lowongan-card .card-body .deskripsi-terbatas {
            overflow: hidden;
            text-overflow: ellipsis;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            /* Jumlah baris maksimal */
            -webkit-box-orient: vertical;
            line-height: 1.4em;
            max-height: 4.2em;
            /* 1.4em * 3 lines */
        }
    </style>
@endpush
