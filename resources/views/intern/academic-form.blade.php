<form id="form-academic" enctype="multipart/form-data">
    @csrf

    {{-- Program Studi --}}
    <div class="mb-3">
      <label for="prodi_id" class="form-label">Program Studi</label>
      <select name="prodi_id" id="prodi_id" class="form-control" required>
        <option value="">-- pilih prodi --</option>
        @foreach($prodis as $prodi)
          <option value="{{ $prodi->id }}"
            {{ old('prodi_id', $mahasiswa->prodi_id ?? '') == $prodi->id ? 'selected' : '' }}>
            {{ $prodi->nama_prodi }}
          </option>
        @endforeach
      </select>
      <small class="text-danger">@error('prodi_id') {{ $message }} @enderror</small>
    </div>
 {{-- Dosen Pembimbing --}}
    <div class="mb-3">
      <label for="dosen_id" class="form-label">Dosen Pembimbing</label>
      <select name="dosen_id" id="dosen_id" class="form-control">
        <option value="">-- pilih dosen --</option>
        @foreach($dosens as $dosen)
          <option value="{{ $dosen->id }}"
            {{ old('dosen_id', $mahasiswa->dosen_id ?? '') == $dosen->id ? 'selected' : '' }}>
            {{ $dosen->nama_lengkap }}
          </option>
        @endforeach
      </select>
      <small class="text-danger">@error('dosen_id') {{ $message }} @enderror</small>
    </div>
 {{-- IPK --}}
    <div class="mb-3">
      <label for="ipk" class="form-label">IPK</label>
      <input type="number" step="0.01" name="ipk" id="ipk" class="form-control"
        value="{{ old('ipk', $user->ipk ?? '') }}">
      <small id="error-ipk" class="text-danger"></small>
    </div>
    <hr>
    <h5>Unggah Dokumen (PDF / Foto max 2MB)</h5>
 {{-- Sertifikat Kompetensi --}}
    <div class="mb-3">
      <label for="sertifikat_kompetensi" class="form-label">
        Sertifikat Kompetensi <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
      </label>
      <input type="file" name="sertifikat_kompetensi" id="sertifikat_kompetensi"
             class="form-control" accept="application/pdf,image/*">
      <small class="text-danger">@error('sertifikat_kompetensi') {{ $message }} @enderror</small>
      @if(!empty($mahasiswa->sertifikat_kompetensi))
        <div class="mt-1">
          <a href="{{ Storage::url($mahasiswa->sertifikat_kompetensi) }}" target="_blank">
            Lihat Sertifikat Kompetensi
          </a>
        </div>
      @endif
    </div>
 {{-- Pakta Integritas --}}
    <div class="mb-3">
      <label for="pakta_integritas" class="form-label">
        Pakta Integritas <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
      </label>
      <input type="file" name="pakta_integritas" id="pakta_integritas"
             class="form-control" accept="application/pdf,image/*">
      <small class="text-danger">@error('pakta_integritas') {{ $message }} @enderror</small>
      @if(!empty($mahasiswa->pakta_integritas))
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
      <input type="file" name="daftar_riwayat_hidup" id="daftar_riwayat_hidup"
             class="form-control" accept="application/pdf,image/*">
      <small class="text-danger">@error('daftar_riwayat_hidup') {{ $message }} @enderror</small>
      @if(!empty($mahasiswa->daftar_riwayat_hidup))
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
      <input type="file" name="khs" id="khs"
             class="form-control" accept="application/pdf,image/*">
      <small class="text-danger">@error('khs') {{ $message }} @enderror</small>
      @if(!empty($mahasiswa->khs))
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
      <input type="file" name="ktp" id="ktp"
             class="form-control" accept="application/pdf,image/*">
      <small class="text-danger">@error('ktp') {{ $message }} @enderror</small>
      @if(!empty($mahasiswa->ktp))
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
      <input type="file" name="ktm" id="ktm"
             class="form-control" accept="application/pdf,image/*">
      <small class="text-danger">@error('ktm') {{ $message }} @enderror</small>
      @if(!empty($mahasiswa->ktm))
        <div class="mt-1">
          <a href="{{ Storage::url($mahasiswa->ktm) }}" target="_blank">Lihat KTM</a>
        </div>
      @endif
    </div>
 {{-- Surat Izin Orang Tua --}}
    <div class="mb-3">
      <label for="surat_izin_ortu" class="form-label">
        Surat Izin Orang Tua <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
      </label>
      <input type="file" name="surat_izin_ortu" id="surat_izin_ortu"
             class="form-control" accept="application/pdf,image/*">
      <small class="text-danger">@error('surat_izin_ortu') {{ $message }} @enderror</small>
      @if(!empty($mahasiswa->surat_izin_ortu))
        <div class="mt-1">
          <a href="{{ Storage::url($mahasiswa->surat_izin_ortu) }}" target="_blank">
            Lihat Surat Izin Orang Tua
          </a>
        </div>
      @endif
    </div>
 {{-- BPJS --}}
    <div class="mb-3">
      <label for="bpjs" class="form-label">
        BPJS <span class="text-muted">(PDF/JPG/PNG max 2MB)</span>
      </label>
      <input type="file" name="bpjs" id="bpjs"
             class="form-control" accept="application/pdf,image/*">
      <small class="text-danger">@error('bpjs') {{ $message }} @enderror</small>
      @if(!empty($mahasiswa->bpjs))
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
      <input type="file" name="sktm_kip" id="sktm_kip"
             class="form-control" accept="application/pdf,image/*">
      <small class="text-danger">@error('sktm_kip') {{ $message }} @enderror</small>
      @if(!empty($mahasiswa->sktm_kip))
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
      <input type="file" name="proposal" id="proposal"
             class="form-control" accept="application/pdf,image/*">
      <small class="text-danger">@error('proposal') {{ $message }} @enderror</small>
      @if(!empty($mahasiswa->proposal))
        <div class="mt-1">
          <a href="{{ Storage::url($mahasiswa->proposal) }}" target="_blank">Lihat Proposal</a>
        </div>
      @endif
    </div>
  <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-primary">Simpan Semua</button>
      <button type="button" class="btn btn-secondary" id="btn-close-academic">Tutup</button>
      <script>
        $('#btn-close-academic').on('click', function(){
        $('#academic-form-container').slideUp();
        });
      </script>
    </div>
  </form>

  <div id="alertMessage" class="alert mt-3" style="display:none;"></div>