{{-- resources/views/industri_page/lowongan/partials/edit_form.blade.php --}}

<form action="{{ route('industri.lowongan.update', $lowongan->lowongan_id) }}" method="POST" id="editLowonganForm">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="judul_lowongan" class="form-label">Judul Lowongan</label>
                <input type="text" class="form-control" id="judul_lowongan" name="judul_lowongan"
                    value="{{ old('judul_lowongan', $lowongan->judul_lowongan) }}" required>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="kategori_skill_id" class="form-label">Kategori Lowongan</label>
                <select class="form-select" id="kategori_skill_id" name="kategori_skill_id" required>
                    @foreach ($kategoriSkills as $kategori)
                        <option value="{{ $kategori->kategori_skill_id }}"
                            {{ old('kategori_skill_id', $lowongan->kategori_skill_id) == $kategori->kategori_skill_id ? 'selected' : '' }}>
                            {{ $kategori->kategori_nama }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <div class="mb-3">
                <label for="slot" class="form-label">Jumlah Kuota (Slot)</label>
                <input type="number" class="form-control" id="slot" name="slot"
                    value="{{ old('slot', $lowongan->slot) }}" required min="1">
            </div>
        </div>
    </div>

    <div class="mb-3">
        <label for="deskripsi" class="form-label">Deskripsi Pekerjaan</label>
        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="5" required>{{ old('deskripsi', $lowongan->deskripsi) }}</textarea>
        {{-- Jika Anda menggunakan text editor seperti CKEditor, pastikan inisialisasi ulang di AJAX success --}}
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="pendaftaran_tanggal_mulai" class="form-label">Tanggal Mulai Pendaftaran</label>
                <input type="date" class="form-control" id="pendaftaran_tanggal_mulai"
                    name="pendaftaran_tanggal_mulai"
                    value="{{ old('pendaftaran_tanggal_mulai', $lowongan->pendaftaran_tanggal_mulai->format('Y-m-d')) }}"
                    required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="pendaftaran_tanggal_selesai" class="form-label">Tanggal Selesai Pendaftaran</label>
                <input type="date" class="form-control" id="pendaftaran_tanggal_selesai"
                    name="pendaftaran_tanggal_selesai"
                    value="{{ old('pendaftaran_tanggal_selesai', $lowongan->pendaftaran_tanggal_selesai->format('Y-m-d')) }}"
                    required>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="tanggal_mulai" class="form-label">Tanggal Mulai Pelaksanaan</label>
                <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai"
                    value="{{ old('tanggal_mulai', $lowongan->tanggal_mulai->format('Y-m-d')) }}" required>
            </div>
        </div>
        <div class="col-md-6">
            <div class="mb-3">
                <label for="tanggal_selesai" class="form-label">Tanggal Selesai Pelaksanaan</label>
                <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai"
                    value="{{ old('tanggal_selesai', $lowongan->tanggal_selesai->format('Y-m-d')) }}" required>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- PENAMBAHAN FIELD UPAH DI SINI --}}
    {{-- ========================================================== --}}
    <div class="col-md-4">
        <div class="mb-3">
            <label for="upah" class="form-label">Uang Saku / Upah (per bulan) <span
                    class="text-danger">*</span></label>
            <input type="number" class="form-control" id="upah" name="upah"
                value="{{ old('upah', $lowongan->upah) }}" required min="0">
            <small class="form-text text-muted">Isi 0 jika tidak menyediakan uang saku.</small>
        </div>
    </div>
    {{-- ========================================================== --}}

    <div class="col-md-4">
        @php $selectedTipeKerja = $lowongan->tipeKerja->pluck('tipe_kerja_id')->toArray(); @endphp
        <div class="form-group mb-3"><label class="form-label">Tipe Kerja <span class="text-danger">*</span></label>
            @foreach ($tipeKerjaList as $tipe)
                <div class="form-check"><input class="form-check-input" type="checkbox" name="tipe_kerja[]"
                        value="{{ $tipe->tipe_kerja_id }}" id="edit_tipe_kerja_{{ $tipe->tipe_kerja_id }}"
                        {{ in_array($tipe->tipe_kerja_id, old('tipe_kerja', $selectedTipeKerja)) ? 'checked' : '' }}><label
                        class="form-check-label"
                        for="edit_tipe_kerja_{{ $tipe->tipe_kerja_id }}">{{ $tipe->nama_tipe_kerja }}</label></div>
            @endforeach
        </div>
    </div>
    <div class="col-md-4">
        @php $selectedFasilitas = $lowongan->fasilitas->pluck('fasilitas_id')->toArray(); @endphp
        <div class="form-group mb-3"><label class="form-label">Fasilitas</label>
            @foreach ($fasilitasList as $fasilitas)
                <div class="form-check"><input class="form-check-input" type="checkbox" name="fasilitas[]"
                        value="{{ $fasilitas->fasilitas_id }}" id="edit_fasilitas_{{ $fasilitas->fasilitas_id }}"
                        {{ in_array($fasilitas->fasilitas_id, old('fasilitas', $selectedFasilitas)) ? 'checked' : '' }}><label
                        class="form-check-label"
                        for="edit_fasilitas_{{ $fasilitas->fasilitas_id }}">{{ $fasilitas->nama_fasilitas }}</label>
                </div>
            @endforeach
        </div>
    </div>
    </div>
    {{-- ========================================================== --}}
    {{-- BAGIAN BARU UNTUK SKILL --}}
    {{-- ========================================================== --}}
    <div class="form-group mb-3">
        <label class="form-label">Skill yang Dibutuhkan</label>
        <div id="skills-container">
            @php $currentSkills = $lowongan->lowonganSkill; @endphp
            @if ($currentSkills->isNotEmpty())
                @foreach ($currentSkills as $index => $item)
                    <div class="row skill-row mb-2">
                        <div class="col-md-6">
                            <select name="skills[]" class="form-select" required>
                                <option value="" disabled>-- Pilih Skill --</option>
                                @foreach ($detailSkills as $skill)
                                    <option value="{{ $skill->skill_id }}"
                                        {{ $item->skill_id == $skill->skill_id ? 'selected' : '' }}>
                                        {{ $skill->skill_nama }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select name="levels[]" class="form-select" required>
                                <option value="Beginner"
                                    {{ $item->level_kompetensi == 'Beginner' ? 'selected' : '' }}>
                                    Beginner</option>
                                <option value="Intermediate"
                                    {{ $item->level_kompetensi == 'Intermediate' ? 'selected' : '' }}>Intermediate
                                </option>
                                <option value="Expert" {{ $item->level_kompetensi == 'Expert' ? 'selected' : '' }}>
                                    Expert</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-skill-btn w-100">Hapus</button>
                        </div>
                    </div>
                @endforeach
            @else
                {{-- Tambahkan satu baris kosong jika belum ada skill sama sekali --}}
                <div class="row skill-row mb-2">
                    <div class="col-md-6"><select name="skills[]" class="form-select" required>
                            <option value="" selected disabled>-- Pilih Skill --</option>
                            @foreach ($detailSkills as $skill)
                                <option value="{{ $skill->skill_id }}">{{ $skill->skill_nama }}</option>
                            @endforeach
                        </select></div>
                    <div class="col-md-4"><select name="levels[]" class="form-select" required>
                            <option value="Beginner">Beginner</option>
                            <option value="Intermediate">Intermediate</option>
                            <option value="Expert">Expert</option>
                        </select></div>
                    <div class="col-md-2"><button type="button"
                            class="btn btn-danger remove-skill-btn w-100">Hapus</button></div>
                </div>
            @endif
        </div>
        <button type="button" id="add-skill-btn" class="btn btn-outline-primary btn-sm mt-2">
            <i class="fas fa-plus"></i> Tambah Skill
        </button>
    </div>

    {{-- Template untuk baris skill baru (disembunyikan) --}}
    <template id="skill-row-template">
        <div class="row skill-row mb-2">
            <div class="col-md-6">
                <select name="skills[]" class="form-select" required>
                    <option value="" selected disabled>-- Pilih Skill --</option>
                    @foreach ($detailSkills as $skill)
                        <option value="{{ $skill->skill_id }}">{{ $skill->skill_nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <select name="levels[]" class="form-select" required>
                    <option value="Beginner">Beginner</option>
                    <option value="Intermediate">Intermediate</option>
                    <option value="Expert">Expert</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger remove-skill-btn w-100">Hapus</button>
            </div>
        </div>
    </template>
    {{-- ========================================================== --}}
    {{-- AKHIR BAGIAN SKILL --}}
    {{-- ========================================================== --}}

    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    </div>
</form>
