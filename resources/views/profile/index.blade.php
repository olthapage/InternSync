@extends('layouts.template')
@section('content')
    <div class="page-header min-height-250 border-radius-lg mt-4 d-flex flex-column justify-content-end">
        <span class="mask bg-primary opacity-9"></span>
        <div class="w-100 position-relative p-3">
            <div class="d-flex justify-content-between align-items-end">
                <div class="d-flex align-items-center">
                    <div class="avatar avatar-xl position-relative me-3">
                        <img id="profileHeaderImage"
                            src="{{ asset('storage/foto/' . (Auth::user()->foto ?? 'default-profile.png')) }}" alt="profil"
                            class="w-100 border-radius-lg shadow-sm">

                    </div>
                    <div>
                        <h5 class="mb-1 text-white font-weight-bolder">
                            {{ Auth::user()->nama_lengkap }}
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
                            <a href="{{ route('home') }}" class="list-group-item list-group-item-action">🔔 Notifikasi</a>
                        @endif
                        @if ($level === 'MHS')
                            <a href="{{ route('home') }}" class="list-group-item list-group-item-action">📝 Laporan Magang</a>
                        @endif
                        <a href="{{ route('home') }}" class="list-group-item list-group-item-action">⚙ Pengaturan</a>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Sistem / Tips --}}
        <div class="col-12 col-xl-4">
            @php $level = auth()->user()->level->level_kode; @endphp

            @if (in_array($level, ['ADM', 'DSN']))
                <div class="card mb-3">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">⚙ Status Sistem</h6>
                    </div>
                    <div class="card-body p-3 small">
                        <ul class="list-unstyled">
                            <li>Database: <span class="text-success">Online ✅</span></li>
                            <li>Backup terakhir: <small class="text-muted">{{ now()->subHours(2)->format('H:i') }} WIB</small></li>
                        </ul>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">📥 Unduhan Cepat</h6>
                    </div>
                    <div class="card-body mb-3">
                        <ul class="list-group">
                            <a href="{{ asset('files/panduan_internsync.pdf') }}" class="list-group-item list-group-item-action" target="_blank">
                                📄 Panduan InternSync
                            </a>
                            <a href="{{ asset('files/form_magang.docx') }}" class="list-group-item list-group-item-action" target="_blank">
                                📝 Formulir Magang
                            </a>
                            <a href="{{ asset('files/sertifikat_sample.pdf') }}" class="list-group-item list-group-item-action" target="_blank">
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
                        <p>Pastikan data akademik dan preferensi lokasi kamu selalu diperbarui untuk memperbesar peluang diterima di industri pilihan.</p>
                        <p>Konten profil yang lengkap juga akan lebih menarik perhatian perekrut dan pihak industri.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Quick Action (hanya untuk non-ADM/DSN) --}}
        @unless (in_array(auth()->user()->level->level_kode, ['ADM', 'DSN']))
            <div class="col-12 col-xl-4">
                <div class="card h-100">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-0">🚀 Quick Action</h6>
                    </div>
                    <div class="card-body p-3">
                        <div class="d-grid gap-2">
                            <button type="button" id="btn-academic-profile" class="btn btn-outline-success">
                                🎓 Isi Profil Akademik
                            </button>
                            <button type="button" id="btn-preferences" class="btn btn-outline-warning">
                                📍 Perbarui Preferensi Lokasi
                            </button>
                        </div>

                        <div id="academic-form-container" class="mt-4" style="display:none;"></div>
                        <div id="preferences-form-container" class="mt-4" style="display:none;"></div>

                        @push('js')
                            <script>
                                $(function () {
                                    function loadInline(url, $container) {
                                        $.ajax({
                                            url: url,
                                            method: 'GET',
                                            success(html) {
                                                $container.html(html).slideDown();
                                            },
                                            error() {
                                                Swal.fire('Error', 'Gagal memuat form.', 'error');
                                            }
                                        });
                                    }

                                    $('#btn-academic-profile').click(function () {
                                        $('#preferences-form-container').slideUp();
                                        loadInline("{{ route('intern.academicProfile') }}", $('#academic-form-container'));
                                    });

                                    $('#btn-preferences').click(function () {
                                        $('#academic-form-container').slideUp();
                                        loadInline("{{ route('intern.preferences') }}", $('#preferences-form-container'));
                                    });
                                });
                            </script>
                        @endpush
                    </div>
                </div>
            </div>
        @endunless
            <div class="col-12 mt-4">
                <div class="card mb-4">
                    <div class="card-header pb-0 p-3">
                        <h6 class="mb-1">Projects</h6>
                        <p class="text-sm">Architects design houses</p>
                    </div>
                    <div class="card-body p-3">
                        <div class="row">
                            <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
                                <div class="card card-blog card-plain">
                                    <div class="position-relative">
                                        <a class="d-block">
                                            <img src="softTemplate/assets/img/home-decor-1.jpg" alt="img-blur-shadow"
                                                class="img-fluid shadow border-radius-md">
                                        </a>
                                    </div>
                                    <div class="card-body px-1 pb-0">
                                        <p class="text-secondary mb-0 text-sm">Project #2</p>
                                        <a href="javascript:;">
                                            <h5 class="font-weight-bolder">
                                                Modern
                                            </h5>
                                        </a>
                                        <p class="mb-4 text-sm">
                                            As Uber works through a huge amount of internal management turmoil.
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <button type="button" class="btn btn-outline-primary btn-sm mb-0">View
                                                Project</button>
                                            <div class="avatar-group mt-2">
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="Elena Morison">
                                                    <img alt="Image placeholder" src="softTemplate/assets/img/team-1.jpg">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="Ryan Milly">
                                                    <img alt="Image placeholder" src="softTemplate/assets/img/team-2.jpg">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="Nick Daniel">
                                                    <img alt="Image placeholder" src="softTemplate/assets/img/team-3.jpg">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Peterson">
                                                    <img alt="Image placeholder" src="softTemplate/assets/img/team-4.jpg">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
                                <div class="card card-blog card-plain">
                                    <div class="position-relative">
                                        <a class="d-block">
                                            <img src="softTemplate/assets/img/home-decor-2.jpg" alt="img-blur-shadow"
                                                class="img-fluid shadow border-radius-md">
                                        </a>
                                    </div>
                                    <div class="card-body px-1 pb-0">
                                        <p class="text-secondary mb-0 text-sm">Project #1</p>
                                        <a href="javascript:;">
                                            <h5 class="font-weight-bolder">
                                                Scandinavian
                                            </h5>
                                        </a>
                                        <p class="mb-4 text-sm">
                                            Music is something that every person has his or her own specific
                                            opinion.
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <button type="button" class="btn btn-outline-primary btn-sm mb-0">View
                                                Project</button>
                                            <div class="avatar-group mt-2">
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="Nick Daniel">
                                                    <img alt="Image placeholder" src="softTemplate/assets/img/team-3.jpg">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Peterson">
                                                    <img alt="Image placeholder" src="softTemplate/assets/img/team-4.jpg">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="Elena Morison">
                                                    <img alt="Image placeholder" src="softTemplate/assets/img/team-1.jpg">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="Ryan Milly">
                                                    <img alt="Image placeholder" src="softTemplate/assets/img/team-2.jpg">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
                                <div class="card card-blog card-plain">
                                    <div class="position-relative">
                                        <a class="d-block">
                                            <img src="softTemplate/assets/img/home-decor-3.jpg" alt="img-blur-shadow"
                                                class="img-fluid shadow border-radius-md">
                                        </a>
                                    </div>
                                    <div class="card-body px-1 pb-0">
                                        <p class="text-secondary mb-0 text-sm">Project #3</p>
                                        <a href="javascript:;">
                                            <h5 class="font-weight-bolder">
                                                Minimalist
                                            </h5>
                                        </a>
                                        <p class="mb-4 text-sm">
                                            Different people have different taste, and various types of music.
                                        </p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <button type="button" class="btn btn-outline-primary btn-sm mb-0">View
                                                Project</button>
                                            <div class="avatar-group mt-2">
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom" title="Peterson">
                                                    <img alt="Image placeholder" src="softTemplate/assets/img/team-4.jpg">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="Nick Daniel">
                                                    <img alt="Image placeholder" src="softTemplate/assets/img/team-3.jpg">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="Ryan Milly">
                                                    <img alt="Image placeholder" src="softTemplate/assets/img/team-2.jpg">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                                    data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                    title="Elena Morison">
                                                    <img alt="Image placeholder" src="softTemplate/assets/img/team-1.jpg">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-3 col-md-6 mb-xl-0 mb-4">
                                <div class="card h-100 card-plain border">
                                    <div class="card-body d-flex flex-column justify-content-center text-center">
                                        <a href="javascript:;">
                                            <i class="fa fa-plus text-secondary mb-3"></i>
                                            <h5 class=" text-secondary"> New project </h5>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
                data-keyboard="false" data-width="75%" aria-hidden="true"></div>
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
