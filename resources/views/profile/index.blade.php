@extends('layouts.template')

@section('content')
    @php
        // Tentukan path gambar default sebagai fallback
        $headerImageUrl = asset('images/slide4.jpg');

        // Tentukan pengguna dan peran berdasarkan guard yang aktif
        $user = null;
        $role = '-';
        if (Auth::guard('web')->check()) {
            $user = Auth::guard('web')->user();
            $role = 'Admin';
        } elseif (Auth::guard('mahasiswa')->check()) {
            $user = Auth::guard('mahasiswa')->user();
            $role = 'Mahasiswa';
        } elseif (Auth::guard('dosen')->check()) {
            $user = Auth::guard('dosen')->user();
            $role = 'Dosen';
        } elseif (Auth::guard('industri')->check()) {
            $user = Auth::guard('industri')->user();
            $role = 'Industri';
        }
    @endphp

    {{-- Terapkan gambar sebagai background menggunakan inline style --}}
    <div class="page-header min-height-250 border-radius-lg mt-4 d-flex flex-column justify-content-end"
        style="background-image: url('{{ $headerImageUrl }}'); background-size: cover; background-position: center;">

        {{-- Mask (overlay) --}}
        <span class="mask bg-gradient-dark opacity-6"></span>

        {{-- Konten di dalam header (foto profil, nama, tombol) --}}
        <div class="w-100 position-relative p-3">
            <div class="d-flex justify-content-between align-items-end">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl position-relative me-3">
                        @php
                            // 1. Tentukan folder, gambar default, DAN NAMA KOLOM FOTO/LOGO berdasarkan guard
                            [$folder, $defaultImage, $imageColumn] = match (true) {
                                Auth::guard('mahasiswa')->check() => [
                                    'mahasiswa/foto',
                                    asset('assets/default-profile.png'),
                                    'foto',
                                ],
                                Auth::guard('dosen')->check() => ['foto', asset('assets/default-profile.png'), 'foto'],
                                Auth::guard('industri')->check() => [
                                    'logo_industri',
                                    asset('assets/default-industri.png'),
                                    'logo',
                                ], // DIUBAH DI SINI
                                Auth::guard('web')->check() => ['foto', asset('assets/default-profile.png'), 'foto'],
                                default => ['', asset('assets/default-profile.png'), ''],
                            };

                            // 2. Siapkan URL gambar dengan gambar default yang sudah sesuai
                            $imageUrl = $defaultImage;

                            // 3. Jika user ada, punya nama kolom, dan nilai di kolom itu tidak kosong...
                            if (isset($user) && $imageColumn && !empty($user->{$imageColumn})) {
                                $fileName = $user->{$imageColumn}; // Akses kolom secara dinamis ('foto' atau 'logo')
                                $filePath = $folder . '/' . $fileName;

                                // 4. Cek apakah file benar-benar ada di storage
                                if (Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                                    $imageUrl = asset('storage/' . $filePath);
                                }
                            }
                        @endphp

                        {{-- Tag <img> Anda tetap sama, tidak perlu diubah --}}
                        <img id="profileHeaderImage" src="{{ $imageUrl }}" alt="profil" class="w-100 border-radius-lg">
                    </div>
                    <div>
                        <h5 class="mb-1 text-white font-weight-bolder">
                            {{-- Tampilkan nama berdasarkan guard --}}
                            @if (Auth::guard('industri')->check())
                                {{ $user->industri_nama ?? 'Nama Industri' }}
                            @else
                                {{ $user->nama_lengkap ?? 'Nama Pengguna' }}
                            @endif
                        </h5>
                        <p class="mb-0 text-white text-sm">
                            {{ $role }}
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    {{-- Tombol Edit Profil berdasarkan guard --}}
                    @if (Auth::guard('mahasiswa')->check())
                        <button onclick="modalAction('{{ url('/mahasiswa/' . $user->mahasiswa_id . '/edit') }}')"
                            class="btn btn-outline-white mb-0 btn-sm">Detail dan Edit Profil</button>
                    @elseif(Auth::guard('dosen')->check())
                        <button onclick="modalAction('{{ url('/dosen/' . $user->dosen_id . '/edit') }}')"
                            class="btn btn-outline-white mb-0 btn-sm">Detail dan Edit Profil</button>
                    @elseif(Auth::guard('industri')->check())
                        <button onclick="modalAction('{{ url('/industri/' . $user->industri_id . '/edit') }}')"
                            class="btn btn-outline-white mb-0 btn-sm">Detail dan Edit Profil</button>
                    @elseif(Auth::guard('web')->check())
                        <button onclick="modalAction('{{ url('/admin/' . $user->user_id . '/edit') }}')"
                            class="btn btn-outline-white mb-0 btn-sm">Detail dan Edit Profil</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="myModalContent"></div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            @if (Auth::guard('mahasiswa')->check())
                <div class="mb-4 w-100">
                    <div class="card h-100">
                        {{-- =============================================== --}}
                        {{-- ===== PERUBAHAN DIMULAI DI SINI ===== --}}
                        {{-- =============================================== --}}
                        <div class="card-header pb-0 p-3 d-flex justify-content-between align-items-center">
                            <h6 class="mb-0">Lengkapi Profil</h6>
                            @php
                                $status = $user->status_verifikasi;
                                $badgeClass = '';
                                $statusText = '';

                                switch ($status) {
                                    case 'valid':
                                        $badgeClass = 'bg-gradient-success';
                                        $statusText = 'Terverifikasi';
                                        break;
                                    case 'pending':
                                        $badgeClass = 'bg-gradient-warning';
                                        $statusText = 'Menunggu Verifikasi';
                                        break;
                                    case 'invalid':
                                        $badgeClass = 'bg-gradient-danger';
                                        $statusText = 'Verifikasi Ditolak';
                                        break;
                                    default:
                                        $badgeClass = 'bg-gradient-secondary';
                                        $statusText = 'Belum Diverifikasi';
                                        break;
                                }
                            @endphp
                            <span class="badge {{ $badgeClass }}">{{ $statusText }}</span>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-grid gap-2">
                                <button type="button" id="btn-academic-profile"
                                    class="btn bg-gradient-success text-start px-3 " data-bs-toggle="modal"
                                    data-bs-target="#academicModal">
                                    Formulir Wajib Akademik
                                </button>
                                <small>Wajib dilengkapi dan menunggu validasi dari admin sebelum mengajukan magang.</small>
                            </div>

                            {{-- Tampilkan alasan penolakan jika status 'invalid' --}}
                            @if ($status === 'invalid' && !empty($user->alasan))
                                <div class="alert alert-danger text-white mt-3 mb-0 py-2 px-3">
                                    <h6 class="text-white mb-1"><i class="fas fa-times-circle me-1"></i> Alasan Penolakan:
                                    </h6>
                                    <p class="mb-0 small">{{ $user->alasan }}</p>
                                </div>
                            @endif
                        </div>
                        {{-- =============================================== --}}
                        {{-- ===== PERUBAHAN SELESAI DI SINI ===== --}}
                        {{-- =============================================== --}}
                    </div>
                </div>

                <div class="modal fade" id="academicModal" tabindex="-1" aria-labelledby="academicModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="academicModalLabel">🎓 Isi Profil Akademik</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="academic-form-container">
                                <div class="text-center py-3">Memuat formulir...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="preferencesModal" tabindex="-1" aria-labelledby="preferencesModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="preferencesModalLabel">📍 Perbarui Preferensi Lokasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="preferences-form-container">
                                <div class="text-center py-3">Memuat formulir...</div>
                            </div>
                        </div>
                    </div>
                </div>

                @push('js')
                    <script>
                        function loadForm(url, container) {
                            $.ajax({
                                url: url,
                                method: 'GET',
                                success: function(html) {
                                    $(container).html(html);
                                },
                                error: function() {
                                    Swal.fire('Error', 'Gagal memuat form.', 'error');
                                }
                            });
                        }

                        $(document).ready(function() {
                            $('#academicModal').on('show.bs.modal', function() {
                                const $container = $('#academic-form-container');
                                $container.html('<div class="text-center py-3">Memuat formulir...</div>');
                                loadForm("{{ route('intern.academicProfile') }}", $container);
                            });

                            $('#preferencesModal').on('show.bs.modal', function() {
                                const $container = $('#preferences-form-container');
                                $container.html('<div class="text-center py-3">Memuat formulir...</div>');
                                loadForm("{{ route('intern.preferences') }}", $container);
                            });
                        });
                    </script>
                @endpush
            @endif

            {{-- Kolom kedua: Konten dinamis berdasarkan guard --}}
            <div class="col-12 mb-4 mb-xl-0">
                {{-- Tampilkan untuk Admin, Dosen, dan Industri --}}
                @if (Auth::guard('web')->check() || Auth::guard('dosen')->check() || Auth::guard('industri')->check())
                    <div class="d-flex flex-column h-100">
                        <div class="card mb-3">
                            <div class="card-header pb-0 p-3">
                                <h6 class="mb-0">⚙ Status Sistem</h6>
                            </div>
                            <div class="card-body p-3 small">
                                <ul class="list-unstyled mb-0">
                                    <li>Database: <span class="text-success">Online ✅</span></li>
                                    <li>Backup terakhir: <small class="text-muted">{{ now()->subHours(2)->format('H:i') }}
                                            WIB</small></li>
                                </ul>
                            </div>
                        </div>

                        <div class="card flex-grow-1">
                            <div class="card-header pb-0 p-3">
                                <h6 class="mb-0">📥 Unduhan Cepat</h6>
                            </div>
                            <div class="card-body p-3">
                                <ul class="list-group">
                                    <a href="{{ asset('files/panduan_internsync.pdf') }}"
                                        class="list-group-item list-group-item-action" target="_blank">
                                        📄 Panduan InternSync
                                    </a>
                                </ul>
                            </div>
                        </div>
                @endif

                {{-- Tampilkan hanya untuk Mahasiswa --}}
                @if (Auth::guard('mahasiswa')->check())
                    <div class="card mb-3">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">💡 Tip Singkat</h6>
                        </div>
                        <div class="card-body p-3 small">
                            <ul class="list-unstyled">
                                <li>Gunakan filter skill untuk hasil lebih tepat.</li>
                                <li>Unggah foto profil agar lebih personal.</li>
                                <li>Perbarui kontak untuk notifikasi lancar.</li>
                            </ul>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">📘 Informasi Tambahan</h6>
                        </div>
                        <div class="card-body p-3 small">
                            <p>Pastikan data akademik dan preferensi lokasi kamu selalu diperbarui untuk memperbesar peluang
                                diterima di industri pilihan.</p>
                            <p>Konten profil yang lengkap juga akan lebih menarik perhatian perekrut dan pihak industri.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            // Cek apakah URL ada, untuk menghindari error jika tombol tidak ada
            if (url) {
                $('#myModal').load(url, function() {
                    $('#myModal').modal('show');
                });
            }
        }
    </script>
@endpush
