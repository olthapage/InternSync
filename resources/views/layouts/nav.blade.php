@php
    // Inisialisasi variabel dengan nilai default untuk tamu (guest)
    $user = null;
    $nama = 'Guest';
    $identifier = '';
    $fotoUrl = asset('assets/default-profile.png');

    // Tentukan pengguna yang aktif dari berbagai guard
    if (Auth::guard('mahasiswa')->check()) $user = Auth::guard('mahasiswa')->user();
    elseif (Auth::guard('dosen')->check()) $user = Auth::guard('dosen')->user();
    elseif (Auth::guard('web')->check()) $user = Auth::guard('web')->user();
    elseif (Auth::guard('industri')->check()) $user = Auth::guard('industri')->user();

    // Jika ada pengguna yang login, siapkan datanya
    if ($user) {
        // 1. Tentukan semua data spesifik (nama, identifier, folder, nama kolom, gambar default) dalam satu tempat
        [$nama, $identifier, $storagePath, $imageColumn, $defaultImage] = match (true) {
            Auth::guard('mahasiswa')->check() => [
                $user->nama_lengkap,
                $user->nim,
                'mahasiswa/foto',
                'foto',
                asset('assets/default-profile.png')
            ],
            Auth::guard('dosen')->check() => [
                $user->nama_lengkap,
                $user->nip,
                'foto',
                'foto',
                asset('assets/default-profile.png')
            ],
            Auth::guard('industri')->check() => [
                $user->industri_nama,
                'Industri Mitra',
                'logo_industri',
                'logo',
                asset('assets/default-industri.png')
            ],
            Auth::guard('web')->check() => [
                $user->nama_lengkap,
                'Administrator',
                'foto',
                'foto',
                asset('assets/default-profile.png')
            ],
        };

        // 2. Tetapkan gambar default yang sesuai
        $fotoUrl = $defaultImage;

        if (!empty($user->{$imageColumn})) {
            $filePath = $storagePath . '/' . $user->{$imageColumn};
            if (Illuminate\Support\Facades\Storage::disk('public')->exists($filePath)) {
                $fotoUrl = asset('storage/' . $filePath);
            }
        }
    }
@endphp

<div class="container-fluid py-1 px-3">
    <div class="d-flex justify-content-between align-items-center w-100">
        <div class="d-flex align-items-center justify content-center">
            <div class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </a>
            </div>
        </div>



        @if ($user)
            <div class="d-flex align-items-center">
                <div class="text-end me-3">
                    <h6 class="mb-0 text-sm font-weight-bold" style="color: #344767;">{{ $nama }}</h6>
                    @if ($identifier)
                        <p class="text-xs text-secondary mb-0">{{ $identifier }}</p>
                    @endif
                </div>

                <a href="{{ route('profile.index') }}" class="avatar avatar-md">
                    <img src="{{ $fotoUrl }}" alt="Foto Profil" class="w-100 border-radius-xl"
                         onerror="this.onerror=null;this.src='{{ asset('assets/default-profile.png') }}';">
                </a>
            </div>
        @endif

    </div>
</div>
