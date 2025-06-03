@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-dark shadow-sm mb-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Detail Lowongan: {{ $lowongan->judul_lowongan }}</h3>
                        <div class="d-flex align-items-center">
                            {{-- Menambahkan Status Pendaftaran di Header Card Detail Lowongan --}}
                            @if ($lowongan->pendaftaran_tanggal_mulai && $lowongan->pendaftaran_tanggal_selesai)
                                <span class="badge badge-{{ $lowongan->status_pendaftaran_badge_class }} mr-3"
                                    style="font-size: 0.9rem;">
                                    {{ $lowongan->status_pendaftaran_text }}
                                </span>
                            @endif
                            <a href="{{ route('industri.lowongan.index') }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Kembali
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p><strong>Industri:</strong> {{ $lowongan->industri->industri_nama }}</p>
                        <p><strong>Kategori:</strong> {{ $lowongan->kategoriSkill->kategori_nama ?? 'Umum' }}</p>
                        <p><strong>Slot Tersedia:</strong> {{ $lowongan->slotTersedia() }} dari {{ $lowongan->slot }}</p>

                        {{-- Periode Pelaksanaan Magang --}}
                        <p><strong>Periode Pelaksanaan Magang:</strong>
                            @if ($lowongan->tanggal_mulai && $lowongan->tanggal_selesai)
                                {{ $lowongan->tanggal_mulai->isoFormat('D MMMM YYYY') }} -
                                {{ $lowongan->tanggal_selesai->isoFormat('D MMMM YYYY') }}
                            @else
                                Belum diatur
                            @endif
                        </p>

                        {{-- Periode Pendaftaran --}}
                        <p><strong>Periode Pendaftaran:</strong>
                            @if ($lowongan->pendaftaran_tanggal_mulai && $lowongan->pendaftaran_tanggal_selesai)
                                {{ $lowongan->pendaftaran_tanggal_mulai->isoFormat('D MMMM YYYY') }} -
                                {{ $lowongan->pendaftaran_tanggal_selesai->isoFormat('D MMMM YYYY') }}
                            @else
                                Belum diatur
                            @endif
                        </p>

                        {{-- Status Pendaftaran (jika tidak di header) --}}
                        {{-- <p><strong>Status Pendaftaran:</strong>
                            @if ($lowongan->pendaftaran_tanggal_mulai && $lowongan->pendaftaran_tanggal_selesai)
                                <span class="badge badge-{{ $lowongan->status_pendaftaran_badge_class }}">{{ $lowongan->status_pendaftaran_text }}</span>
                            @else
                                <span class="badge badge-secondary">Periode Belum Diatur</span>
                            @endif
                        </p> --}}

                        <div>
                            <strong>Deskripsi:</strong>
                            {!! $lowongan->deskripsi !!}
                        </div>
                        @if ($lowongan->lowonganSkill->isNotEmpty())
                            <div class="mt-3">
                                <strong>Skill yang Dibutuhkan:</strong>
                                <ul>
                                    @foreach ($lowongan->lowonganSkill as $item)
                                        <li>{{ $item->skill->skill_nama ?? 'Skill tidak tersedia' }}</li>
                                        {{-- Asumsi ada relasi skill() di LowonganSkillModel dan nama_skill di SkillModel --}}
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{-- Tambahkan detail lowongan lainnya jika perlu --}}
                    </div>
                </div>

                {{-- Card Daftar Pendaftar --}}
                <div class="card border-dark shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Pendaftar ({{ $lowongan->pendaftar->count() }})</h5>
                        <a href="#" class="btn btn-info" data-bs-toggle="modal"
                            data-bs-target="#rekomendasiSpkModal-{{ $lowongan->lowongan_id }}"
                            id="btnLihatRekomendasi-{{ $lowongan->lowongan_id }}">Lihat Rekomendasi</a>
                    </div>

                    <div class="card-body">
                        @if ($lowongan->pendaftar->isEmpty())
                            <div class="alert alert-secondary text-center">
                                <i class="fas fa-info-circle mr-2"></i>
                                Belum ada mahasiswa yang mendaftar pada lowongan ini.
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover align-items-center mb-0 text-center">
                                    {{-- ... isi tabel pendaftar ... --}}
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Mahasiswa</th>
                                            <th>Email Mahasiswa</th>
                                            <th>NIM</th>
                                            <th>Tanggal Pengajuan</th>
                                            <th>Status Pengajuan</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($lowongan->pendaftar as $index => $pengajuan)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $pengajuan->mahasiswa->nama_lengkap ?? 'Nama Tidak Tersedia' }}</td>
                                                <td>{{ $pengajuan->mahasiswa->email ?? '-' }}</td>
                                                <td>{{ $pengajuan->mahasiswa->nim ?? '-' }}</td>
                                                <td>{{ $pengajuan->created_at ? \Carbon\Carbon::parse($pengajuan->created_at)->isoFormat('D MMM YY, HH:mm') : '-' }}
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge text-dark badge-{{ $pengajuan->status == 'diterima' ? 'success' : ($pengajuan->status == 'ditolak' ? 'danger' : 'warning') }}">
                                                        {{ ucfirst($pengajuan->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{-- MODIFIKASI TOMBOL AKSI --}}
                                                    <a href="{{ route('industri.lowongan.pendaftar.show_profil', $pengajuan->pengajuan_id) }}"
                                                        class="btn btn-info btn-sm" title="Lihat Profil & Skill Pendaftar">
                                                        <i class="fas fa-user-check me-1"></i> Review
                                                    </a>
                                                    {{-- Tambahkan tombol aksi lain jika perlu (misal: Terima, Tolak, Wawancara) --}}
                                                    {{-- Tombol-tombol ini bisa berupa form POST atau link ke controller action lain --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="modal fade" id="rekomendasiSpkModal-{{ $lowongan->lowongan_id }}" tabindex="-1"
        aria-labelledby="rekomendasiSpkModalLabel-{{ $lowongan->lowongan_id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable"> {{-- modal-xl untuk lebih lebar --}}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rekomendasiSpkModalLabel-{{ $lowongan->lowongan_id }}">
                        <i class="fas fa-cogs me-2"></i> SPK Rekomendasi Pendaftar
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="rekomendasiSpkModalBody-{{ $lowongan->lowongan_id }}">
                    {{-- Konten kriteria dan hasil akan dimuat di sini via AJAX --}}
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2 mb-0">Memuat kriteria SPK...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-1"></i> Tutup
                    </button>
                    {{-- Tombol hitung akan ada di dalam form yang di-load AJAX --}}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('css')
    {{-- Jika ada CSS tambahan khusus halaman ini --}}
    <style>
        .card-header h5 {
            font-weight: 600;
        }
    </style>
@endpush

@push('js')
    {{-- Jika ada JS tambahan khusus halaman ini --}}
    <script>
        $(document).ready(function() {
            var lowonganId = '{{ $lowongan->lowongan_id }}';
            var modalId = '#rekomendasiSpkModal-' + lowonganId;
            var modalBodyId = '#rekomendasiSpkModalBody-' + lowonganId;

            $(modalId).on('show.bs.modal', function(event) {
                var modal = $(this);
                var modalBody = $(modalBodyId);

                // Tampilkan spinner default
                modalBody.html(
                    '<div class="text-center py-5"><div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;"><span class="visually-hidden">Loading...</span></div><p class="mt-2 mb-0">Memuat kriteria SPK...</p></div>'
                );

                $.ajax({
                    url: '{{ route('industri.lowongan.spk.get_kriteria_form', ['lowongan' => $lowongan->lowongan_id]) }}',
                    type: 'GET',
                    success: function(response) {
                        modalBody.html(response);
                    },
                    error: function(xhr) {
                        modalBody.html(
                            '<div class="alert alert-danger m-3">Gagal memuat kriteria SPK. Silakan tutup dan coba lagi. Error: ' +
                            (xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON
                                .message : xhr.statusText) + '</div>');
                        console.error(xhr);
                    }
                });
            });

            // Event delegation untuk form submit di dalam modal (karena form di-load AJAX)
            $(document).on('submit', '#formSpkKriteria-' + lowonganId, function(e) {
                e.preventDefault(); // Mencegah submit form standar

                var form = $(this);
                var formData = form.serialize();
                var resultArea = $('#spkResultArea-' + lowonganId);
                var submitButton = form.find('button[type="submit"]');
                var originalButtonText = submitButton.html();

                resultArea.html(
                    '<div class="text-center mt-3"><div class="spinner-border text-success" role="status"></div><p class="mt-2 mb-0">Menghitung rekomendasi...</p></div>'
                );
                submitButton.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghitung...'
                ).prop('disabled', true);

                $.ajax({
                    url: '{{ route('industri.lowongan.spk.calculate', ['lowongan' => $lowongan->lowongan_id]) }}',
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        resultArea.html(response);
                        submitButton.html(originalButtonText).prop('disabled', false);
                        // Scroll ke hasil jika perlu
                        if (resultArea.length) {
                            $(modalBodyId).animate({
                                scrollTop: resultArea.offset().top - $(modalBodyId)
                                    .offset().top + $(modalBodyId).scrollTop() -
                                    20 // offset 20px
                            }, 500);
                        }
                    },
                    error: function(xhr) {
                        var errorMessage = "Terjadi kesalahan.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                            if (xhr.responseJSON.errors) {
                                let errors = xhr.responseJSON.errors;
                                errorMessage += "<ul class='text-start ps-3 mt-2 mb-0'>";
                                for (let key in errors) {
                                    errors[key].forEach(function(msg) {
                                        errorMessage += "<li>" + msg + "</li>";
                                    });
                                }
                                errorMessage += "</ul>";
                            }
                        } else {
                            errorMessage = "Gagal menghitung rekomendasi. Status: " + xhr
                                .statusText;
                        }
                        resultArea.html('<div class="alert alert-danger">' + errorMessage +
                            '</div>');
                        submitButton.html(originalButtonText).prop('disabled', false);
                        console.error(xhr);
                    }
                });
            });

            // Event listener untuk tombol "Lihat Langkah Perhitungan" (menggunakan event delegation)
            $(document).on('click', '[id^="btnLihatLangkahEdas-"]', function() {
                const lowonganId = $(this).data('lowongan-id'); // Ambil lowongan_id dari data attribute
                const stepsContainer = $('#edasStepsContainer-' + lowonganId);
                const stepsContent = $('#edasStepsContent-' + lowonganId);
                const formKriteria = $('#formSpkKriteria-' + lowonganId); // Ambil form kriteria

                if (!lowonganId || !formKriteria.length) {
                    console.error(
                        "Lowongan ID atau Form Kriteria tidak ditemukan untuk mengambil langkah SPK.");
                    stepsContent.html(
                        '<p class="text-danger">Gagal memuat langkah: ID Lowongan tidak ditemukan.</p>');
                    stepsContainer.slideDown();
                    return;
                }

                // Kumpulkan data bobot yang saat ini ada di form
                const formData = formKriteria.serialize();

                stepsContent.html(
                    '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div><p class="mt-2 mb-0 small">Memuat langkah perhitungan...</p></div>'
                    );
                stepsContainer.slideToggle(); // Toggle tampilan

                if (stepsContainer.is(':visible')) {
                    $.ajax({
                        url: '{{ route('industri.lowongan.spk.get_langkah_edas', ['lowongan' => ':lowonganIdPlaceholder']) }}'
                            .replace(':lowonganIdPlaceholder', lowonganId),
                        type: 'POST', // Gunakan POST untuk mengirim data bobot
                        data: formData, // Kirim bobot yang sedang digunakan
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                'content') // Jangan lupa CSRF token
                        },
                        success: function(response) {
                            stepsContent.html(response);
                        },
                        error: function(xhr) {
                            stepsContent.html(
                                '<div class="alert alert-warning small">Gagal memuat detail langkah perhitungan. (' +
                                (xhr.responseJSON ? xhr.responseJSON.message : xhr
                                    .statusText) + ')</div>');
                            console.error("Error fetching EDAS steps:", xhr);
                        }
                    });
                }
            });

        });

        // Fungsi toggle IPK (didefinisikan global agar bisa dipanggil dari konten AJAX)
        function toggleIpkWeightDynamic(checkboxElement, lowonganId) {
            var bobotIpkDiv = document.getElementById('bobot_ipk_div-' + lowonganId);
            var bobotIpkInput = document.getElementById('bobot_ipk-' + lowonganId);
            if (bobotIpkDiv && bobotIpkInput) {
                bobotIpkDiv.style.display = checkboxElement.checked ? 'block' : 'none';
                if (!checkboxElement.checked) {
                    bobotIpkInput.value = ''; // Kosongkan nilai jika tidak dicentang
                    bobotIpkInput.required = false;
                } else {
                    bobotIpkInput.required = true; // Jadikan required jika dicentang
                }
            }
        }
    </script>
@endpush
