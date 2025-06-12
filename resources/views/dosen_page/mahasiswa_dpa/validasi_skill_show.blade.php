@extends('layouts.template')

@section('title', 'Validasi Skill - ' . $mahasiswa->nama_lengkap)

@push('css')
    <style>
        :root {
            --app-border-color: #e0e0e0;
            --app-text-muted: #6c757d;
            --app-text-dark: #212529;
            --app-bg-light-section: #f9f9f9;
            --app-success-color: #198754;
            --app-primary-text-color: #0d6efd;
            --app-info-badge-bg: #0dcaf0;
            --app-pending-badge-bg: #ffc107;
            --app-pending-badge-text: #212529;
            --app-invalid-badge-bg: #dc3545;
        }

        .main-card-validation {
            border: 1px solid var(--app-border-color);
            box-shadow: 0 .125rem .25rem rgba(0, 0, 0, .05);
        }

        .profile-header-validation {
            background-color: #fff;
            padding: 1.5rem;
            border-bottom: 1px solid var(--app-border-color);
            margin-bottom: 1.5rem;
        }

        .profile-avatar-validation {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 2px solid var(--app-border-color);
        }

        .section-title-validation {
            margin-top: 1.5rem;
            margin-bottom: 1rem;
            color: var(--app-text-dark);
            font-weight: 500;
            font-size: 1.15rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--app-success-color);
        }

        .section-title-validation:first-of-type {
            margin-top: 0;
        }

        .skill-validation-item-card {
            background-color: #fff;
            border: 1px solid var(--app-border-color);
            margin-bottom: 1rem;
            border-radius: .375rem;
            box-shadow: none;
            transition: box-shadow 0.3s ease-in-out;
        }

        .skill-card-highlight-success {
            box-shadow: 0 0 0 2px rgba(25, 135, 84, 0.5); /* Shadow hijau */
        }

        .skill-card-highlight-error {
             box-shadow: 0 0 0 2px rgba(220, 53, 69, 0.5); /* Shadow merah */
        }

        .skill-validation-item-card .card-body {
            padding: 1rem 1.25rem;
        }

        .portfolio-list-clean {
            list-style: none;
            padding-left: 0;
            margin-top: 0.75rem;
        }

        .portfolio-list-clean li {
            background-color: var(--app-bg-light-section);
            border: 1px solid #e7e7e7;
            padding: 0.5rem 0.75rem;
            margin-bottom: 0.5rem;
            border-radius: .25rem;
            font-size: 0.875rem;
        }

        .portfolio-title-clean {
            font-weight: 500;
        }

        .portfolio-type-clean {
            font-size: 0.8em;
            color: var(--app-text-muted);
        }

        .portfolio-link-clean a {
            text-decoration: none;
            color: var(--app-primary-text-color);
        }

        .portfolio-link-clean a:hover {
            text-decoration: underline;
        }

        .badge.custom-badge {
            font-size: 0.8rem;
            padding: .4em .65em;
            font-weight: 500;
            vertical-align: middle;
        }

        .badge.bg-level {
             background-color: var(--app-info-badge-bg) !important;
             color: var(--app-text-dark) !important;
        }

        .badge.bg-valid {
            background-color: var(--app-success-color) !important;
        }

        .badge.bg-pending {
            background-color: var(--app-pending-badge-bg) !important;
            color: var(--app-pending-badge-text) !important;
        }

        .badge.bg-invalid {
            background-color: var(--app-invalid-badge-bg) !important;
        }

        .validation-form-column {
            background-color: var(--app-bg-light-section);
            padding: 1.25rem;
            border-radius: .25rem;
        }

        .validation-form-column .form-label {
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .validation-form-column .form-select,
        .validation-form-column .form-control {
            font-size: 0.875rem;
        }

        .ajax-error-message {
            color: #dc3545;
            font-size: .875em;
            margin-top: .25rem;
        }

    </style>
@endpush

@section('content')
    <div class="container-fluid py-3">
        <div class="row mb-3">
            <div class="col-12">
                @php
                    $backUrl = route('home');
                    $backText = 'Kembali ke Dashboard';
                    if (request('from') === 'validasi') {
                        $backUrl = route('dosen.mahasiswa-dpa.index');
                        $backText = 'Kembali ke Daftar Mahasiswa DPA';
                    }
                @endphp
                <a href="{{ $backUrl }}" class="btn btn-sm btn-outline-dark">
                    <i class="fas fa-arrow-left me-1"></i> {{ $backText }}
                </a>
            </div>
        </div>

        <div class="card main-card-validation">
            <div class="card-body p-lg-4">
                {{-- Header Informasi Mahasiswa --}}
                <div class="profile-header-validation rounded mb-4">
                    <div class="row align-items-center">
                        <div class="col-md-auto text-center mb-3 mb-md-0">
                            <img src="{{ optional($mahasiswa)->foto ? asset('storage/mahasiswa/foto/' . $mahasiswa->foto) : asset('assets/default-profile.png') }}"
                                alt="Foto Mahasiswa" class="profile-avatar-validation rounded-circle">
                        </div>
                        <div class="col-md">
                            <h4 class="mb-1">{{ $mahasiswa->nama_lengkap ?? 'Nama Mahasiswa' }}</h4>
                            <p class="text-muted mb-1"><i class="fas fa-id-card fa-fw me-1"></i>NIM:
                                {{ $mahasiswa->nim ?? '-' }}</p>
                            <p class="text-muted mb-0"><i class="fas fa-graduation-cap fa-fw me-1"></i>Prodi:
                                {{ optional($mahasiswa->prodi)->nama_prodi ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                <h4 class="section-title-validation">Daftar Keahlian Mahasiswa</h4>
                @if ($skillsForValidation->isEmpty())
                    <div class="alert alert-secondary text-center">
                        <i class="fas fa-info-circle me-1"></i> Mahasiswa ini belum menambahkan skill apapun untuk divalidasi.
                    </div>
                @else
                    @foreach ($skillsForValidation as $index => $mskill)
                        <div class="card skill-validation-item-card" id="skill-card-{{ $mskill->mahasiswa_skill_id }}">
                            <div class="card-body">
                                <form action="{{ route('dosen.mahasiswa-dpa.skill.update_validasi', $mskill->mahasiswa_skill_id) }}" method="POST" class="validation-form">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-7">
                                            <div class="d-flex justify-content-between align-items-center mb-2">
                                                <h5 class="mb-0">{{ $index + 1 }}.
                                                    {{ optional($mskill->detailSkill)->skill_nama ?? 'Tidak diketahui' }}
                                                </h5>
                                                <div>
                                                    <span class="badge custom-badge bg-level me-1"
                                                          id="level-badge-{{ $mskill->mahasiswa_skill_id }}"
                                                          title="Level divalidasi DPA">{{ $mskill->level_kompetensi }}</span>
                                                </div>
                                            </div>
                                            <p class="mb-1 small text-muted">
                                                Status Verifikasi Saat Ini:
                                                @php
                                                    $statusClass = 'bg-pending';
                                                    if ($mskill->status_verifikasi === 'Valid') $statusClass = 'bg-valid';
                                                    if ($mskill->status_verifikasi === 'Invalid') $statusClass = 'bg-invalid';
                                                @endphp
                                                <span class="badge custom-badge {{ $statusClass }}" id="status-badge-{{ $mskill->mahasiswa_skill_id }}">
                                                    {{ $mskill->status_verifikasi }}
                                                </span>
                                            </p>

                                            @if ($mskill->linkedPortofolios->isNotEmpty())
                                                <p class="mt-2 mb-1 text-xs text-uppercase fw-bold text-muted">Bukti Portofolio Terkait:</p>
                                                <ul class="portfolio-list-clean">
                                                    @foreach ($mskill->linkedPortofolios as $portfolioItem)
                                                        <li>
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <span class="portfolio-title-clean">{{ $portfolioItem->judul_portofolio }}</span>
                                                                    <span class="portfolio-type-clean"> ({{ ucfirst($portfolioItem->tipe_portofolio) }})</span>
                                                                </div>
                                                                <span class="portfolio-link-clean">
                                                                    @if (in_array($portfolioItem->tipe_portofolio, ['url', 'video']))
                                                                        <a href="{{ $portfolioItem->lokasi_file_atau_url }}" target="_blank" class="btn btn-sm btn-outline-dark py-0 px-1" title="Lihat Link"><i class="fas fa-external-link-alt"></i></a>
                                                                    @elseif(in_array($portfolioItem->tipe_portofolio, ['file', 'gambar']))
                                                                        <a href="{{ asset('storage/' . $portfolioItem->lokasi_file_atau_url) }}" target="_blank" class="btn btn-sm btn-outline-dark py-0 px-1" title="Lihat File"><i class="fas fa-download"></i></a>
                                                                    @endif
                                                                </span>
                                                            </div>
                                                            @if ($portfolioItem->pivot->deskripsi_penggunaan_skill)
                                                                <p class="small text-muted mt-1 mb-0 fst-italic">"{{ $portfolioItem->pivot->deskripsi_penggunaan_skill }}"</p>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="small text-muted fst-italic mt-2 mb-0">Tidak ada portofolio yang dikaitkan.</p>
                                            @endif
                                        </div>

                                        <div class="col-md-5 border-start-md ps-md-3 mt-3 mt-md-0 validation-form-column">
                                            <p class="fw-bold mb-2 text-center">Form Validasi DPA</p>
                                            <div class="mb-3">
                                                <label for="level_kompetensi_{{ $mskill->mahasiswa_skill_id }}" class="form-label">Validasi Level Kompetensi <span class="text-danger">*</span></label>
                                                <select name="level_kompetensi" id="level_kompetensi_{{ $mskill->mahasiswa_skill_id }}" class="form-select" required>
                                                    <option value="Beginner" {{ old('level_kompetensi', $mskill->level_kompetensi) == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                                    <option value="Intermediate" {{ old('level_kompetensi', $mskill->level_kompetensi) == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                    <option value="Expert" {{ old('level_kompetensi', $mskill->level_kompetensi) == 'Expert' ? 'selected' : '' }}>Expert</option>
                                                </select>
                                                <div class="ajax-error-message" data-field="level_kompetensi"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="status_verifikasi_{{ $mskill->mahasiswa_skill_id }}" class="form-label">Ubah Status Verifikasi <span class="text-danger">*</span></label>
                                                <select name="status_verifikasi" id="status_verifikasi_{{ $mskill->mahasiswa_skill_id }}" class="form-select" required>
                                                    <option value="Pending" {{ old('status_verifikasi', $mskill->status_verifikasi) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="Valid" {{ old('status_verifikasi', $mskill->status_verifikasi) == 'Valid' ? 'selected' : '' }}>Valid</option>
                                                    <option value="Invalid" {{ old('status_verifikasi', $mskill->status_verifikasi) == 'Invalid' ? 'selected' : '' }}>Invalid</option>
                                                </select>
                                                 <div class="ajax-error-message" data-field="status_verifikasi"></div>
                                            </div>
                                            <button type="submit" class="btn btn-sm btn-success w-100"><i class="fas fa-save me-1"></i> Simpan Validasi</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Karena template sudah memiliki CSRF token, kita bisa langsung mengambilnya.
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.querySelectorAll('.validation-form').forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const currentForm = this;
                const actionUrl = currentForm.getAttribute('action');
                const formData = new FormData(currentForm);
                const submitButton = currentForm.querySelector('button[type="submit"]');
                const originalButtonHtml = submitButton.innerHTML;

                submitButton.disabled = true;
                submitButton.innerHTML = `<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Menyimpan...`;
                currentForm.querySelectorAll('.ajax-error-message').forEach(el => el.textContent = '');

                fetch(actionUrl, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message || 'Validasi skill berhasil diperbarui.',
                            timer: 2000,
                            showConfirmButton: false
                        });
                        updateSkillCardUI(currentForm, data.newData);

                    }
                    // Note: penanganan error validasi (else) akan ditangkap oleh .catch jika response.ok adalah false
                })
                .catch(errorData => {
                    // Blok ini akan menangani error jaringan dan error validasi dari server (status 422)
                    let errorMessage = 'Terjadi kesalahan teknis. Silakan coba lagi.';

                    if (errorData && errorData.errors) {
                         // Ini adalah error validasi
                        errorMessage = errorData.message || 'Data yang diberikan tidak valid.';
                        displayValidationErrors(currentForm, errorData.errors);
                    } else if (errorData && errorData.message) {
                        // Error server lain dengan pesan JSON
                        errorMessage = errorData.message;
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: errorMessage,
                    });
                     console.error('Fetch Error:', errorData);
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalButtonHtml;
                });
            });
        });

        function updateSkillCardUI(form, newData) {
            const skillId = form.closest('.skill-validation-item-card').id.split('-').pop();
            const levelBadge = document.getElementById(`level-badge-${skillId}`);
            if (levelBadge) {
                levelBadge.textContent = newData.level_kompetensi;
            }
            const statusBadge = document.getElementById(`status-badge-${skillId}`);
            if (statusBadge) {
                statusBadge.textContent = newData.status_verifikasi;
                statusBadge.className = 'badge custom-badge'; // Reset class
                if (newData.status_verifikasi === 'Valid') {
                    statusBadge.classList.add('bg-valid');
                } else if (newData.status_verifikasi === 'Invalid') {
                    statusBadge.classList.add('bg-invalid');
                } else {
                    statusBadge.classList.add('bg-pending');
                }
            }
            const card = document.getElementById(`skill-card-${skillId}`);
            if(card) {
                card.classList.add('skill-card-highlight-success');
                setTimeout(() => {
                    card.classList.remove('skill-card-highlight-success');
                }, 2500);
            }
        }

        function displayValidationErrors(form, errors) {
            const card = form.closest('.skill-validation-item-card');
            if(card) {
                card.classList.add('skill-card-highlight-error');
                 setTimeout(() => {
                    card.classList.remove('skill-card-highlight-error');
                }, 2500);
            }

            for (const field in errors) {
                const errorContainer = form.querySelector(`.ajax-error-message[data-field="${field}"]`);
                if (errorContainer) {
                    errorContainer.textContent = errors[field][0];
                }
            }
        }
    });
</script>
@endpush
