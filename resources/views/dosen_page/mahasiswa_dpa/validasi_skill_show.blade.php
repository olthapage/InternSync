@extends('layouts.template')

@section('title', 'Validasi Skill Mahasiswa - ' . $mahasiswa->nama_lengkap)

@push('css')
<style>
    .skill-validation-card { margin-bottom: 1.5rem; }
    .portfolio-list { list-style: none; padding-left: 0; }
    .portfolio-list li {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        border-radius: 0.25rem;
    }
    .portfolio-list .portfolio-title { font-weight: bold; }
    .portfolio-list .portfolio-type { font-size: 0.85em; color: #6c757d; }
    .portfolio-list .portfolio-link a { word-break: break-all; }
    .badge.status-badge { font-size: 0.9em; }
    .form-label { font-weight: 500; }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-3">
        <div class="col-12">
            <a href="{{ route('dosen.mahasiswa-dpa.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali ke Daftar Mahasiswa
            </a>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header border-bottom d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0 text-dark-blue">Validasi Skill Mahasiswa</h5>
                <p class="mb-0 text-muted">
                    <strong>Nama:</strong> {{ $mahasiswa->nama_lengkap }} |
                    <strong>NIM:</strong> {{ $mahasiswa->nim }} |
                    <strong>Prodi:</strong> {{ $mahasiswa->prodi->nama_prodi ?? 'N/A' }}
                </p>
            </div>
        </div>
        <div class="card-body">
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

            @if($skillsForValidation->isEmpty())
                <div class="alert alert-info text-center">
                    Mahasiswa ini belum menambahkan skill apapun untuk divalidasi.
                </div>
            @else
                @foreach($skillsForValidation as $index => $mskill)
                    <div class="card skill-validation-card {{ session('error_skill_id') == $mskill->mahasiswa_skill_id ? 'border-danger' : '' }}">
                        <div class="card-body">
                            <form action="{{ route('dosen.mahasiswa-dpa.skill.update_validasi', $mskill->mahasiswa_skill_id) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-7">
                                        <h5>{{ $index + 1 }}. Skill: {{ $mskill->detailSkill->skill_nama ?? 'Tidak diketahui' }}</h5>
                                        <p class="mb-1">
                                            Level Diajukan Mahasiswa: <span class="badge bg-info status-badge">{{ $mskill->level_kompetensi }}</span>
                                        </p>
                                        <p>
                                            Status Verifikasi Saat Ini:
                                            @php
                                                $currentStatusClass = 'bg-secondary';
                                                if ($mskill->status_verifikasi === 'Valid') $currentStatusClass = 'bg-success';
                                                if ($mskill->status_verifikasi === 'Invalid') $currentStatusClass = 'bg-danger';
                                            @endphp
                                            <span class="badge {{ $currentStatusClass }} status-badge">{{ $mskill->status_verifikasi }}</span>
                                        </p>

                                        <h6>Bukti Portofolio Terkait:</h6>
                                        @if($mskill->linkedPortofolios->isEmpty())
                                            <p class="text-muted fst-italic">Tidak ada portofolio yang dikaitkan untuk skill ini.</p>
                                        @else
                                            <ul class="portfolio-list">
                                                @foreach($mskill->linkedPortofolios as $portfolioLink)
                                                <li>
                                                    <span class="portfolio-title">{{ $portfolioLink->portofolio->judul_portofolio }}</span>
                                                    (<span class="portfolio-type">{{ ucfirst($portfolioLink->portofolio->tipe_portofolio) }}</span>)
                                                    @if($portfolioLink->portofolio->tipe_portofolio == 'url' || $portfolioLink->portofolio->tipe_portofolio == 'video')
                                                        <span class="portfolio-link">
                                                            <a href="{{ $portfolioLink->portofolio->lokasi_file_atau_url }}" target="_blank" class="ms-2"><i class="fas fa-external-link-alt"></i> Lihat</a>
                                                        </span>
                                                    @elseif($portfolioLink->portofolio->tipe_portofolio == 'file' || $portfolioLink->portofolio->tipe_portofolio == 'gambar')
                                                         <span class="portfolio-link">
                                                            <a href="{{ asset('storage/' . $portfolioLink->portofolio->lokasi_file_atau_url) }}" target="_blank" class="ms-2"><i class="fas fa-download"></i> Lihat/Unduh</a>
                                                        </span>
                                                    @endif
                                                    @if($portfolioLink->pivot->deskripsi_penggunaan_skill)
                                                        <p class="small text-muted mt-1 mb-0">Deskripsi Penggunaan: {{ $portfolioLink->pivot->deskripsi_penggunaan_skill }}</p>
                                                    @endif
                                                </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                    <div class="col-md-5 border-start">
                                        <div class="mb-3">
                                            <label for="level_kompetensi_{{ $mskill->mahasiswa_skill_id }}" class="form-label">Validasi Level Kompetensi <span class="text-danger">*</span></label>
                                            <select name="level_kompetensi" id="level_kompetensi_{{ $mskill->mahasiswa_skill_id }}" class="form-select @error('level_kompetensi', $mskill->mahasiswa_skill_id . '_errors') is-invalid @enderror" required>
                                                <option value="Beginner" {{ (old('level_kompetensi', $mskill->level_kompetensi) ?? $mskill->level_kompetensi) == 'Beginner' ? 'selected' : '' }}>Beginner</option>
                                                <option value="Intermediate" {{ (old('level_kompetensi', $mskill->level_kompetensi) ?? $mskill->level_kompetensi) == 'Intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                <option value="Expert" {{ (old('level_kompetensi', $mskill->level_kompetensi) ?? $mskill->level_kompetensi) == 'Expert' ? 'selected' : '' }}>Expert</option>
                                            </select>
                                            @error('level_kompetensi', $mskill->mahasiswa_skill_id . '_errors') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        <div class="mb-3">
                                            <label for="status_verifikasi_{{ $mskill->mahasiswa_skill_id }}" class="form-label">Status Verifikasi <span class="text-danger">*</span></label>
                                            <select name="status_verifikasi" id="status_verifikasi_{{ $mskill->mahasiswa_skill_id }}" class="form-select @error('status_verifikasi', $mskill->mahasiswa_skill_id . '_errors') is-invalid @enderror" required>
                                                <option value="Pending" {{ (old('status_verifikasi', $mskill->status_verifikasi) ?? $mskill->status_verifikasi) == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="Valid" {{ (old('status_verifikasi', $mskill->status_verifikasi) ?? $mskill->status_verifikasi) == 'Valid' ? 'selected' : '' }}>Valid</option>
                                                <option value="Invalid" {{ (old('status_verifikasi', $mskill->status_verifikasi) ?? $mskill->status_verifikasi) == 'Invalid' ? 'selected' : '' }}>Invalid</option>
                                            </select>
                                            @error('status_verifikasi', $mskill->mahasiswa_skill_id . '_errors') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                        </div>
                                        {{-- Jika ada field catatan DPA:
                                        <div class="mb-3">
                                            <label for="catatan_dpa_{{ $mskill->mahasiswa_skill_id }}" class="form-label">Catatan DPA (Opsional)</label>
                                            <textarea name="catatan_dpa" id="catatan_dpa_{{ $mskill->mahasiswa_skill_id }}" class="form-control" rows="2">{{ old('catatan_dpa') }}</textarea>
                                        </div>
                                        --}}
                                        <button type="submit" class="btn btn-success w-100"><i class="fas fa-check-circle me-1"></i> Simpan Validasi</button>
                                    </div>
                                </div>
                            </form>
                             @if ($errors->hasBag($mskill->mahasiswa_skill_id . '_errors'))
                                <div class="alert alert-danger mt-2 py-2">
                                    <ul class="mb-0">
                                        @foreach ($errors->getBag($mskill->mahasiswa_skill_id . '_errors')->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                        @if(!$loop->last)
                            <hr class="my-0">
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    // Tidak ada JS khusus yang dibutuhkan untuk halaman ini saat ini,
    // kecuali jika Anda ingin interaksi yang lebih kompleks.
    // Pastikan Bootstrap JS dimuat untuk fungsionalitas alert dismissal.
</script>
@endpush
