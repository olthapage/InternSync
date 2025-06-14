@extends('layouts.template')

@section('content')
    <div class="container-fluid">
        <a href="{{ route('industri.lowongan.index') }}" class="btn btn-white btn-sm mb-3">
            <i class="fas fa-arrow-left me-2"></i>
            Kembali
        </a>
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

                            {{-- ================================================================== --}}
                            {{-- MODIFIKASI DIMULAI DISINI: TOMBOL CRUD JIKA BELUM ADA PENDAFTAR --}}
                            {{-- ================================================================== --}}
                            @if ($lowongan->pendaftar->isEmpty())
                                <div class="btn-group">
                                    <button type="button" class="btn btn-dark dropdown-toggle btn-sm"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-cogs me-1"></i> Kelola Lowongan
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                data-bs-target="#editLowonganModal">
                                                <i class="fas fa-edit me-2"></i>Edit Lowongan
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <a class="dropdown-item text-danger" href="#" data-bs-toggle="modal"
                                                data-bs-target="#deleteLowonganModal">
                                                <i class="fas fa-trash me-2"></i>Hapus Lowongan
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                            {{-- ================================================================== --}}
                            {{-- MODIFIKASI SELESAI --}}
                            {{-- ================================================================== --}}
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
                        <hr>
                        <div class="row">
                            {{-- Menampilkan Upah --}}
                            <div class="col-md-4">
                                <p>
                                    <i class="fas fa-money-bill-wave text-success me-2"></i>
                                    <strong>Uang Saku / Upah:</strong><br>
                                    @if ($lowongan->upah > 0)
                                        Rp {{ number_format($lowongan->upah, 0, ',', '.') }} / bulan
                                    @else
                                        Tidak disediakan
                                    @endif
                                </p>
                            </div>

                            {{-- Menampilkan Tipe Kerja --}}
                            <div class="col-md-8">
                                <p>
                                    <i class="fas fa-briefcase text-primary me-2"></i>
                                    <strong>Tipe Kerja:</strong><br>
                                    @if ($lowongan->tipeKerja->isNotEmpty())
                                        @foreach ($lowongan->tipeKerja as $tipe)
                                            <span class="badge bg-primary me-1">{{ $tipe->nama_tipe_kerja }}</span>
                                        @endforeach
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>

                        {{-- Menampilkan Fasilitas --}}
                        @if ($lowongan->fasilitas->isNotEmpty())
                            <div class="mt-2">
                                <strong><i class="fas fa-gifts text-info me-2"></i>Fasilitas yang Disediakan:</strong>
                                <ul class="list-unstyled mt-2 row">
                                    @foreach ($lowongan->fasilitas as $fasilitas)
                                        <li class="col-md-6 mb-2">
                                            <i class="fas fa-check-circle text-success me-1"></i>
                                            {{ $fasilitas->nama_fasilitas }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <hr>
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
                        <a href="#" class="btn btn-info text-white" data-bs-toggle="modal"
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
                                                        class="badge text-white bg-{{ $pengajuan->status == 'diterima' ? 'gradient-success' : ($pengajuan->status == 'ditolak' ? 'gradient-danger' : 'gradient-warning') }}">
                                                        {{ ucfirst($pengajuan->status) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{-- MODIFIKASI TOMBOL AKSI --}}
                                                    <a href="{{ route('industri.lowongan.pendaftar.show_profil', [
                                                        'pengajuan' => $pengajuan->pengajuan_id,
                                                        'from' => 'detail', // Menandakan datang dari halaman detail lowongan
                                                    ]) }}"
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

    @if ($lowongan->pendaftar->isEmpty())
        <div class="modal fade" id="editLowonganModal" tabindex="-1" aria-labelledby="editLowonganModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editLowonganModalLabel">Edit Lowongan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="editLowonganModalBody">
                        {{-- Konten form edit akan dimuat di sini via AJAX --}}
                        <div class="text-center py-5">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat form edit...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="deleteLowonganModal" tabindex="-1" aria-labelledby="deleteLowonganModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteLowonganModalLabel">Konfirmasi Hapus Lowongan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus lowongan <strong>"{{ $lowongan->judul_lowongan }}"</strong>?
                        </p>
                        <p class="text-danger">Tindakan ini tidak dapat diurungkan.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <form action="{{ route('industri.lowongan.destroy', $lowongan->lowongan_id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">Ya, Hapus Lowongan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    @endif

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
            // KODE BARU YANG DIPERBAIKI
            $(document).on('submit', '#formSpkKriteria-' + lowonganId, function(e) {
                e.preventDefault(); // Mencegah submit form standar

                var form = $(this);
                var formData = form.serialize();
                var resultArea = $('#spkResultArea-' + lowonganId);
                var submitButton = form.find('button[type="submit"]');
                var originalButtonText =
                    ' <i class="fas fa-calculator me-1"></i> Hitung Ulang Rekomendasi'; // Simpan teks baru

                // Tampilkan spinner
                resultArea.html(
                    '<div class="text-center mt-3"><div class="spinner-border text-success" role="status"></div><p class="mt-2 mb-0">Menghitung rekomendasi...</p></div>'
                );
                submitButton.html(
                    '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menghitung...'
                ).prop('disabled', true);

                $.ajax({
                    url: form.attr('action'), // Ambil URL dari atribut action form
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        // 1. Tampilkan response (view hasil) ke dalam div resultArea
                        resultArea.html(response);

                        // 2. Kembalikan tombol ke keadaan semula dengan teks baru
                        submitButton.html(originalButtonText).prop('disabled', false);

                        // 3. (Opsional) Scroll ke area hasil agar langsung terlihat
                        $(modalBodyId).animate({
                            scrollTop: resultArea.offset().top - $(modalBodyId).offset()
                                .top + $(modalBodyId).scrollTop()
                        }, 500);
                    },
                    error: function(xhr) {
                        // ... (logika error Anda sudah cukup baik)
                        var errorMessage = "Gagal menghitung rekomendasi.";
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                            if (xhr.responseJSON.errors) {
                                /* ... logika tampilkan error validasi ... */
                            }
                        } else {
                            errorMessage = "Status: " + xhr.statusText;
                        }
                        resultArea.html('<div class="alert alert-danger">' + errorMessage +
                            '</div>');
                        submitButton.html(originalButtonText).prop('disabled',
                            false); // Kembalikan tombol jika error
                        console.error(xhr);
                    }
                });
            });


            // Event listener untuk tombol "Lihat Langkah Perhitungan" (menggunakan event delegation)
            // ==========================================================
            // PERBAIKI EVENT LISTENER INI
            // ==========================================================
            // Event listener untuk tombol "Lihat Langkah Perhitungan"
            $(document).on('click', '#btnLihatLangkahSpk-' + lowonganId, function() {
                const stepsContainer = $('#spkStepsContainer-' + lowonganId);
                const stepsContent = $('#spkStepsContent-' + lowonganId);
                const formKriteria = $('#formSpkKriteria-' + lowonganId);

                if (!formKriteria.length) {
                    console.error("Form kriteria tidak ditemukan.");
                    return;
                }

                const formData = formKriteria.serialize();

                // Toggle tampilan dan muat konten jika container terlihat
                stepsContainer.slideToggle();
                if (stepsContainer.is(':visible')) {
                    stepsContent.html(
                        '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"></div><p class="mt-2 mb-0 small">Memuat...</p></div>'
                        );

                    $.ajax({
                        // Pastikan route ini benar
                        url: '{{ route('industri.lowongan.spk.get_langkah_edas', ['lowongan' => $lowongan->lowongan_id]) }}',
                        type: 'POST',
                        data: formData, // Kirim bobot saat ini dari form
                        success: function(response) {
                            stepsContent.html(response);
                        },
                        error: function(xhr) {
                            stepsContent.html(
                                '<div class="alert alert-warning small">Gagal memuat detail langkah perhitungan.</div>'
                                );
                            console.error("Error fetching SPK steps:", xhr);
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

    {{-- ================================================================== --}}
    {{-- MODIFIKASI DIMULAI DISINI: JAVASCRIPT UNTUK MODAL CRUD --}}
    {{-- ================================================================== --}}
    @if ($lowongan->pendaftar->isEmpty())
        <script>
            $(document).ready(function() {
                // Event listener untuk saat modal edit akan ditampilkan
                $('#editLowonganModal').on('show.bs.modal', function(event) {
                    var modal = $(this);
                    var modalBody = $('#editLowonganModalBody');
                    var url = "{{ route('industri.lowongan.edit', $lowongan->lowongan_id) }}";

                    // Ambil konten form dari controller via AJAX
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(response) {
                            modalBody.html(response);
                            // Inisialisasi ulang plugin (jika ada, misal: select2, datepicker)
                            // Contoh: $('.select2-edit').select2();
                        },
                        error: function(xhr) {
                            modalBody.html(
                                '<div class="alert alert-danger">Gagal memuat form. Silakan coba lagi.</div>'
                            );
                            console.error(xhr);
                        }
                    });
                });

                // Event delegation untuk submit form yang diload via AJAX
                $(document).on('submit', '#editLowonganForm', function(e) {
                    e.preventDefault();

                    var form = $(this);
                    var url = form.attr('action');
                    var formData = new FormData(this);
                    var submitButton = form.find('button[type="submit"]');
                    var originalButtonText = submitButton.html();

                    // Tampilkan spinner pada tombol submit
                    submitButton.html(
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...'
                    ).prop('disabled', true);

                    // Hapus pesan error sebelumnya
                    $('.form-control, .form-select').removeClass('is-invalid');
                    $('.invalid-feedback').remove();

                    $.ajax({
                        type: 'POST', // Form method spoofing akan ditangani Laravel (_method: 'PUT')
                        url: url,
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            // Tutup modal
                            $('#editLowonganModal').modal('hide');

                            // Tampilkan notifikasi sukses (contoh menggunakan alert, bisa diganti SweetAlert)
                            alert(response.success);

                            // Reload halaman untuk melihat perubahan
                            location.reload();
                        },
                        error: function(xhr) {
                            // Kembalikan tombol ke keadaan semula
                            submitButton.html(originalButtonText).prop('disabled', false);

                            if (xhr.status === 422) { // Error validasi
                                var errors = xhr.responseJSON.errors;
                                $.each(errors, function(key, value) {
                                    var field = $('[name="' + key + '"], [name="' + key +
                                        '[]"]');
                                    field.addClass('is-invalid');
                                    field.after('<div class="invalid-feedback">' + value[
                                        0] + '</div>');
                                });
                                alert(
                                    'Terdapat kesalahan pada input Anda. Silakan periksa kembali.'
                                );
                            } else {
                                // Error lainnya
                                alert('Terjadi kesalahan. Gagal menyimpan data.');
                            }
                        }
                    });
                });
                $(document).on('click', '#add-skill-btn', function() {
                    const template = document.getElementById('skill-row-template').content.cloneNode(true);
                    $('#skills-container').append(template);
                });

                $(document).on('click', '.remove-skill-btn', function() {
                    // Jangan hapus baris terakhir, minimal harus ada satu
                    if ($('.skill-row').length > 1) {
                        $(this).closest('.skill-row').remove();
                    } else {
                        alert('Minimal harus ada satu skill yang dibutuhkan.');
                    }
                });
            });
        </script>
    @endif
    {{-- ================================================================== --}}
    {{-- MODIFIKASI SELESAI --}}
    {{-- ================================================================== --}}
@endpush
