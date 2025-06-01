<div class="d-flex align-items-center">
    <img src="{{ $mahasiswa->foto ? asset('storage/mahasiswa/foto/' . $mahasiswa->foto) : asset('assets/default-profile.png') }}"
         alt="{{ $mahasiswa->nama_lengkap }}" class="avatar-sm-table rounded-circle me-2">
    <div>
        <span class="fw-bold">{{ $mahasiswa->nama_lengkap }}</span><br>
        <small class="text-muted">{{ $mahasiswa->nim }}</small>
    </div>
</div>
