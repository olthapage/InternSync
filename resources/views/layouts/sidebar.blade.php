<div class="sidenav-header">
    <i class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
        aria-hidden="true" id="iconSidenav"></i>
    <section class="navbar-brand m-0">
        <img src="{{ asset('softTemplate/assets/img/LogoInternSync.png') }}" class="navbar-brand-img h-100"
            alt="main_logo">
        <span class="ms-1 font-weight-bold">Intern.Sync</span>
    </section>
</div>
<hr class="horizontal dark mt-0">
<div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
    <ul class="navbar-nav text-dark">
        <li class="nav-item">
            <a class="nav-link {{ $activeMenu == 'home' ? 'active text-success' : '' }}" href="{{ route('home') }} ">
                <i class="fas fa-chart-line me-2 {{ $activeMenu == 'home' ? 'text-success' : 'text-dark' }}"></i>
                <span class="nav-link-text ms-1">Dashboard</span>
            </a>
        </li>
        {{-- Menu khusus Dosen taruh sini --}}
        @auth('dosen')
            @if (Auth::user()->role_dosen === 'pembimbing')
                <li class="nav-item">
                    <a class="nav-link {{ $activeMenu == 'mahasiswa-bimbingan' ? 'active text-success' : '' }}"
                        href="{{ route('mahasiswa-bimbingan.index') }}"> {{-- Pastikan route name ini benar --}}
                        <i
                            class="fas fa-users me-2 {{ $activeMenu == 'mahasiswa-bimbingan' ? 'text-success' : 'text-dark' }}"></i>
                        <span class="nav-link-text ms-1">Mahasiswa Bimbingan</span>
                    </a>
                    <a class="nav-link {{ $activeMenu == 'logharian_dosen' ? 'active text-success' : '' }}"
                        href="{{ route('logharian_dosen.index') }}">
                        <i
                            class="fas fa-users me-2 {{ $activeMenu == 'logharian_dosen' ? 'text-success' : 'text-dark' }}"></i>
                        <span class="nav-link-text ms-1">Log Harian Mahasiswa</span>
                    </a>
                </li>
            @endif
            @if (Auth::user()->role_dosen === 'dpa')
                <li class="nav-item">
                    <a class="nav-link {{ $activeMenu == 'validasi-portofolio' ? 'active text-success' : '' }}"
                        href="{{ route('dosen.mahasiswa-dpa.index') }}">
                        <i
                            class="fas fa-check-double me-2 {{ $activeMenu == 'validasi-portofolio' ? 'text-success' : 'text-dark' }}"></i>
                        <span class="nav-link-text ms-1">Validasi Mahasiswa</span>
                    </a>
                </li>
            @endif
        @endauth
        {{-- Menu khusus mahasiswa taruh sini --}}
        @auth('mahasiswa')
            <li class="nav-item">
                <a class="nav-link {{ $activeMenu == 'lowongan' ? 'active text-success' : '' }}"
                    href="{{ route('mahasiswa.lowongan.index') }} ">
                    <i
                        class="fa-solid fa-briefcase me-2 {{ $activeMenu == 'lowongan' ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Lowongan</span>
                </a>
                <a class="nav-link {{ $activeMenu == 'pengajuan' ? 'active text-success' : '' }}"
                    href="{{ route('mahasiswa.pengajuan.index') }} ">
                    <i class="fa-solid fa-scroll me-2 {{ $activeMenu == 'pengajuan' ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Pengajuan Magang</span>
                </a>
                <a class="nav-link {{ ($activeMenu ?? 'portofolio') == 'portofolio' ? 'active text-success' : '' }}"
                    href="{{ route('mahasiswa.portofolio.index') }}">
                    <i
                        class="fa-solid fa-address-card me-2 {{ ($activeMenu ?? '') == 'portofolio' ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Portofolio Saya</span>
                </a>
                <a class="nav-link {{ $activeMenu == 'magang' ? 'active text-success' : '' }}"
                    href="{{ route('mahasiswa.magang.index') }} ">
                    <i
                        class="fa-solid fa-chart-simple me-2 {{ $activeMenu == 'magang' ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Magang Saya</span>
                </a>
                {{-- <a class="nav-link {{ ($activeMenu ?? '') == 'logharian' ? 'active text-success' : '' }}"
                    href="{{ route('logHarian.index') }}">
                    <i
                        class="fa-solid fa-book me-2 {{ ($activeMenu ?? '') == 'logharian' ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Log Harian</span>
                </a> --}}
            </li>
        @endauth
        @auth('industri')
            <li class="nav-item">
                <a class="nav-link {{ $activeMenu == 'lowongan' ? 'active text-success' : '' }}"
                    href="{{ route('industri.lowongan.index') }} ">
                    <i
                        class="fa-solid fa-address-book me-2 {{ $activeMenu == 'lowongan' ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Manajemen Lowongan</span>
                </a>
                <a class="nav-link {{ $activeMenu == 'manajemen' ? 'active text-success' : '' }}"
                    href="{{ route('industri.magang.index') }} ">
                    <i
                        class="fa-solid fa-briefcase me-2 {{ $activeMenu == 'manajemen' ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Manajemen Magang</span>
                </a>
                <a class="nav-link {{ $activeMenu == 'logharian_industri' ? 'active text-success' : '' }}"
                    href="{{ route('logharian_industri.index') }}">
                    <i
                        class="fas fa-users me-2 {{ $activeMenu == 'logharian_industri' ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Log Harian Mahasiswa</span>
                </a>
            </li>
        @endauth

        {{-- Menu khusus admin taruh sini --}}
        @auth('web')
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ in_array($activeMenu, ['mahasiswa', 'dosen', 'admin']) ? 'active text-success' : '' }}"
                    href="#" id="navbarDropdownUser" role="button" data-bs-toggle="collapse"
                    data-bs-target="#dropdownMenuUser"
                    aria-expanded="{{ in_array($activeMenu, ['mahasiswa', 'dosen', 'admin']) ? 'true' : 'false' }}"
                    aria-controls="dropdownMenuUser">
                    <i
                        class="fas fa-users me-2 {{ in_array($activeMenu, ['mahasiswa', 'dosen', 'admin']) ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Daftar Pengguna</span>
                </a>
                <ul class="collapse list-unstyled ps-4 {{ in_array($activeMenu, ['mahasiswa', 'dosen', 'admin']) ? 'show' : '' }}"
                    id="dropdownMenuUser">

                    <li class="nav-item">
                        <a class="dropdown-item d-flex align-items-center py-2 border-bottom text-sm {{ $activeMenu == 'mahasiswa' ? 'active text-success' : '' }}"
                            href="{{ route('mahasiswa.index') }}">
                            <i
                                class="fas fa-user-graduate me-2 {{ $activeMenu == 'mahasiswa' ? 'text-success' : 'text-dark' }}"></i>
                            <span
                                class="nav-link {{ $activeMenu == 'mahasiswa' ? 'text-success' : 'text-dark' }}">Mahasiswa</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="dropdown-item d-flex align-items-center py-2 border-bottom text-sm {{ $activeMenu == 'dosen' ? 'active text-success' : '' }}"
                            href="{{ route('dosen.index') }}">
                            <i
                                class="fas fa-chalkboard-teacher me-2 {{ $activeMenu == 'dosen' ? 'text-success' : 'text-dark' }}"></i>
                            <span class="nav-link {{ $activeMenu == 'dosen' ? 'active text-success' : '' }}">Dosen</span>
                        </a>
                    </li>

                    @if (auth()->user()->is_superadmin)
                        <li class="nav-item">
                            <a class="dropdown-item d-flex align-items-center border-bottom py-2 text-sm {{ $activeMenu == 'admin' ? 'active text-success' : '' }}"
                                href="{{ route('admin.index') }}">
                                <i
                                    class="fas fa-user-shield me-2 {{ $activeMenu == 'admin' ? 'text-success' : 'text-dark' }}"></i>
                                <span
                                    class="nav-link {{ $activeMenu == 'admin' ? 'active text-success' : '' }}">Admin</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ $activeMenu == 'validasi_akun' ? 'active text-success' : '' }}" {{-- Mengganti 'home' dengan 'prodi' --}}
                    href="{{ route('validasi-akun.index') }}">
                    <i class="fas fa-clock me-2 {{ $activeMenu == 'validasi_akun' ? 'active text-success' : '' }}"></i>
                    <span class="nav-link-text ms-1 ">Permintaan Akun</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeMenu == 'prodi' ? 'active text-success' : '' }}" {{-- Mengganti 'home' dengan 'prodi' --}}
                    href="{{ route('program-studi.index') }} ">
                    <i class="fas fa-address-book me-2 {{ $activeMenu == 'prodi' ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1 ">Program Studi</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ in_array($activeMenu, ['kategori-industri', 'industri']) ? 'active text-success' : '' }}"
                    href="#" id="navbarDropdownIndustri" role="button" data-bs-toggle="collapse"
                    data-bs-target="#dropdownMenuIndustri"
                    aria-expanded="{{ in_array($activeMenu, ['kategori_industri', 'industri']) ? 'true' : 'false' }}"
                    aria-controls="dropdownMenuIndustri">
                    <i
                        class="fas fa-building me-2 {{ in_array($activeMenu, ['kategori-industri', 'industri']) ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Industri</span>
                </a>
                <ul class="collapse list-unstyled ps-4 {{ in_array($activeMenu, ['kategori-industri', 'industri']) ? 'show' : '' }}"
                    id="dropdownMenuIndustri">
                    <li class="nav-item">
                        <a class="dropdown-item d-flex align-items-center py-2 border-bottom text-sm {{ $activeMenu == 'kategori-industri' ? 'active text-success' : '' }}"
                            href="{{ route('kategori-industri.index') }}">
                            <i
                                class="fas fa-industry me-2 {{ $activeMenu == 'kategori-industri' ? 'text-success' : 'text-dark' }}"></i>
                            <span
                                class="nav-link {{ $activeMenu == 'kategori-industri' ? 'active text-success' : '' }}">Kategori
                                Industri</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="dropdown-item d-flex align-items-center py-2 text-sm {{ $activeMenu == 'industri' ? 'active text-success' : '' }}"
                            href="{{ route('industri.index') }}">
                            <i
                                class="fas fa-shop me-2 {{ $activeMenu == 'industri' ? 'text-success' : 'text-dark' }}"></i>
                            <span class="nav-link {{ $activeMenu == 'industri' ? 'text-success' : 'text-dark' }}">Daftar
                                Industri</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ in_array($activeMenu, ['kategori_skill', 'skill']) ? 'active text-success' : '' }}"
                    href="#" id="navbarDropdownSkill" role="button" data-bs-toggle="collapse"
                    data-bs-target="#dropdownMenuSkill"
                    aria-expanded="{{ in_array($activeMenu, ['kategori_skill', 'skill']) ? 'true' : 'false' }}"
                    aria-controls="dropdownMenuSkill">
                    <i
                        class="fas fa-book me-2 {{ in_array($activeMenu, ['kategori_skill', 'skill']) ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Skill</span>
                </a>
                <ul class="collapse list-unstyled ps-4 {{ in_array($activeMenu, ['kategori_skill', 'skill']) ? 'show' : '' }}"
                    id="dropdownMenuSkill">
                    <li class="nav-item">
                        <a class="dropdown-item d-flex align-items-center py-2 text-sm border-bottom {{ $activeMenu == 'kategori_skill' ? 'active text-success' : '' }}"
                            href="{{ route('kategori-skill.index') }}">
                            <i
                                class="fas fa-tags me-2 {{ $activeMenu == 'kategori_skill' ? 'text-success' : 'text-dark' }}"></i>
                            <span
                                class="nav-link {{ $activeMenu == 'kategori_skill' ? 'text-success' : 'text-dark' }}">Kategori
                                Skill</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="dropdown-item d-flex align-items-center py-2 text-sm border-bottom {{ $activeMenu == 'skill' ? 'active text-success' : '' }}"
                            href="{{ route('detail-skill.index') }}">
                            <i
                                class="fas fa-list-alt me-2 {{ $activeMenu == 'skill' ? 'text-success' : 'text-dark' }}"></i>
                            <span class="nav-link {{ $activeMenu == 'skill' ? 'text-success' : 'text-dark' }}">Daftar
                                Skill</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeMenu == 'detail_lowongan' ? 'active text-success' : '' }}"
                    href="{{ route('lowongan.index') }}">
                    <i
                        class="fas fa-briefcase me-2 {{ $activeMenu == 'detail_lowongan' ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Lowongan</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ $activeMenu == 'pengajuan' ? 'active text-success' : '' }}" {{-- Pastikan $activeMenu untuk menu ini juga 'pengajuan' untuk @auth('web') --}}
                    href="{{ route('pengajuan.index') }}">
                    <i class="fas fa-scroll me-2 {{ $activeMenu == 'kota' ? 'text-success' : 'text-dark' }}"></i>
                    <span class="nav-link-text ms-1">Pengajuan</span>
                </a>
            </li>
        @endauth

        <li class="nav-item mt-3">
            <h6 class="ps-4 ms-2 text-uppercase text-xs font-weight-bolder opacity-6">Account pages</h6>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $activeMenu == 'profile' ? 'active text-success' : '' }}"
                href="{{ route('profile.index') }}">
                <i class="fas fa-user me-2 {{ $activeMenu == 'profile' ? 'text-success' : 'text-dark' }}"></i>
                <span class="nav-link-text ms-1">Profile</span>
            </a>
        </li>
    </ul>
