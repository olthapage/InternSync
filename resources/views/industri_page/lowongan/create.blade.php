@extends('layouts.template')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-dark shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="mb-0">Tambah Lowongan Baru - {{ $industri->industri_nama }}</h3>
                    <a href="{{ route('industri.lowongan.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form action="{{ route('industri.lowongan.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="judul_lowongan">Judul Lowongan <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('judul_lowongan') is-invalid @enderror"
                                           id="judul_lowongan" name="judul_lowongan" value="{{ old('judul_lowongan') }}" required>
                                    @error('judul_lowongan')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kategori_skill_id">Kategori Lowongan <span class="text-danger">*</span></label>
                                    <select class="form-control @error('kategori_skill_id') is-invalid @enderror"
                                            id="kategori_skill_id" name="kategori_skill_id" required>
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($kategoriSkills as $kategori)
                                            <option value="{{ $kategori->kategori_skill_id }}" {{ old('kategori_skill_id') == $kategori->kategori_skill_id ? 'selected' : '' }}>
                                                {{ $kategori->kategori_nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_skill_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi Lowongan <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi"
                                      name="deskripsi" rows="5" required>{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="slot">Jumlah Slot Magang <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('slot') is-invalid @enderror"
                                           id="slot" name="slot" value="{{ old('slot', 1) }}" min="1" required>
                                    @error('slot')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pendaftaran_tanggal_mulai">Pendaftaran Dibuka <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('pendaftaran_tanggal_mulai') is-invalid @enderror"
                                           id="pendaftaran_tanggal_mulai" name="pendaftaran_tanggal_mulai" value="{{ old('pendaftaran_tanggal_mulai') }}" required>
                                    @error('pendaftaran_tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="pendaftaran_tanggal_selesai">Pendaftaran Ditutup <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('pendaftaran_tanggal_selesai') is-invalid @enderror"
                                           id="pendaftaran_tanggal_selesai" name="pendaftaran_tanggal_selesai" value="{{ old('pendaftaran_tanggal_selesai') }}" required>
                                    @error('pendaftaran_tanggal_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                             <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai Magang <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                           id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}" required>
                                    @error('tanggal_mulai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tanggal_selesai">Tanggal Selesai Magang <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                           id="tanggal_selesai" name="tanggal_selesai" value="{{ old('tanggal_selesai') }}" required>
                                    @error('tanggal_selesai')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <h5>Skill yang Dibutuhkan & Bobot SPK</h5>
                        <p class="text-muted small">Tambahkan skill yang dibutuhkan untuk lowongan ini beserta bobotnya (misal: 1-100) untuk perhitungan SPK.</p>

                        <div id="skills-container">
                            {{-- Baris skill akan ditambahkan di sini oleh JavaScript --}}
                            {{-- Contoh untuk old input (jika validasi gagal) --}}
                            @if(old('skills'))
                                @foreach(old('skills') as $index => $skill_id)
                                <div class="row skill-item mb-2">
                                    <div class="col-md-6">
                                        <select name="skills[]" class="form-control">
                                            <option value="">-- Pilih Skill --</option>
                                            @foreach($detailSkills as $skill)
                                            <option value="{{ $skill->skill_id }}" {{ $skill_id == $skill->skill_id ? 'selected' : '' }}>
                                                {{ $skill->skill_nama }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="number" name="bobot[]" class="form-control" placeholder="Bobot (1-100)"
                                               value="{{ old('bobot.'.$index) }}" min="1" max="100">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-skill-btn w-100">Hapus</button>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        <button type="button" id="add-skill-btn" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="fas fa-plus mr-1"></i> Tambah Skill
                        </button>

                        <hr>
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-dark">
                                <i class="fas fa-save mr-1"></i> Simpan Lowongan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('css')
<style>
    /* Styling tambahan jika diperlukan */
</style>
@endpush

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const skillsContainer = document.getElementById('skills-container');
    const addSkillBtn = document.getElementById('add-skill-btn');
    // Opsi skill yang akan digunakan di dropdown baru
    const skillOptionsHtml = `
        <option value="">-- Pilih Skill --</option>
        @foreach ($detailSkills as $skill)
            <option value="{{ $skill->skill_id }}">{{ $skill->skill_nama }}</option>
        @endforeach
    `;

    addSkillBtn.addEventListener('click', function () {
        const newSkillRow = document.createElement('div');
        newSkillRow.classList.add('row', 'skill-item', 'mb-2');
        newSkillRow.innerHTML = `
            <div class="col-md-6">
                <select name="skills[]" class="form-control" required>
                    ${skillOptionsHtml}
                </select>
            </div>
            <div class="col-md-4">
                <input type="number" name="bobot[]" class="form-control" placeholder="Bobot (1-100)" min="1" max="100" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm remove-skill-btn w-100">Hapus</button>
            </div>
        `;
        skillsContainer.appendChild(newSkillRow);
    });

    skillsContainer.addEventListener('click', function (e) {
        if (e.target && e.target.classList.contains('remove-skill-btn')) {
            e.target.closest('.skill-item').remove();
        }
    });
});
</script>
@endpush
