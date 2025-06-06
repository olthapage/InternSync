@extends('layouts.template')

@section('title', 'Tambah Lowongan Baru Industri')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card border-dark shadow-sm">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h3 class="mb-0">Tambah Lowongan Baru -
                            {{ $industri->industri_nama ?? ($industri->nama ?? 'Industri') }}</h3>
                        <a href="{{ route('industri.lowongan.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-arrow-left me-1"></i> Kembali
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
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif

                        <form action="{{ route('industri.lowongan.store') }}" method="POST">
                            @csrf
                            {{-- ... (Field Judul, Kategori, Deskripsi tetap sama) ... --}}
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label for="judul_lowongan" class="form-label">Judul Lowongan <span
                                                class="text-danger">*</span></label>
                                        <input type="text"
                                            class="form-control @error('judul_lowongan') is-invalid @enderror"
                                            id="judul_lowongan" name="judul_lowongan" value="{{ old('judul_lowongan') }}"
                                            required>
                                        @error('judul_lowongan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="kategori_skill_id" class="form-label">Kategori Lowongan <span
                                                class="text-danger">*</span></label>
                                        <select class="form-select @error('kategori_skill_id') is-invalid @enderror"
                                            id="kategori_skill_id" name="kategori_skill_id" required>
                                            <option value="">-- Pilih Kategori --</option>
                                            @foreach ($kategoriSkills as $kategori)
                                                <option value="{{ $kategori->kategori_skill_id }}"
                                                    {{ old('kategori_skill_id') == $kategori->kategori_skill_id ? 'selected' : '' }}>
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
                            <div class="form-group mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi Lowongan <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="5"
                                    required>{{ old('deskripsi') }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            {{-- === BAGIAN ALAMAT SPESIFIK LOWONGAN === --}}
                            <hr class="my-4">
                            <h5>Lokasi Lowongan</h5>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="use_specific_location"
                                    id="use_specific_location" value="1"
                                    {{ old('use_specific_location', optional($industri)->alamat_spesifik_lowongan_default ?? false) ? 'checked' : '' }}
                                    {{-- Tambahkan default dari industri jika ada --}} onchange="toggleSpecificLocationFields()">
                                <label class="form-check-label" for="use_specific_location">
                                    Gunakan alamat spesifik untuk lowongan ini (berbeda dari alamat utama perusahaan)?
                                </label>
                            </div>
                            <p class="text-muted small">Jika tidak dicentang, alamat lowongan akan mengikuti alamat utama
                                perusahaan: <br>
                                @php
                                    $alamatIndustriDisplay =
                                        optional(optional($industri)->kota)->provinsi->provinsi_nama ?? '';
                                    if (optional(optional($industri)->kota)->kota_nama) {
                                        $alamatIndustriDisplay .=
                                            ($alamatIndustriDisplay ? ', ' : '') . optional($industri->kota)->kota_nama;
                                    }
                                    if (optional($industri)->alamat_lengkap) {
                                        // Asumsi industri punya alamat_lengkap
                                        $alamatIndustriDisplay .=
                                            ($alamatIndustriDisplay ? ', ' : '') . $industri->alamat_lengkap;
                                    }
                                    echo $alamatIndustriDisplay ?: 'Alamat utama belum diatur';
                                @endphp
                            </p>

                            <div id="specific-location-fields"
                                style="{{ old('use_specific_location', optional($industri)->alamat_spesifik_lowongan_default ?? false) ? '' : 'display:none;' }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="lokasi_provinsi_id" class="form-label">Provinsi Lowongan</label>
                                            <select class="form-select @error('lokasi_provinsi_id') is-invalid @enderror"
                                                id="lokasi_provinsi_id" name="lokasi_provinsi_id">
                                                <option value="">-- Pilih Provinsi --</option>
                                                @foreach ($provinsiList as $provinsi)
                                                    <option value="{{ $provinsi->provinsi_id }}"
                                                        {{ old('lokasi_provinsi_id') == $provinsi->provinsi_id ? 'selected' : '' }}>
                                                        {{ $provinsi->provinsi_nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('lokasi_provinsi_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group mb-3">
                                            <label for="lokasi_kota_id" class="form-label">Kota Lowongan</label>
                                            <select class="form-select @error('lokasi_kota_id') is-invalid @enderror"
                                                id="lokasi_kota_id" name="lokasi_kota_id">
                                                <option value="">-- Pilih Provinsi Terlebih Dahulu --</option>
                                                {{-- Opsi Kota akan diisi oleh JavaScript (AJAX) --}}
                                            </select>
                                            @error('lokasi_kota_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="lokasi_alamat_lengkap" class="form-label">Alamat Lengkap Lowongan</label>
                                    <textarea class="form-control @error('lokasi_alamat_lengkap') is-invalid @enderror" id="lokasi_alamat_lengkap"
                                        name="lokasi_alamat_lengkap" rows="3">{{ old('lokasi_alamat_lengkap') }}</textarea>
                                    @error('lokasi_alamat_lengkap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            {{-- === AKHIR BAGIAN ALAMAT SPESIFIK LOWONGAN === --}}

                            {{-- ... (Field Slot, Tanggal Pendaftaran, Tanggal Magang, Skill, Bobot Kriteria Lainnya tetap sama) ... --}}
                            <hr class="my-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="slot" class="form-label">Jumlah Slot Magang <span
                                                class="text-danger">*</span></label>
                                        <input type="number" class="form-control @error('slot') is-invalid @enderror"
                                            id="slot" name="slot" value="{{ old('slot', 1) }}" min="1"
                                            required>
                                        @error('slot')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="pendaftaran_tanggal_mulai" class="form-label">Pendaftaran Dibuka <span
                                                class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('pendaftaran_tanggal_mulai') is-invalid @enderror"
                                            id="pendaftaran_tanggal_mulai" name="pendaftaran_tanggal_mulai"
                                            value="{{ old('pendaftaran_tanggal_mulai') }}" required>
                                        @error('pendaftaran_tanggal_mulai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="pendaftaran_tanggal_selesai" class="form-label">Pendaftaran Ditutup
                                            <span class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('pendaftaran_tanggal_selesai') is-invalid @enderror"
                                            id="pendaftaran_tanggal_selesai" name="pendaftaran_tanggal_selesai"
                                            value="{{ old('pendaftaran_tanggal_selesai') }}" required>
                                        @error('pendaftaran_tanggal_selesai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai Magang <span
                                                class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('tanggal_mulai') is-invalid @enderror"
                                            id="tanggal_mulai" name="tanggal_mulai" value="{{ old('tanggal_mulai') }}"
                                            required>
                                        @error('tanggal_mulai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group mb-3">
                                        <label for="tanggal_selesai" class="form-label">Tanggal Selesai Magang <span
                                                class="text-danger">*</span></label>
                                        <input type="date"
                                            class="form-control @error('tanggal_selesai') is-invalid @enderror"
                                            id="tanggal_selesai" name="tanggal_selesai"
                                            value="{{ old('tanggal_selesai') }}" required>
                                        @error('tanggal_selesai')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5>Fasilitas, Tipe Kerja, dan Upah</h5>
                            <div class="row">
                                {{-- Input Upah --}}
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="upah" class="form-label">Uang Saku / Upah (per bulan)</label>
                                        <div class="input-group">
                                            <span class="input-group-text">Rp</span>
                                            <input type="number"
                                                class="form-control @error('upah') is-invalid @enderror" id="upah"
                                                name="upah" value="{{ old('upah', 0) }}" min="0" required>
                                        </div>
                                        <div class="form-text">Isi dengan 0 jika tidak ada uang saku.</div>
                                        @error('upah')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                {{-- Checklist Tipe Kerja --}}
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Tipe Kerja yang Tersedia <span
                                                class="text-danger">*</span></label>
                                        @foreach ($tipeKerjaList as $tipe)
                                            <div class="form-check">
                                                <input class="form-check-input @error('tipe_kerja') is-invalid @enderror"
                                                    type="checkbox" name="tipe_kerja[]"
                                                    value="{{ $tipe->tipe_kerja_id }}"
                                                    id="tipe_kerja_{{ $tipe->tipe_kerja_id }}"
                                                    {{ is_array(old('tipe_kerja')) && in_array($tipe->tipe_kerja_id, old('tipe_kerja')) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="tipe_kerja_{{ $tipe->tipe_kerja_id }}">
                                                    {{ $tipe->nama_tipe_kerja }}
                                                </label>
                                            </div>
                                        @endforeach
                                        @error('tipe_kerja')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{-- Checklist Fasilitas --}}
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label class="form-label">Fasilitas yang Disediakan</label>
                                        @foreach ($fasilitasList as $fasilitas)
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="fasilitas[]"
                                                    value="{{ $fasilitas->fasilitas_id }}"
                                                    id="fasilitas_{{ $fasilitas->fasilitas_id }}"
                                                    {{ is_array(old('fasilitas')) && in_array($fasilitas->fasilitas_id, old('fasilitas')) ? 'checked' : '' }}>
                                                <label class="form-check-label"
                                                    for="fasilitas_{{ $fasilitas->fasilitas_id }}">
                                                    {{ $fasilitas->nama_fasilitas }}
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5>Skill yang Dibutuhkan & Level Kompetensi</h5>
                            <p class="text-muted small">Tambahkan skill yang dibutuhkan untuk lowongan ini beserta tingkat
                                kompetensi yang diharapkan. Bobot kepentingan skill akan diatur default (misalnya, sama
                                rata) dan dapat disesuaikan lebih lanjut oleh admin jika diperlukan untuk SPK.</p>
                            <div id="skills-container">
                                @if (is_array(old('skills')) && count(old('skills')) > 0)
                                    @foreach (old('skills') as $index => $skill_id)
                                        @if (!empty($skill_id))
                                            <div class="row skill-item mb-2 align-items-center">
                                                <div class="col-md-5">
                                                    <select name="skills[]"
                                                        class="form-select form-select-sm @error('skills.' . $index) is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Pilih Skill --</option>
                                                        @foreach ($detailSkills as $skill)
                                                            <option value="{{ $skill->skill_id }}"
                                                                {{ $skill_id == $skill->skill_id ? 'selected' : '' }}>
                                                                {{ $skill->skill_nama }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('skills.' . $index)
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-4">
                                                    <select name="levels[]"
                                                        class="form-select form-select-sm @error('levels.' . $index) is-invalid @enderror"
                                                        required>
                                                        <option value="">-- Pilih Level --</option>
                                                        <option value="Beginner"
                                                            {{ old('levels.' . $index) == 'Beginner' ? 'selected' : '' }}>
                                                            Beginner</option>
                                                        <option value="Intermediate"
                                                            {{ old('levels.' . $index) == 'Intermediate' ? 'selected' : '' }}>
                                                            Intermediate</option>
                                                        <option value="Expert"
                                                            {{ old('levels.' . $index) == 'Expert' ? 'selected' : '' }}>
                                                            Expert</option>
                                                    </select>
                                                    @error('levels.' . $index)
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="col-md-1">
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm remove-skill-btn w-100 p-2"
                                                        title="Hapus Skill"><i class="fas fa-trash"></i></button>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif
                            </div>
                            <button type="button" id="add-skill-btn" class="btn btn-outline-primary btn-sm mt-2 mb-3">
                                <i class="fas fa-plus me-1"></i> Tambah Skill
                            </button>

                            <hr class="mt-4">
                            <div class="form-group text-end mt-4">
                                <button type="submit" class="btn btn-dark">
                                    <i class="fas fa-save me-1"></i> Simpan Lowongan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const useSpecificLocationCheckbox = document.getElementById('use_specific_location');
            const specificLocationFieldsDiv = document.getElementById('specific-location-fields');
            const lokasiProvinsiSelect = document.getElementById('lokasi_provinsi_id');
            const lokasiKotaSelect = document.getElementById('lokasi_kota_id');
            const lokasiAlamatLengkap = document.getElementById('lokasi_alamat_lengkap');

            // Fungsi untuk mengambil dan mengisi dropdown kota
            function fetchKotaByProvinsi(provinsiId, kotaToSelect = null) {
                lokasiKotaSelect.innerHTML = '<option value="">-- Memuat Kota... --</option>';
                lokasiKotaSelect.disabled = true;

                if (!provinsiId) {
                    lokasiKotaSelect.innerHTML = '<option value="">-- Pilih Provinsi Terlebih Dahulu --</option>';
                    lokasiKotaSelect.disabled = false;
                    return;
                }

                // Ganti {{-- route('api.kota_by_provinsi', ['provinsi_id' => ':provinsiId']) --}} dengan URL yang sesuai
                // Cara paling aman adalah membuat URL dasar di PHP lalu menggabungkannya di JS
                // atau menggunakan helper `url()` jika route tidak memiliki parameter dinamis selain di akhir.
                // Untuk route dengan parameter di tengah, lebih baik definisikan URL dasar.
                const url = `{{ url('/kota-by-provinsi') }}/${provinsiId}`;

                fetch(url)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok: ' + response.statusText + ' (URL: ' +
                                response.url + ')');
                        }
                        return response.json();
                    })
                    .then(kotas => {
                        lokasiKotaSelect.innerHTML = ''; // Kosongkan
                        if (Array.isArray(kotas) && kotas.length === 0) {
                            lokasiKotaSelect.appendChild(new Option('-- Tidak ada kota untuk provinsi ini --',
                                ''));
                        } else if (Array.isArray(kotas)) {
                            lokasiKotaSelect.appendChild(new Option('-- Pilih Kota --', ''));
                            kotas.forEach(function(kota) {
                                const option = new Option(kota.kota_nama, kota.kota_id);
                                lokasiKotaSelect.appendChild(option);
                            });
                        } else {
                            lokasiKotaSelect.appendChild(new Option('-- Format data kota tidak sesuai --', ''));
                            console.error('Data kota tidak valid:', kotas);
                        }

                        if (kotaToSelect) {
                            const optionExists = Array.from(lokasiKotaSelect.options).some(opt => opt.value ==
                                kotaToSelect);
                            if (optionExists) {
                                lokasiKotaSelect.value = kotaToSelect;
                            }
                        }
                        lokasiKotaSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error fetching kota:', error);
                        lokasiKotaSelect.innerHTML = '<option value="">-- Gagal memuat kota --</option>';
                        lokasiKotaSelect.disabled = false;
                    });
            }

            // Fungsi untuk menampilkan/menyembunyikan field alamat spesifik
            window.toggleSpecificLocationFields =
        function() { // Jadikan global agar onchange di HTML bisa memanggilnya
                const isChecked = useSpecificLocationCheckbox.checked;
                if (isChecked) {
                    specificLocationFieldsDiv.style.display = 'block';
                    lokasiProvinsiSelect.setAttribute('required', 'required');
                    lokasiKotaSelect.setAttribute('required', 'required');
                    lokasiAlamatLengkap.setAttribute('required', 'required');

                    // Jika provinsi sudah terpilih saat checkbox dicentang, load kotanya
                    const selectedProvinsi = lokasiProvinsiSelect.value;
                    if (selectedProvinsi) {
                        const kotaLama = "{{ old('lokasi_kota_id') }}"; // Ambil kota lama jika ada
                        // Hanya panggil fetchKotaByProvinsi jika kita berada dalam konteks pengisian form awal
                        // dan provinsi yang terpilih adalah provinsi lama.
                        if ("{{ old('lokasi_provinsi_id') }}" === selectedProvinsi) {
                            fetchKotaByProvinsi(selectedProvinsi, kotaLama);
                        } else {
                            // Jika provinsi berbeda dari old('lokasi_provinsi_id'), berarti user baru saja mengubahnya
                            // atau ini adalah centangan baru tanpa old value provinsi, jadi load tanpa preselect kota lama.
                            fetchKotaByProvinsi(selectedProvinsi, null);
                        }
                    } else {
                        // Jika belum ada provinsi terpilih, reset dropdown kota
                        lokasiKotaSelect.innerHTML =
                            '<option value="">-- Pilih Provinsi Terlebih Dahulu --</option>';
                    }
                } else {
                    specificLocationFieldsDiv.style.display = 'none';
                    lokasiProvinsiSelect.removeAttribute('required');
                    lokasiKotaSelect.removeAttribute('required');
                    lokasiAlamatLengkap.removeAttribute('required');
                    // Reset pilihan jika tidak dicentang
                    // lokasiProvinsiSelect.value = ''; // Opsional, bisa jadi user ingin mempertahankan pilihan provinsinya
                    lokasiKotaSelect.innerHTML =
                        '<option value="">-- Pilih Provinsi Terlebih Dahulu --</option>';
                }
            }

            // Event listener untuk perubahan provinsi
            if (lokasiProvinsiSelect) {
                lokasiProvinsiSelect.addEventListener('change', function() {
                    // Saat provinsi diubah oleh user, kita tidak perlu mengirimkan `old('lokasi_kota_id')`
                    // karena user sedang membuat pilihan baru.
                    fetchKotaByProvinsi(this.value, null);
                });
            }

            // Logika inisialisasi saat halaman dimuat
            // Panggil toggleSpecificLocationFields untuk mengatur tampilan awal berdasarkan old('use_specific_location')
            // Fungsi ini akan menangani pemanggilan fetchKotaByProvinsi jika diperlukan (misalnya, jika checkbox tercentang dan ada old('lokasi_provinsi_id'))
            toggleSpecificLocationFields();


            // --- Logika untuk tambah skill dinamis (tetap sama seperti kode Anda sebelumnya) ---
            const skillsContainer = document.getElementById('skills-container');
            const addSkillBtn = document.getElementById('add-skill-btn');

            const skillOptionsHtml = `
        <option value="">-- Pilih Skill --</option>
        @foreach ($detailSkills as $skill)
            <option value="{{ $skill->skill_id }}">{{ $skill->skill_nama }}</option>
        @endforeach
    `;
            const levelOptionsHtml = `
        <option value="">-- Pilih Level --</option>
        <option value="Beginner">Beginner</option>
        <option value="Intermediate">Intermediate</option>
        <option value="Expert">Expert</option>
    `;

            if (addSkillBtn && skillsContainer) {
                addSkillBtn.addEventListener('click', function() {
                    const newSkillRow = document.createElement('div');
                    newSkillRow.classList.add('row', 'skill-item', 'mb-2', 'align-items-center');
                    // Tambahkan atribut required pada select skill dan level yang baru ditambahkan
                    newSkillRow.innerHTML = `
                <div class="col-md-5">
                    <select name="skills[]" class="form-select form-select-sm" required> ${skillOptionsHtml} </select>
                </div>
                <div class="col-md-4">
                    <select name="levels[]" class="form-select form-select-sm" required> ${levelOptionsHtml} </select>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-danger btn-sm remove-skill-btn w-100 p-2" title="Hapus Skill"><i class="fas fa-trash"></i></button>
                </div>
            `;
                    skillsContainer.appendChild(newSkillRow);
                });

                skillsContainer.addEventListener('click', function(e) {
                    const removeButton = e.target.closest('.remove-skill-btn');
                    if (removeButton) {
                        removeButton.closest('.skill-item').remove();
                    }
                });
            }
        });
    </script>
@endpush
