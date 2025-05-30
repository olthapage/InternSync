@extends('layouts.template')

@section('title', 'Keahlian & Portofolio Saya')

@push('css')
    <style>
        .portfolio-item {
            margin-bottom: 1.5rem;
        }

        .portfolio-item img,
        .portfolio-item iframe {
            max-width: 100%;
            border-radius: 0.25rem;
        }

        .skill-tag {
            margin-right: 5px;
            margin-bottom: 5px;
        }

        .form-section {
            margin-bottom: 2.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid #dee2e6;
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .small-label {
            font-size: 0.8rem;
            color: #6c757d;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col-lg-12">
                <h3 class="text-dark-blue">Keahlian & Portofolio Saya</h3>
                <p class="text-muted">Kelola daftar keahlian yang Anda kuasai dan pamerkan karya terbaik Anda melalui
                    portofolio.</p>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- BAGIAN KELOLA SKILL --}}
        <div class="card shadow-sm mb-4 form-section">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-cogs me-2"></i>Keahlian Saya</h5>
            </div>
            <div class="card-body">
                @if ($errors->storeSkillErrors->any())
                    <div class="alert alert-danger">
                        <p class="font-weight-bold">Gagal menambahkan skill:</p>
                        <ul class="mb-0 ms-3">
                            @foreach ($errors->storeSkillErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('mahasiswa.portofolio.skill.store') }}" method="POST" class="mb-4">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label for="skill_id" class="form-label">Pilih Skill <span class="text-danger">*</span></label>
                            <select name="skill_id" id="skill_id"
                                class="form-select @error('skill_id', 'storeSkillErrors') is-invalid @enderror" required>
                                <option value="">-- Pilih Keahlian --</option>
                                @foreach ($availableSkills as $skill)
                                    <option value="{{ $skill->skill_id }}"
                                        {{ old('skill_id') == $skill->skill_id ? 'selected' : '' }}>{{ $skill->skill_nama }}
                                    </option>
                                @endforeach
                            </select>
                            @error('skill_id', 'storeSkillErrors')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-5">
                            <label for="level_kompetensi" class="form-label">Level Kompetensi <span
                                    class="text-danger">*</span></label>
                            <select name="level_kompetensi" id="level_kompetensi"
                                class="form-select @error('level_kompetensi', 'storeSkillErrors') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Level --</option>
                                <option value="Beginner" {{ old('level_kompetensi') == 'Beginner' ? 'selected' : '' }}>
                                    Beginner</option>
                                <option value="Intermediate"
                                    {{ old('level_kompetensi') == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="Expert" {{ old('level_kompetensi') == 'Expert' ? 'selected' : '' }}>Expert
                                </option>
                            </select>
                            @error('level_kompetensi', 'storeSkillErrors')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-2 d-flex flex-column justify-content-end">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah</button>
                        </div>

                    </div>
                </form>

                <h6>Daftar Skill Anda:</h6>
                @if ($claimedSkills->isEmpty())
                    <p class="text-muted">Anda belum menambahkan skill apapun.</p>
                @else
                    <ul class="list-group">
                        @foreach ($claimedSkills as $cs)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $cs->detailSkill->skill_nama ?? 'Skill tidak diketahui' }}</strong> -
                                    <span class="badge bg-info">{{ $cs->level_kompetensi }}</span>
                                    {{-- MODIFIKASI UNTUK MENAMPILKAN STATUS VERIFIKASI --}}
                                    @php
                                        $statusClass = 'bg-secondary'; // Default untuk Pending
                                        $statusText = $cs->status_verifikasi; // Ambil dari model
                                        $titleText = "Menunggu Verifikasi"; // Default title

                                        if ($cs->status_verifikasi === 'Valid') {
                                            $statusClass = 'bg-success';
                                            $titleText = "Terverifikasi";
                                        } elseif ($cs->status_verifikasi === 'Invalid') {
                                            $statusClass = 'bg-danger';
                                            $titleText = "Verifikasi Ditolak";
                                        }
                                    @endphp
                                    <span class="badge {{ $statusClass }} status-badge" title="{{ $titleText }}">
                                        {{ $statusText }}
                                    </span>

                                    @if ($cs->status_verifikasi === 'Valid')
                                        <i class="fas fa-check-circle text-success ms-1 verification-icon" title="Skill terverifikasi dan siap digunakan untuk melamar"></i>
                                    @elseif ($cs->status_verifikasi === 'Invalid')
                                        <i class="fas fa-times-circle text-danger ms-1 verification-icon" title="Verifikasi skill ditolak"></i>
                                    @else {{-- Pending --}}
                                        <i class="fas fa-hourglass-half text-warning ms-1 verification-icon" title="Skill Anda sedang menunggu proses verifikasi"></i>
                                    @endif
                                    {{-- AKHIR MODIFIKASI STATUS VERIFIKASI --}}
                                    {{-- Jika ingin menampilkan bobot: (Bobot: {{ $cs->bobot }}) --}}
                                    @if ($cs->linkedPortofolios->isNotEmpty())
                                        <br>
                                        <small class="text-muted">Terhubung ke {{ $cs->linkedPortofolios->count() }}
                                            portofolio</small>
                                    @endif
                                </div>
                                <div>
                                    {{-- <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Edit Skill"><i class="fas fa-edit"></i></button> --}}
                                    <form
                                        action="{{ route('mahasiswa.portofolio.skill.destroy', $cs->mahasiswa_skill_id) }}"
                                        method="POST" class="d-inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus skill ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus Skill"><i
                                                class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

        {{-- BAGIAN KELOLA PORTOFOLIO --}}
        <div class="card shadow-sm form-section">
            <div class="card-header border-bottom">
                <h5 class="mb-0"><i class="fas fa-briefcase me-2"></i>Portofolio Saya</h5>
            </div>
            <div class="card-body">
                @if ($errors->storePortfolioErrors->any())
                    <div class="alert alert-danger">
                        <p class="font-weight-bold">Gagal menambahkan portofolio:</p>
                        <ul class="mb-0 ms-3">
                            @foreach ($errors->storePortfolioErrors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('mahasiswa.portofolio.item.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="judul_portofolio" class="form-label">Judul Portofolio/Proyek <span
                                class="text-danger">*</span></label>
                        <input type="text" name="judul_portofolio" id="judul_portofolio"
                            class="form-control @error('judul_portofolio', 'storePortfolioErrors') is-invalid @enderror"
                            value="{{ old('judul_portofolio') }}" required>
                        @error('judul_portofolio', 'storePortfolioErrors')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="deskripsi_portofolio" class="form-label">Deskripsi</label>
                        <textarea name="deskripsi_portofolio" id="deskripsi_portofolio"
                            class="form-control @error('deskripsi_portofolio', 'storePortfolioErrors') is-invalid @enderror" rows="3">{{ old('deskripsi_portofolio') }}</textarea>
                        @error('deskripsi_portofolio', 'storePortfolioErrors')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tipe_portofolio" class="form-label">Tipe Portofolio <span
                                    class="text-danger">*</span></label>
                            <select name="tipe_portofolio" id="tipe_portofolio"
                                class="form-select @error('tipe_portofolio', 'storePortfolioErrors') is-invalid @enderror"
                                required>
                                <option value="">-- Pilih Tipe --</option>
                                <option value="file" {{ old('tipe_portofolio') == 'file' ? 'selected' : '' }}>File (PDF,
                                    Doc, ZIP)</option>
                                <option value="gambar" {{ old('tipe_portofolio') == 'gambar' ? 'selected' : '' }}>Gambar
                                    (JPG, PNG)</option>
                                <option value="url" {{ old('tipe_portofolio') == 'url' ? 'selected' : '' }}>Link URL
                                    (misal: GitHub, Website Proyek)</option>
                                <option value="video" {{ old('tipe_portofolio') == 'video' ? 'selected' : '' }}>Link URL
                                    Video (misal: YouTube, Vimeo)</option>
                            </select>
                            @error('tipe_portofolio', 'storePortfolioErrors')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3" id="lokasi_file_upload_group" style="display: none;">
                            <label for="lokasi_file_upload" class="form-label">Upload File/Gambar</label>
                            <input type="file" name="lokasi_file_upload" id="lokasi_file_upload"
                                class="form-control @error('lokasi_file_upload', 'storePortfolioErrors') is-invalid @enderror">
                            @error('lokasi_file_upload', 'storePortfolioErrors')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3" id="lokasi_file_atau_url_input_group" style="display: none;">
                            <label for="lokasi_file_atau_url_input" class="form-label">Masukkan URL</label>
                            <input type="url" name="lokasi_file_atau_url_input" id="lokasi_file_atau_url_input"
                                class="form-control @error('lokasi_file_atau_url_input', 'storePortfolioErrors') is-invalid @enderror"
                                placeholder="https://contoh.com/proyek-saya"
                                value="{{ old('lokasi_file_atau_url_input') }}">
                            @error('lokasi_file_atau_url_input', 'storePortfolioErrors')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_pengerjaan_mulai" class="form-label">Tanggal Mulai Pengerjaan</label>
                            <input type="date" name="tanggal_pengerjaan_mulai" id="tanggal_pengerjaan_mulai"
                                class="form-control @error('tanggal_pengerjaan_mulai', 'storePortfolioErrors') is-invalid @enderror"
                                value="{{ old('tanggal_pengerjaan_mulai') }}">
                            @error('tanggal_pengerjaan_mulai', 'storePortfolioErrors')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="tanggal_pengerjaan_selesai" class="form-label">Tanggal Selesai Pengerjaan</label>
                            <input type="date" name="tanggal_pengerjaan_selesai" id="tanggal_pengerjaan_selesai"
                                class="form-control @error('tanggal_pengerjaan_selesai', 'storePortfolioErrors') is-invalid @enderror"
                                value="{{ old('tanggal_pengerjaan_selesai') }}">
                            @error('tanggal_pengerjaan_selesai', 'storePortfolioErrors')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Hubungkan dengan Skill yang Dikuasai (Opsional):</label>
                        <div id="linked-skills-container">
                            {{-- Kontainer untuk skill yang dipilih dan deskripsinya --}}
                            {{-- Contoh jika ada old input (lebih kompleks untuk dirender ulang, bisa di-handle dengan JS lanjutan) --}}
                        </div>
                        <button type="button" id="add-skill-to-portfolio-btn"
                            class="btn btn-sm btn-outline-info mt-2"><i class="fas fa-link me-1"></i> Kaitkan
                            Skill</button>
                    </div>


                    <button type="submit" class="btn btn-primary"><i class="fas fa-plus me-1"></i> Tambah
                        Portofolio</button>
                </form>

                <hr class="my-4">
                <h6>Daftar Portofolio Anda:</h6>
                @if ($portfolioItems->isEmpty())
                    <p class="text-muted">Anda belum menambahkan item portofolio apapun.</p>
                @else
                    <div class="row">
                        @foreach ($portfolioItems as $item)
                            <div class="col-md-6 col-lg-4 portfolio-item">
                                <div class="card h-100">
                                    {{-- Tampilkan preview jika gambar --}}
                                    @if ($item->tipe_portofolio == 'gambar' && $item->lokasi_file_atau_url)
                                        <img src="{{ asset('storage/' . $item->lokasi_file_atau_url) }}"
                                            class="card-img-top" alt="{{ $item->judul_portofolio }}"
                                            style="max-height: 200px; object-fit: cover;">
                                    @elseif($item->tipe_portofolio == 'video' && Str::contains($item->lokasi_file_atau_url, 'youtube.com/watch?v='))
                                        @php
                                            parse_str(
                                                parse_url($item->lokasi_file_atau_url, PHP_URL_QUERY),
                                                $youtube_vars,
                                            );
                                            $youtube_id = $youtube_vars['v'] ?? null;
                                        @endphp
                                        @if ($youtube_id)
                                            <div class="embed-responsive embed-responsive-16by9">
                                                <iframe class="embed-responsive-item card-img-top"
                                                    src="https://www.youtube.com/embed/{{ $youtube_id }}"
                                                    allowfullscreen style="max-height: 200px;"></iframe>
                                            </div>
                                        @endif
                                    @elseif(in_array(pathinfo($item->lokasi_file_atau_url, PATHINFO_EXTENSION), ['pdf']))
                                        <div class="text-center p-3 border-bottom">
                                            <i class="fas fa-file-pdf fa-3x text-danger"></i>
                                        </div>
                                    @else
                                        <div class="text-center p-3 border-bottom">
                                            <i class="fas fa-link fa-3x text-secondary"></i>
                                        </div>
                                    @endif

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ $item->judul_portofolio }}</h5>
                                        <p class="card-text small text-muted flex-grow-1">
                                            {{ Str::limit($item->deskripsi_portofolio, 100) }}</p>
                                        <p class="small mb-1">
                                            <strong>Tipe:</strong> <span
                                                class="badge bg-secondary">{{ ucfirst($item->tipe_portofolio) }}</span>
                                        </p>
                                        @if ($item->tanggal_pengerjaan_selesai)
                                            <p class="small mb-1">
                                                <strong>Selesai:</strong>
                                                {{ Carbon\Carbon::parse($item->tanggal_pengerjaan_selesai)->isoFormat('D MMM YYYY') }}
                                            </p>
                                        @endif

                                        @if ($item->linkedMahasiswaSkills->isNotEmpty())
                                            <div class="mt-2">
                                                <p class="small mb-1"><strong>Skill Terkait:</strong></p>
                                                @foreach ($item->linkedMahasiswaSkills as $linkedSkill)
                                                    <span class="badge bg-primary skill-tag">
                                                        {{ $linkedSkill->detailSkill->skill_nama ?? 'N/A' }}
                                                        ({{ $linkedSkill->level_kompetensi }})
                                                        @if ($linkedSkill->pivot->deskripsi_penggunaan_skill)
                                                            <i class="fas fa-info-circle ms-1"
                                                                title="Deskripsi Penggunaan: {{ $linkedSkill->pivot->deskripsi_penggunaan_skill }}"></i>
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                    <div class="card-footer bg-transparent border-top-0 text-end">
                                        @if ($item->tipe_portofolio == 'url' || $item->tipe_portofolio == 'video')
                                            <a href="{{ $item->lokasi_file_atau_url }}" target="_blank"
                                                class="btn btn-sm btn-outline-primary me-1" title="Kunjungi Link"><i
                                                    class="fas fa-external-link-alt"></i></a>
                                        @elseif($item->tipe_portofolio == 'file' || $item->tipe_portofolio == 'gambar')
                                            <a href="{{ asset('storage/' . $item->lokasi_file_atau_url) }}"
                                                target="_blank" class="btn btn-sm btn-outline-primary me-1"
                                                title="Lihat File"><i class="fas fa-download"></i></a>
                                        @endif
                                        {{-- <button type="button" class="btn btn-sm btn-outline-secondary me-1" title="Edit Portofolio"><i class="fas fa-edit"></i></button> --}}
                                        <form
                                            action="{{ route('mahasiswa.portofolio.item.destroy', $item->portofolio_id) }}"
                                            method="POST" class="d-inline"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus item portofolio ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger"
                                                title="Hapus Portofolio"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tipePortofolioSelect = document.getElementById('tipe_portofolio');
            const fileUploadGroup = document.getElementById('lokasi_file_upload_group');
            const urlInputGroup = document.getElementById('lokasi_file_atau_url_input_group');
            const fileUploadInput = document.getElementById('lokasi_file_upload');
            const urlInput = document.getElementById('lokasi_file_atau_url_input');

            function togglePortfolioLocationInput() {
                const selectedType = tipePortofolioSelect.value;
                fileUploadGroup.style.display = 'none';
                urlInputGroup.style.display = 'none';
                fileUploadInput.removeAttribute('required');
                urlInput.removeAttribute('required');


                if (selectedType === 'file' || selectedType === 'gambar') {
                    fileUploadGroup.style.display = 'block';
                    fileUploadInput.setAttribute('required', 'required');
                } else if (selectedType === 'url' || selectedType === 'video') {
                    urlInputGroup.style.display = 'block';
                    urlInput.setAttribute('required', 'required');
                }
            }

            if (tipePortofolioSelect) {
                tipePortofolioSelect.addEventListener('change', togglePortfolioLocationInput);
                // Initial check on page load (for old input)
                togglePortfolioLocationInput();
            }

            // --- Logic untuk Add Skill to Portfolio (Dynamic) ---
            const addSkillToPortfolioBtn = document.getElementById('add-skill-to-portfolio-btn');
            const linkedSkillsContainer = document.getElementById('linked-skills-container');
            let skillLinkCounter = 0;

            // Ambil data skill yang sudah diklaim mahasiswa (untuk dropdown)
            // Ini harus di-pass dari controller atau dibuat sebagai string JS seperti skillOptionsHtml di form lowongan
            const claimedSkillOptions = `
        <option value="">-- Pilih Skill yang Dikuasai --</option>
        @foreach ($claimedSkills as $cs)
            <option value="{{ $cs->mahasiswa_skill_id }}">{{ $cs->detailSkill->skill_nama ?? 'N/A' }} ({{ $cs->level_kompetensi }})</option>
        @endforeach
    `;

            if (addSkillToPortfolioBtn) {
                addSkillToPortfolioBtn.addEventListener('click', function() {
                    skillLinkCounter++;
                    const newSkillLinkRow = document.createElement('div');
                    newSkillLinkRow.classList.add('row', 'g-2', 'mb-2', 'align-items-center',
                        'skill-link-item');
                    newSkillLinkRow.innerHTML = `
                <div class="col-md-5">
                    <select name="linked_mahasiswa_skills[]" class="form-select form-select-sm" required>
                        ${claimedSkillOptions}
                    </select>
                </div>
                <div class="col-md-6">
                    <input type="text" name="deskripsi_penggunaan_skill_input[${skillLinkCounter}]" class="form-control form-control-sm" placeholder="Deskripsi penggunaan skill di proyek ini (opsional)">
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-skill-link-btn w-100"><i class="fas fa-times"></i></button>
                </div>
            `;
                    // Perbaikan: Ganti name deskripsi menjadi array yang bisa di-map dengan ID skill di backend
                    // atau ubah cara controller mengambilnya. Untuk sekarang, kita gunakan array biasa dan map di controller.
                    // Kita perlu cara untuk menghubungkan deskripsi dengan skill_id yang benar.
                    // Salah satu cara: ubah nama input deskripsi menjadi: name="deskripsi_penggunaan_skill[\${selected_skill_id_here}]"
                    // Ini membutuhkan JS lebih lanjut untuk mendapatkan ID skill yang dipilih.
                    // Untuk sementara, di controller, kita akan asumsikan urutan `deskripsi_penggunaan_skill_input` cocok dengan `linked_mahasiswa_skills`
                    // Namun, lebih baik menggunakan ID skill sebagai kunci array deskripsi.

                    // Modifikasi untuk menggunakan ID skill sebagai kunci pada deskripsi:
                    const skillSelect = newSkillLinkRow.querySelector(
                        'select[name="linked_mahasiswa_skills[]"]');
                    const deskripsiInput = newSkillLinkRow.querySelector('input[type="text"]');

                    skillSelect.addEventListener('change', function() {
                        if (this.value) {
                            deskripsiInput.setAttribute('name', 'deskripsi_penggunaan_skill[' + this
                                .value + ']');
                        } else {
                            deskripsiInput.setAttribute('name',
                                'deskripsi_penggunaan_skill_temp[]'
                                ); // Temporary name if no skill selected
                        }
                    });
                    // Set initial name for description input to be unique until a skill is selected
                    deskripsiInput.setAttribute('name', 'deskripsi_penggunaan_skill_temp[' +
                        skillLinkCounter + ']');


                    linkedSkillsContainer.appendChild(newSkillLinkRow);
                });
            }

            if (linkedSkillsContainer) {
                linkedSkillsContainer.addEventListener('click', function(e) {
                    if (e.target && (e.target.classList.contains('remove-skill-link-btn') || e.target
                            .closest('.remove-skill-link-btn'))) {
                        e.target.closest('.skill-link-item').remove();
                    }
                });
            }

        });
    </script>
@endpush