</div>
@auth('web')
    <div class="sidenav-footer mx-3">
        <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
            <div class="full-background"
                style="background-image: url('{{ asset('softTemplate/assets/img/curved-images/white-curved.jpg') }}')">
            </div>
            <div class="card-body text-start p-3 w-100">
                <div
                    class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
                    <i class="fas fa-headset text-dark text-lg top-0" aria-hidden="true" id="sidenavCardIcon"></i>
                </div>
                <div class="docs-info">
                    <h6 class="text-white up mb-0">Butuh bantuan?</h6>
                    <p class="text-xs font-weight-bold">Hubungi Admin</p>
                    <a href="https://api.whatsapp.com/send/?phone=6281357717345" target="_blank"
                        class="btn btn-white btn-sm w-100 mb-0 d-flex align-items-center justify-content-center gap-2">
                        <img src="{{ asset('assets/wa.svg') }}" alt="WhatsApp Logo" style="width: 16px; height: 16px;">
                        Whatsapp
                    </a>

                </div>
            </div>
        </div>
        <a class="btn btn-dark mt-3 w-100" href="{{ url('logout') }}">Log out</a>
    </div>
@endauth
@auth('dosen')
    <div class="sidenav-footer mx-3">
        <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
            <div class="full-background"
                style="background-image: url('{{ asset('softTemplate/assets/img/curved-images/white-curved.jpg') }}')">
            </div>
            <div class="card-body text-start p-3 w-100">
                <div
                    class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
                    <i class="fas fa-headset text-dark text-gradient text-lg top-0" aria-hidden="true"
                        id="sidenavCardIcon"></i>
                </div>
                <div class="docs-info">
                    <h6 class="text-white up mb-0">Butuh bantuan?</h6>
                    <p class="text-xs font-weight-bold">Hubungi Admin</p>
                    <a href="https://api.whatsapp.com/send/?phone=6281357717345" target="_blank"
                        class="btn btn-white btn-sm w-100 mb-0">Whatsapp</a>
                </div>
            </div>
        </div>
        <a class="btn btn-dark mt-3 w-100" href="{{ url('logout') }}">Log out</a>
    </div>
