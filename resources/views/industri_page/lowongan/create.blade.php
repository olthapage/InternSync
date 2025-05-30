@extends('layouts.template')

@section('title', 'Tambah Lowongan Baru') {{-- Tambahkan title jika belum ada --}}

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card border-dark shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    {{-- Di controller Anda $industri adalah Auth::user(), jadi pastikan Auth::user() memiliki properti industri_nama --}}
                    <h3 class="mb-0">Tambah Lowongan Baru - {{ $industri->industri_nama ?? ($industri->nama ?? 'Industri') }}</h3>
                    <a href="{{ route('industri.lowongan.index') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-arrow-left mr-1"></i> Kembali
                    </a>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <p class="font-weight-bold">Terdapat kesalahan berikut:</p>
                            <ul class="mb-0 ms-3">
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

                        {{-- ... (Bagian form Judul, Kategori, Deskripsi, Alamat, Slot, Tanggal tetap sama seperti yang Anda berikan) ... --}}
                        {{-- Pastikan field untuk provinsi, kota, alamat_lengkap ada di DetailLowonganModel dan $fillable nya jika ingin disimpan --}}
                        {{-- Contoh bagian form yang ada di kode Anda: --}}

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
                                                {{ $kategori->kategori_nama }} {{-- Pastikan ini nama field yang benar --}}
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

                        {{-- === PEMBAGIAN ALAMAT (sesuai kode Anda) === --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="provinsi_id">Provinsi <span class="text-danger">*</span></label>
                                    <select class="form-control @error('provinsi_id') is-invalid @enderror"
                                            id="provinsi_id" name="provinsi_id" {{-- required --}}> {{-- Anda perlu mengisi $provinsis dari controller jika mau dinamis --}}
                                        <option value="">-- Pilih Provinsi --</option>
                                        {{-- Contoh Hardcoded dari kode Anda --}}
                                        <option value="11" {{ old('provinsi_id', optional($industri->kota)->provinsi_id) == '11' ? 'selected' : '' }}>JAWA TIMUR</option>
                                        <option value="12" {{ old('provinsi_id', optional($industri->kota)->provinsi_id) == '12' ? 'selected' : '' }}>JAWA TENGAH</option>
                                        <option value="13" {{ old('provinsi_id', optional($industri->kota)->provinsi_id) == '13' ? 'selected' : '' }}>JAWA BARAT</option>
                                    </select>
                                     @error('provinsi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                             <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kota_id">Kota <span class="text-danger">*</span></label> {{-- Ganti label dan ID --}}
                                    <select class="form-control @error('kota_id') is-invalid @enderror"
                                            id="kota_id" name="kota_id" {{-- required --}}> {{-- Akan diisi via JS atau dari controller --}}
                                        <option value="">-- Pilih Kota --</option>
                                         {{-- Contoh Hardcoded dari kode Anda, sesuaikan dengan logika dependent dropdown jika ada --}}
                                        <option value="1101" {{ old('kota_id', optional($industri)->kota_id) == '1101' ? 'selected' : '' }}>SURABAYA</option>
                                        <option value="1102" {{ old('kota_id', optional($industri)->kota_id) == '1102' ? 'selected' : '' }}>MALANG</option>
                                        <option value="1103" {{ old('kota_id', optional($industri)->kota_id) == '1103' ? 'selected' : '' }}>KEDIRI</option>
                                    </select>
                                     @error('kota_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="alamat_lengkap">Alamat Lengkap (Jalan, No, RT/RW, Kel/Kec) <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('alamat_lengkap') is-invalid @enderror" id="alamat_lengkap"
                                      name="alamat_lengkap" rows="3" {{-- required --}}>{{ old('alamat_lengkap', optional($industri)->alamat) }}</textarea> {{-- Sesuaikan old() jika field industri berbeda --}}
                            @error('alamat_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        {{-- === AKHIR PEMBAGIAN ALAMAT === --}}

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
                        <h5>Skill yang Dibutuhkan & Level Kompetensi</h5>
                        <p class="text-muted small">Tambahkan skill yang dibutuhkan untuk lowongan ini beserta tingkat kompetensi yang diharapkan.</p>

                        <div id="skills-container">
                            {{-- Baris skill akan ditambahkan di sini oleh JavaScript --}}
                            @if(is_array(old('skills')) && count(old('skills')) > 0)
                                @foreach(old('skills') as $index => $skill_id)
                                    @if(!empty($skill_id)) {{-- Hanya proses jika skill_id ada --}}
                                    <div class="row skill-item mb-2">
                                        <div class="col-md-6">
                                            <select name="skills[]" class="form-control @error('skills.'.$index) is-invalid @enderror">
                                                <option value="">-- Pilih Skill --</option>
                                                @foreach($detailSkills as $skill)
                                                <option value="{{ $skill->skill_id }}" {{ $skill_id == $skill->skill_id ? 'selected' : '' }}>
                                                    {{ $skill->skill_nama }}
                                                </option>
                                                @endforeach
                                            </select>
                                            @error('skills.'.$index) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <select name="levels[]" class="form-control @error('levels.'.$index) is-invalid @enderror">
                                                <option value="">-- Pilih Level --</option>
                                                <option value="Beginner" {{ old('levels.'.$index) == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                                <option value="Intermediate" {{ old('levels.'.$index) == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                <option value="Expert" {{ old('levels.'.$index) == 'Expert' ? 'selected' : '' }}>Expert</option>
                                            </select>
                                            @error('levels.'.$index) <div class="invalid-feedback d-block">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm remove-skill-btn w-100">Hapus</button>
                                        </div>
                                    </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

                        <button type="button" id="add-skill-btn" class="btn btn-outline-primary btn-sm mt-2">
                            <i class="fas fa-plus mr-1"></i> Tambah Skill
                        </button>

                        <hr class="mt-4">
                        <h5>Bobot Kriteria Lainnya (untuk SPK)</h5>
                        <p class="text-muted small">Tentukan bobot untuk kriteria nilai akademik (IPK) dan kesesuaian lokasi. Bobot ini akan digunakan dalam perhitungan SPK untuk merangking pelamar.</p>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bobot_akademik">Bobot Nilai Akademik (IPK) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('bobot_akademik') is-invalid @enderror"
                                           id="bobot_akademik" name="bobot_akademik" value="{{ old('bobot_akademik', 30) }}" {{-- Contoh default bobot --}}
                                           placeholder="Bobot (1-100)" min="1" max="100" required>
                                    @error('bobot_akademik')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                             <div class="col-md-4">
                                <div class="form-group">
                                    <label for="bobot_lokasi">Bobot Lokasi <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('bobot_lokasi') is-invalid @enderror"
                                           id="bobot_lokasi" name="bobot_lokasi" value="{{ old('bobot_lokasi', 20) }}" {{-- Contoh default bobot --}}
                                           placeholder="Bobot (1-100)" min="1" max="100" required>
                                    @error('bobot_lokasi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group text-right mt-4">
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
    .form-group label { font-weight: 500; }
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

    // Opsi level yang akan digunakan
    const levelOptionsHtml = `
        <option value="">-- Pilih Level --</option>
        <option value="Beginner">Beginner</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Expert">Expert</option>
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
                <select name="levels[]" class="form-control" required>
                    ${levelOptionsHtml}
                </select>
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

    // Script untuk dependent dropdown alamat (Provinsi -> Kota) jika Anda belum punya
    // Anda perlu endpoint untuk mengambil data kota berdasarkan provinsi_id
    // $('#provinsi_id').on('change', function() {
    //     let provinsiId = $(this).val();
    //     $('#kota_id').empty().append('<option value="">-- Pilih Kota --</option>');
    //     if (provinsiId) {
    //         $.ajax({
    //             url: '/api/get-kota-by-provinsi/' + provinsiId, // Buat endpoint ini
    //             type: 'GET',
    //             dataType: 'json',
    //             success: function(data) {
    //                 if(data) {
    //                     $.each(data, function(key, kota) {
    //                         $('#kota_id').append('<option value="'+ kota.id +'">'+ kota.nama +'</option>');
    //                     });
    //                 }
    //             }
    //         });
    //     }
    // });
});
</script>
@endpush
