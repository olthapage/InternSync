@php
    $mahasiswa = auth()->user();
    // Ambil nilai prodi_id dan dpa_id yang mungkin sudah ada (dari data mahasiswa atau input lama)
    $selectedProdiId = old('prodi_id', $mahasiswa->prodi_id ?? '');
    $selectedDpaId = old('dpa_id', $mahasiswa->dpa_id ?? ($mahasiswa->dosenWali->dosen_id ?? '')); // Sesuaikan jika relasi DPA di model Mahasiswa berbeda
@endphp

<form action="{{ route('mahasiswa.verifikasi.store') }}" id="form-academic" method="POST" enctype="multipart/form-data">
    @csrf

    {{-- Status Verifikasi --}}
    <div class="mb-3">
        @if ($mahasiswa->status_verifikasi == 'invalid')
            <div class="alert bg-gradient-danger text-white">
                <strong>Status:</strong> Ditolak <br>
                <strong>Alasan Penolakan:</strong> {{ $mahasiswa->alasan }}
            </div>
        @elseif ($mahasiswa->status_verifikasi == 'valid')
            <div class="alert bg-gradient-success text-white">
                <strong>Status:</strong> Diterima <br>
                Data telah diverifikasi dan diterima.
            </div>
        @elseif ($mahasiswa->status_verifikasi == 'pending')
            <div class="alert bg-gradient-warning text-white">
                <strong>Status:</strong> Menunggu Verifikasi <br>
                Data sedang dalam proses pemeriksaan.
            </div>
        @else
            <div class="alert bg-gradient-light text-dark">
                <strong>Status:</strong> Belum diisi <br>
                Data sedang dalam proses pemeriksaan.
            </div>
        @endif
    </div>

    {{-- Program Studi --}}
    <div class="mb-3">
        <label for="prodi_id" class="form-label">Program Studi <span class="text-danger">*</span></label>
        <select name="prodi_id" id="prodi_id" class="form-control" required>
            <option value="">-- pilih prodi --</option>
            @foreach ($prodis as $prodi)
                <option value="{{ $prodi->prodi_id }}"
                    {{ $selectedProdiId == $prodi->prodi_id ? 'selected' : '' }}>
                    {{ $prodi->nama_prodi }}
                </option>
            @endforeach
        </select>
        <small class="text-danger">
            @error('prodi_id')
                {{ $message }}
            @enderror
        </small>
    </div>

    {{-- Dosen DPA --}}
    <div class="mb-3">
        <label for="dpa_id" class="form-label">Dosen DPA <span class="text-danger">*</span></label>
        <select name="dpa_id" id="dpa_id" class="form-control" required {{ !$selectedProdiId ? 'disabled' : '' }}>
            <option value="">-- pilih prodi terlebih dahulu --</option>
            {{-- Opsi Dosen DPA akan diisi oleh JavaScript --}}
            {{-- Jika ada $selectedProdiId dan $selectedDpaId, kita akan coba load dan select di script --}}
        </select>
        <small class="text-danger">
            @error('dpa_id')
                {{ $message }}
            @enderror
        </small>
    </div>


    {{-- IPK --}}
    <div class="mb-3">
        <label for="ipk" class="form-label">IPK</label>
        <input type="number" step="0.01" name="ipk" id="ipk" class="form-control"
            value="{{ old('ipk', $mahasiswa->ipk ?? '') }}">
        <small id="error-ipk" class="text-danger"> @error('ipk') {{ $message }} @enderror</small>
    </div>

    {{-- TAMBAHAN: Aktivitas Organisasi --}}
    <div class="mb-3">
        <label for="organisasi" class="form-label">Aktivitas Organisasi <span class="text-danger">*</span></label>
        <select name="organisasi" id="organisasi" class="form-select @error('organisasi') is-invalid @enderror" required>
            <option value="tidak_ikut" {{ old('organisasi', optional($mahasiswa)->organisasi ?? 'tidak_ikut') == 'tidak_ikut' ? 'selected' : '' }}>Tidak Ikut Organisasi</option>
            <option value="aktif" {{ old('organisasi', optional($mahasiswa)->organisasi) == 'aktif' ? 'selected' : '' }}>Aktif Berorganisasi</option>
            <option value="sangat_aktif" {{ old('organisasi', optional($mahasiswa)->organisasi) == 'sangat_aktif' ? 'selected' : '' }}>Sangat Aktif Berorganisasi (Misal: Pengurus Inti)</option>
        </select>
        @error('organisasi') <small class="text-danger">{{ $message }}</small> @enderror
    </div>

    {{-- TAMBAHAN: Aktivitas Lomba --}}
    <div class="mb-3">
        <label for="lomba" class="form-label">Aktivitas Lomba/Kompetisi <span class="text-danger">*</span></label>
        <select name="lomba" id="lomba" class="form-select @error('lomba') is-invalid @enderror" required>
            <option value="tidak_ikut" {{ old('lomba', optional($mahasiswa)->lomba ?? 'tidak_ikut') == 'tidak_ikut' ? 'selected' : '' }}>Tidak Pernah Ikut Lomba</option>
            <option value="aktif" {{ old('lomba', optional($mahasiswa)->lomba) == 'aktif' ? 'selected' : '' }}>Pernah Ikut Lomba (Peserta/Finalis)</option>
            <option value="sangat_aktif" {{ old('lomba', optional($mahasiswa)->lomba) == 'sangat_aktif' ? 'selected' : '' }}>Sering Ikut & Memenangkan Lomba</option>
        </select>
        @error('lomba') <small class="text-danger">{{ $message }}</small> @enderror
    </div>
    {{-- AKHIR TAMBAHAN --}}
    <hr>
    <h5>Unggah Dokumen (PDF / Foto max 2MB)</h5>

    {{-- Sertifikat Kompetensi --}}
    <div class="mb-3">
        <label for="sertifikat_kompetensi" class="form-label">
            Sertifikat Kompetensi <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
        </label>
        <input type="file" name="sertifikat_kompetensi" id="sertifikat_kompetensi" class="form-control"
            accept="application/pdf,image/*">
        <small class="text-danger">
            @error('sertifikat_kompetensi')
                {{ $message }}
            @enderror
        </small>
        @if (!empty($mahasiswa->sertifikat_kompetensi))
            <div class="mt-1">
                <a href="{{ Storage::url($mahasiswa->sertifikat_kompetensi) }}" target="_blank">
                    Lihat Sertifikat Kompetensi
                </a>
            </div>
        @endif
    </div>

    {{-- TAMBAHAN: Sertifikat Organisasi --}}
    <div class="mb-3">
        <label for="sertifikat_organisasi" class="form-label">
            Sertifikat Keaktifan Organisasi <span class="text-muted">(Opsional, PDF/JPG/PNG max 2MB)</span>
        </label>
        <input type="file" name="sertifikat_organisasi" id="sertifikat_organisasi" class="form-control @error('sertifikat_organisasi') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
        @error('sertifikat_organisasi') <small class="text-danger">{{ $message }}</small> @enderror
        <small>Jika lebih dari 1, jadikan PDF</small>
        @if (optional($mahasiswa)->sertifikat_organisasi)
            <div class="mt-1">
                <a href="{{ Storage::url($mahasiswa->sertifikat_organisasi) }}" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-2">
                    <i class="fas fa-eye me-1"></i> Lihat Sertifikat Organisasi Saat Ini
                </a>
            </div>
        @endif
    </div>

    {{-- TAMBAHAN: Sertifikat Lomba --}}
    <div class="mb-3">
        <label for="sertifikat_lomba" class="form-label">
            Sertifikat Prestasi Lomba <span class="text-muted">(Opsional, PDF/JPG/PNG max 2MB)</span>
        </label>
        <input type="file" name="sertifikat_lomba" id="sertifikat_lomba" class="form-control @error('sertifikat_lomba') is-invalid @enderror" accept=".pdf,.jpg,.jpeg,.png">
        @error('sertifikat_lomba') <small class="text-danger">{{ $message }}</small> @enderror
        <small>Jika lebih dari 1, jadikan PDF</small>
        @if (optional($mahasiswa)->sertifikat_lomba)
            <div class="mt-1">
                <a href="{{ Storage::url($mahasiswa->sertifikat_lomba) }}" target="_blank" class="btn btn-sm btn-outline-secondary py-1 px-2">
                    <i class="fas fa-eye me-1"></i> Lihat Sertifikat Lomba Saat Ini
                </a>
            </div>
        @endif
    </div>
    {{-- AKHIR TAMBAHAN SERTIFIKAT --}}

    {{-- Pakta Integritas --}}
    <div class="mb-3">
        <label for="pakta_integritas" class="form-label">
            Pakta Integritas <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
        </label>
        <input type="file" name="pakta_integritas" id="pakta_integritas" class="form-control"
            accept="application/pdf,image/*">
        <small class="text-danger">
            @error('pakta_integritas')
                {{ $message }}
            @enderror
        </small>
        @if (!empty($mahasiswa->pakta_integritas))
            <div class="mt-1">
                <a href="{{ Storage::url($mahasiswa->pakta_integritas) }}" target="_blank">
                    Lihat Pakta Integritas
                </a>
            </div>
        @endif
    </div>

    {{-- Daftar Riwayat Hidup --}}
    <div class="mb-3">
        <label for="daftar_riwayat_hidup" class="form-label">
            Daftar Riwayat Hidup <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
        </label>
        <input type="file" name="daftar_riwayat_hidup" id="daftar_riwayat_hidup" class="form-control"
            accept="application/pdf,image/*">
        <small class="text-danger">
            @error('daftar_riwayat_hidup')
                {{ $message }}
            @enderror
        </small>
        @if (!empty($mahasiswa->daftar_riwayat_hidup))
            <div class="mt-1">
                <a href="{{ Storage::url($mahasiswa->daftar_riwayat_hidup) }}" target="_blank">
                    Lihat Daftar Riwayat Hidup
                </a>
            </div>
        @endif
    </div>

    {{-- KHS --}}
    <div class="mb-3">
        <label for="khs" class="form-label">
            KHS <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
        </label>
        <input type="file" name="khs" id="khs" class="form-control" accept="application/pdf,image/*">
        <small class="text-danger">
            @error('khs')
                {{ $message }}
            @enderror
        </small>
        @if (!empty($mahasiswa->khs))
            <div class="mt-1">
                <a href="{{ Storage::url($mahasiswa->khs) }}" target="_blank">Lihat KHS</a>
            </div>
        @endif
    </div>

    {{-- KTP --}}
    <div class="mb-3">
        <label for="ktp" class="form-label">
            KTP <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
        </label>
        <input type="file" name="ktp" id="ktp" class="form-control" accept="application/pdf,image/*">
        <small class="text-danger">
            @error('ktp')
                {{ $message }}
            @enderror
        </small>
        @if (!empty($mahasiswa->ktp))
            <div class="mt-1">
                <a href="{{ Storage::url($mahasiswa->ktp) }}" target="_blank">Lihat KTP</a>
            </div>
        @endif
    </div>

    {{-- KTM --}}
    <div class="mb-3">
        <label for="ktm" class="form-label">
            KTM <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
        </label>
        <input type="file" name="ktm" id="ktm" class="form-control" accept="application/pdf,image/*">
        <small class="text-danger">
            @error('ktm')
                {{ $message }}
            @enderror
        </small>
        @if (!empty($mahasiswa->ktm))
            <div class="mt-1">
                <a href="{{ Storage::url($mahasiswa->ktm) }}" target="_blank">Lihat KTM</a>
            </div>
        @endif
    </div>

    {{-- Surat Izin Orang Tua --}}
    <div class="mb-3">
        <label for="surat_izin_ortu" class="form-label">
            Surat Izin Orang Tua <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
        </label>
        <input type="file" name="surat_izin_ortu" id="surat_izin_ortu" class="form-control"
            accept="application/pdf,image/*">
        <small class="text-danger">
            @error('surat_izin_ortu')
                {{ $message }}
            @enderror
        </small>
        @if (!empty($mahasiswa->surat_izin_ortu))
            <div class="mt-1">
                <a href="{{ Storage::url($mahasiswa->surat_izin_ortu) }}" target="_blank">
                    Lihat Surat Izin Orang Tua
                </a>
            </div>
        @endif
    </div>

    {{-- BPJS --}}
    <div class="mb-3">
        <label for="bpjs" class="form-label">
            BPJS <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
        </label>
        <input type="file" name="bpjs" id="bpjs" class="form-control" accept="application/pdf,image/*">
        <small class="text-danger">
            @error('bpjs')
                {{ $message }}
            @enderror
        </small>
        @if (!empty($mahasiswa->bpjs))
            <div class="mt-1">
                <a href="{{ Storage::url($mahasiswa->bpjs) }}" target="_blank">Lihat BPJS</a>
            </div>
        @endif
    </div>

    {{-- SKTM/KIP --}}
    <div class="mb-3">
        <label for="sktm_kip" class="form-label">
            SKTM / KIP <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
        </label>
        <input type="file" name="sktm_kip" id="sktm_kip" class="form-control" accept="application/pdf,image/*">
        <small class="text-danger">
            @error('sktm_kip')
                {{ $message }}
            @enderror
        </small>
        @if (!empty($mahasiswa->sktm_kip))
            <div class="mt-1">
                <a href="{{ Storage::url($mahasiswa->sktm_kip) }}" target="_blank">Lihat SKTM/KIP</a>
            </div>
        @endif
    </div>

    {{-- Proposal --}}
    <div class="mb-3">
        <label for="proposal" class="form-label">
            Proposal <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
        </label>
        <input type="file" name="proposal" id="proposal" class="form-control" accept="application/pdf,image/*">
        <small class="text-danger">
            @error('proposal')
                {{ $message }}
            @enderror
        </small>
        @if (!empty($mahasiswa->proposal))
            <div class="mt-1">
                <a href="{{ Storage::url($mahasiswa->proposal) }}" target="_blank">Lihat Proposal</a>
            </div>
        @endif
    </div>

    <div class="d-flex justify-content-between">
        <button type="submit" class="btn btn-primary">Simpan Semua</button>
        <button type="button" class="btn btn-secondary" id="btn-close-academic">Tutup</button>
    </div>
