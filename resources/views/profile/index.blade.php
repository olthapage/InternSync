@extends('layouts.template')
@section('content')
    <div class="page-header min-height-250 border-radius-lg mt-4 d-flex flex-column justify-content-end">
        <span class="mask bg-primary opacity-9"></span>
        <div class="w-100 position-relative p-3">
            <div class="d-flex justify-content-between align-items-end">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl position-relative me-3">
                        <img id="profileHeaderImage"
                            src="{{ Auth::user()->foto ? asset('storage/foto/' . Auth::user()->foto) : asset('assets/default-profile.png') }}"
                            alt="profil" class="w-100 border-radius-lg">


                    </div>
                    <div>
                        <h5 class="mb-1 text-white font-weight-bolder">
                        @if(Auth::guard('industri')->check())
                            {{ Auth::user()->industri_nama }}
                        @else
                            {{ Auth::user()->nama_lengkap }}
                        @endif
                        </h5>
                        <p class="mb-0 text-white text-sm">
                            {{ Auth::user()->level->level_nama ?? '-' }}
                        </p>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    @if (Auth::guard('mahasiswa')->check())
                        <button
                            onclick="modalAction('{{ url('/mahasiswa/' . Auth::guard('mahasiswa')->user()->mahasiswa_id . '/edit') }}')"
                            class="btn btn-outline-white mb-0 btn-sm">Edit Profil</button>
                    @elseif(Auth::guard('dosen')->check())
                        <button
                            onclick="modalAction('{{ url('/dosen/' . Auth::guard('dosen')->user()->dosen_id . '/edit') }}')"
                            class="btn btn-outline-white mb-0 btn-sm">Edit Profil</button>
                    @else
                        <button onclick="modalAction('{{ url('/admin/' . Auth::user()->user_id . '/edit') }}')"
                            class="btn btn-outline-white mb-0 btn-sm">Edit Profil</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content" id="editProfileModalContent"></div>
        </div>
    </div>
    <div class="container-fluid py-4">
        <div class="row">
            {{-- Quick Links --}}
            <div class="col-12 col-xl-4">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">📥 Quick Links</h6>
                    </div>
                    <div class="card-body p-3">
                        @php $level = auth()->user()->level->level_kode; @endphp
                        <ul class="list-group">
                            <a href="{{ route('home') }}" class="list-group-item list-group-item-action">🏠 Dashboard</a>
                            @if ($level !== 'MHS')
                                <a href="{{ route('home') }}" class="list-group-item list-group-item-action">🔔
                                    Notifikasi</a>
                            @endif
                            @if ($level === 'MHS')
                                <a href="{{ route('home') }}" class="list-group-item list-group-item-action">📝 Laporan
                                    Magang</a>
                            @endif
                            <a href="{{ route('home') }}" class="list-group-item list-group-item-action">⚙ Pengaturan</a>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Sistem / Tips --}}
            <div class="col-12 col-xl-4">
                @php $level = auth()->user()->level->level_kode; @endphp

                @if (in_array($level, ['ADM', 'DSN', 'IND']))
                    <div class="card mb-3">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">⚙ Status Sistem</h6>
                        </div>
                        <div class="card-body p-3 small">
                            <ul class="list-unstyled">
                                <li>Database: <span class="text-success">Online ✅</span></li>
                                <li>Backup terakhir: <small class="text-muted">{{ now()->subHours(2)->format('H:i') }}
                                        WIB</small></li>
                            </ul>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">📥 Unduhan Cepat</h6>
                        </div>
                        <div class="card-body mb-3">
                            <ul class="list-group">
                                <a href="{{ asset('files/panduan_internsync.pdf') }}"
                                    class="list-group-item list-group-item-action" target="_blank">
                                    📄 Panduan InternSync
                                </a>
                                <a href="{{ asset('files/form_magang.docx') }}"
                                    class="list-group-item list-group-item-action" target="_blank">
                                    📝 Formulir Magang
                                </a>
                                <a href="{{ asset('files/sertifikat_sample.pdf') }}"
                                    class="list-group-item list-group-item-action" target="_blank">
                                    🎖 Contoh Sertifikat
                                </a>
                            </ul>
                        </div>
                    </div>
                @else
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

            {{-- Quick Action (hanya untuk non-ADM/DSN) --}}
            @unless (in_array(auth()->user()->level->level_kode, ['ADM', 'DSN', 'IND']))
                <div class="col-12 col-xl-4">
                    <div class="card h-100">
                        <div class="card-header pb-0 p-3">
                            <h6 class="mb-0">🚀 Quick Action</h6>
                        </div>
                        <div class="card-body p-3">
                            <div class="d-grid gap-2">
                                <button type="button" id="btn-academic-profile" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#academicModal">
                                    🎓 Isi Profil Akademik
                                </button>
                                <button type="button" id="btn-preferences" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#preferencesModal">
                                    📍 Perbarui Preferensi Lokasi
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal: Academic Profile -->
                <div class="modal fade" id="academicModal" tabindex="-1" aria-labelledby="academicModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="academicModalLabel">🎓 Isi Profil Akademik</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body" id="academic-form-container">
                                <div class="text-center py-3">Memuat formulir...</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal: Preferences -->
                <div class="modal fade" id="preferencesModal" tabindex="-1" aria-labelledby="preferencesModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="preferencesModalLabel">📍 Perbarui Preferensi Lokasi</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                            success: function (html) {
                                $(container).html(html);
                            },
                            error: function () {
                                Swal.fire('Error', 'Gagal memuat form.', 'error');
                            }
                        });
                    }

                    $(document).ready(function () {
                        $('#academicModal').on('show.bs.modal', function () {
                            const $container = $('#academic-form-container');
                            $container.html('<div class="text-center py-3">Memuat formulir...</div>');
                            loadForm("{{ route('intern.academicProfile') }}", $container);
                        });

                        $('#preferencesModal').on('show.bs.modal', function () {
                            const $container = $('#preferences-form-container');
                            $container.html('<div class="text-center py-3">Memuat formulir...</div>');
                            loadForm("{{ route('intern.preferences') }}", $container);
                        });
                    });
                </script>
                @endpush
            @endunless
        @endsection
        @push('js')
            <script>
                function modalAction(url = '') {
                    $('#myModal').load(url, function() {
                        $('#myModal').modal('show');
                    });
                }
            </script>
        @endpush