@endauth
@auth('mahasiswa')
    <div class="sidenav-footer mx-3">
        <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
            <div class="full-background"
                style="background-image: url('{{ asset('softTemplate/assets/img/curved-images/white-curved.jpg') }}')">
            </div>
            <div class="card-body text-start p-3 w-100">
                <div
                    class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
                    <i class="fas fa-headset text-dark text-gradient text-lg top-0" aria-hidden="true"
                        id="sidenavCardIcon"></i>
                </div>
                <div class="docs-info">
                    <h6 class="text-white up mb-0">Butuh bantuan?</h6>
                    <p class="text-xs font-weight-bold">Hubungi Admin</p>
                    <a href="https://api.whatsapp.com/send/?phone=6281357717345" target="_blank"
                        class="btn btn-white btn-sm w-100 mb-0">Whatsapp</a>
                </div>
            </div>
        </div>
        <a class="btn btn-dark mt-3 w-100" href="{{ url('logout') }}">Log out</a>
    </div>
@endauth
@auth('industri')
    <div class="sidenav-footer mx-3">
        <div class="card card-background shadow-none card-background-mask-secondary" id="sidenavCard">
            <div class="full-background"
                style="background-image: url('{{ asset('softTemplate/assets/img/curved-images/white-curved.jpg') }}')">
            </div>
            <div class="card-body text-start p-3 w-100">
                <div
                    class="icon icon-shape icon-sm bg-white shadow text-center mb-3 d-flex align-items-center justify-content-center border-radius-md">
                    <i class="fas fa-headset text-dark text-gradient text-lg top-0" aria-hidden="true"
                        id="sidenavCardIcon"></i>
                </div>
                <div class="docs-info">
                    <h6 class="text-white up mb-0">Butuh bantuan?</h6>
                    <p class="text-xs font-weight-bold">Hubungi Admin</p>
                    <a href="https://api.whatsapp.com/send/?phone=6281357717345" target="_blank"
                        class="btn btn-white btn-sm w-100 mb-0">Whatsapp</a>
                </div>
            </div>
        </div>
        <a class="btn btn-dark mt-3 w-100" href="{{ url('logout') }}">Log out</a>
    </div>
@endauth