</form>

<div id="alertMessage" class="alert mt-3" style="display:none;"></div>

{{-- Pastikan jQuery sudah dimuat sebelum script ini --}}
<script>
    $(document).ready(function() {
        const prodiSelect = $('#prodi_id');
        const dpaSelect = $('#dpa_id');
        // Simpan nilai dpa_id yang mungkin sudah ada (dari server/old input)
        // Perhatikan: $selectedDpaId adalah variabel PHP, kita perlu nilainya di JS
        const previouslySelectedDpaId = '{{ $selectedDpaId }}';

        function loadDosenDpa(prodiId, selectedDpaId = null) {
            dpaSelect.empty().append('<option value="">Memuat dosen...</option>').prop('disabled', true);

            if (!prodiId) {
                dpaSelect.empty().append('<option value="">-- pilih prodi terlebih dahulu --</option>').prop('disabled', true);
                return;
            }

            // Ganti URL dengan route yang benar jika berbeda
            const url = `{{ url('/mahasiswa/verifikasi/get-dosen-by-prodi') }}/${prodiId}`;

            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    dpaSelect.empty(); // Kosongkan lagi sebelum mengisi
                    if (data.length > 0) {
                        dpaSelect.append('<option value="">-- pilih dosen DPA --</option>');
                        $.each(data, function(key, dosen) {
                            // Buat option baru
                            let option = $('<option></option>').attr('value', dosen.dosen_id).text(dosen.nama_lengkap);
                            // Jika ada selectedDpaId dan cocok, tandai sebagai selected
                            if (selectedDpaId && dosen.dosen_id == selectedDpaId) {
                                option.prop('selected', true);
                            }
                            dpaSelect.append(option);
                        });
                        dpaSelect.prop('disabled', false);
                    } else {
                        dpaSelect.append('<option value="">-- Tidak ada Dosen DPA untuk prodi ini --</option>');
                        dpaSelect.prop('disabled', true);
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching dosen DPA: ", error);
                    dpaSelect.empty().append('<option value="">Gagal memuat dosen</option>').prop('disabled', true);
                }
            });
        }

        // Event listener untuk perubahan prodi
        prodiSelect.on('change', function() {
            const selectedProdi = $(this).val();
            loadDosenDpa(selectedProdi); // Saat prodi berubah, tidak perlu mengirim dpa_id lama
        });

        // Panggil loadDosenDpa saat halaman dimuat jika prodi sudah terpilih
        // (misalnya karena ada old input atau data dari database)
        const initialProdiId = prodiSelect.val();
        if (initialProdiId) {
            loadDosenDpa(initialProdiId, previouslySelectedDpaId);
        }

        // Script untuk tombol tutup (sudah ada)
        $('#btn-close-academic').on('click', function() {
            $('#academic-form-container').slideUp();
        });

        // Validasi IPK (contoh, jika Anda membutuhkannya)
        $('#ipk').on('input', function() {
            var ipk = parseFloat($(this).val());
            var errorIpk = $('#error-ipk');
            if (ipk < 0 || ipk > 4) {
                errorIpk.text('IPK harus antara 0.00 dan 4.00');
                $(this).addClass('is-invalid');
            } else {
                errorIpk.text('');
                $(this).removeClass('is-invalid');
            }
        });
    });
</script>
