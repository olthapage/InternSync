@extends('layouts.template')
@section('content')
<div class="page-header min-height-250 border-radius-lg mt-4 d-flex flex-column justify-content-end">
    <span class="mask bg-primary opacity-9"></span>
    <div class="w-100 position-relative p-3">
        <div class="d-flex justify-content-between align-items-end">
            <div clasb s="d-flex align-items-center">
                <div class="avatar avatar-xl position-relative me-3">
                    <img id="profileHeaderImage"
                        src="{{ asset('storage/public/foto/' . Auth::user()->foto) }}" alt="profile_image"
                        class="w-100 border-radius-lg shadow-sm">
                </div>
                <div>
                    <h5 class="mb-1 text-white font-weight-bolder">
                        {{ Auth::user()->nama_lengkap }}
                    </h5>
                    <p class="mb-0 text-white text-sm">
                        {{ Auth::user()->level->level_nama }}
                    </p>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <a href="javascript:;" class="btn btn-outline-white mb-0 me-1 btn-sm">
                    Billing
                </a>
                <a href="javascript:;" class="btn btn-outline-white mb-0 btn-sm">
                    Payments
                </a>
            </div>
        </div>
    </div>
</div>
</div>
<div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
aria-hidden="true">
<div class="modal-dialog">
    <div class="modal-content" id="editProfileModalContent"></div>
</div>
</div>
<div class="container-fluid py-4">
<div class="row">
    <div class="col-12 col-xl-4">
        <div class="card h-100">
          <div class="card-header pb-0 p-3">
            <h6 class="mb-0">📥 Quick Links</h6>
          </div>
          <div class="card-body p-3">
            @php $level = auth()->user()->level->level_kode; @endphp
            <ul class="list-group">
              <a href="{{ route('home') }}" class="list-group-item list-group-item-action">🏠 Dashboard</a>
              <a href="{{ route('home') }}" class="list-group-item list-group-item-action">🏢 Industri</a>
              @if($level !== 'MHS')
                <a href="{{ route('home') }}" class="list-group-item list-group-item-action">🔔 Notifikasi</a>
              @endif
              @if($level === 'MHS')
                <a href="{{ route('home') }}" class="list-group-item list-group-item-action">📝 Laporan Magang</a>
              @endif
              <a href="{{ route('home') }}" class="list-group-item list-group-item-action">⚙️ Pengaturan</a>
            </ul>
          </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        <div class="card h-100">
            <div class="card-header pb-0 p-3">
                <h6 class="mb-0">Edit Profile</h6>
            </div>
            <div class="card-body p-3">
                <form id="editProfileForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label">Nama</label>
                        <input type="text" name="nama_lengkap" class="form-control"
                            value="{{ $user->nama_lengkap }}">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ $user->email }}">
                    </div>

                    <div class="mb-3">
                        <label for="foto" class="form-label">Foto Profil</label><br>
                        <img id="profileImage" src="{{ asset('storage/public/foto/' . $user->foto) }}"
                            width="60" class="rounded mb-2" alt="Foto Profil">
                        <input type="file" name="foto" class="form-control">
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Save</button>
                        <a href="{{ route('profile.index') }}" class="btn btn-secondary">Cancel</a>
                    </div>
                </form>

                <div id="errorMessage" class="alert alert-danger mt-3" style="display:none;"></div>
                <div id="successMessage" class="alert alert-success mt-3" style="display:none;"></div>
            </div>
        </div>
    </div>
    <div class="col-12 col-xl-4">
        @php $level = auth()->user()->level->level_kode; @endphp
        @if(in_array($level, ['ADM', 'DSN']))
          <div class="card mb-3">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">⚙️ Status Sistem</h6>
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
                <a href="{{ asset('files/panduan_internsync.pdf') }}" class="list-group-item list-group-item-action" target="_blank">📄 Panduan InternSync</a>
                <a href="{{ asset('files/form_magang.docx') }}" class="list-group-item list-group-item-action" target="_blank">📝 Formulir Magang</a>
                <a href="{{ asset('files/sertifikat_sample.pdf') }}" class="list-group-item list-group-item-action" target="_blank">🎖️ Contoh Sertifikat</a>
              </ul>
            </div>
          </div>
        @else
          <div class="card-body p-3 small">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">💡 Tip Singkat</h6>
            </div>
            <div class="card-body p-3 small">
              <ul class="ps-3 mb-0">
                <li>Gunakan filter skill untuk hasil lebih tepat.</li>
                <li>Unggah foto profil agar lebih personal.</li>
                <li>Perbarui kontak untuk notifikasi lancar.</li>
              </ul>
            </div>
          </div>
          <div class="card mb-3">
            <div class="card-header pb-0 p-3">
              <h6 class="mb-0">📥 Unduhan Cepat</h6>
            </div>
            <div class="card-body p-3">
              <ul class="list-group">
                <a href="{{ asset('files/panduan_internsync.pdf') }}" class="list-group-item list-group-item-action" target="_blank">📄 Panduan InternSync</a>
                <a href="{{ asset('files/form_magang.docx') }}" class="list-group-item list-group-item-action" target="_blank">📝 Formulir Magang</a>
                <a href="{{ asset('files/sertifikat_sample.pdf') }}" class="list-group-item list-group-item-action" target="_blank">🎖️ Contoh Sertifikat</a>
              </ul>
            </div>
          </div>
        @endif
    </div>    
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
                                    <img src="softTemplate/assets/img/home-decor-1.jpg"
                                        alt="img-blur-shadow" class="img-fluid shadow border-radius-md">
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
                                    <button type="button"
                                        class="btn btn-outline-primary btn-sm mb-0">View Project</button>
                                    <div class="avatar-group mt-2">
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Elena Morison">
                                            <img alt="Image placeholder"
                                                src="softTemplate/assets/img/team-1.jpg">
                                        </a>
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Ryan Milly">
                                            <img alt="Image placeholder"
                                                src="softTemplate/assets/img/team-2.jpg">
                                        </a>
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Nick Daniel">
                                            <img alt="Image placeholder"
                                                src="softTemplate/assets/img/team-3.jpg">
                                        </a>
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Peterson">
                                            <img alt="Image placeholder"
                                                src="softTemplate/assets/img/team-4.jpg">
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
                                    <img src="softTemplate/assets/img/home-decor-2.jpg"
                                        alt="img-blur-shadow" class="img-fluid shadow border-radius-md">
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
                                    <button type="button"
                                        class="btn btn-outline-primary btn-sm mb-0">View Project</button>
                                    <div class="avatar-group mt-2">
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Nick Daniel">
                                            <img alt="Image placeholder"
                                                src="softTemplate/assets/img/team-3.jpg">
                                        </a>
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Peterson">
                                            <img alt="Image placeholder"
                                                src="softTemplate/assets/img/team-4.jpg">
                                        </a>
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Elena Morison">
                                            <img alt="Image placeholder"
                                                src="softTemplate/assets/img/team-1.jpg">
                                        </a>
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Ryan Milly">
                                            <img alt="Image placeholder"
                                                src="softTemplate/assets/img/team-2.jpg">
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
                                    <img src="softTemplate/assets/img/home-decor-3.jpg"
                                        alt="img-blur-shadow" class="img-fluid shadow border-radius-md">
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
                                    <button type="button"
                                        class="btn btn-outline-primary btn-sm mb-0">View Project</button>
                                    <div class="avatar-group mt-2">
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Peterson">
                                            <img alt="Image placeholder"
                                                src="softTemplate/assets/img/team-4.jpg">
                                        </a>
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Nick Daniel">
                                            <img alt="Image placeholder"
                                                src="softTemplate/assets/img/team-3.jpg">
                                        </a>
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Ryan Milly">
                                            <img alt="Image placeholder"
                                                src="softTemplate/assets/img/team-2.jpg">
                                        </a>
                                        <a href="javascript:;" class="avatar avatar-xs rounded-circle"
                                            data-bs-toggle="tooltip" data-bs-placement="bottom"
                                            title="Elena Morison">
                                            <img alt="Image placeholder"
                                                src="softTemplate/assets/img/team-1.jpg">
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
@endsection
