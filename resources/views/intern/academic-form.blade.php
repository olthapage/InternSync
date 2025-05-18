<form id="form-academic" enctype="multipart/form-data">
  @csrf

  <!-- Jenjang Pendidikan -->
  <div class="mb-3">
    <label for="pendidikan" class="form-label">Jenjang Pendidikan</label>
    <input type="text" name="pendidikan" id="pendidikan" class="form-control"
      value="{{ old('pendidikan', $user->pendidikan ?? '') }}" required>
    <small id="error-pendidikan" class="text-danger"></small>
  </div>
  <!-- Bidang Keahlian -->
  <div class="mb-3">
    <label for="bidang_keahlian" class="form-label">Bidang Keahlian</label>
    <textarea name="bidang_keahlian" id="bidang_keahlian" class="form-control" rows="3" required>{{ old('bidang_keahlian', $user->bidang_keahlian ?? '') }}</textarea>
    <small id="error-bidang_keahlian" class="text-danger"></small>
  </div>
 <!-- Sertifikasi -->
  <div class="mb-3">
    <label for="sertifikasi" class="form-label">Sertifikasi (pisahkan koma)</label>
    <input type="text" name="sertifikasi" id="sertifikasi" class="form-control"
      value="{{ old('sertifikasi', $user->sertifikasi ?? '') }}">
    <small id="error-sertifikasi" class="text-danger"></small>
  </div>
  <!-- Pengalaman -->
  <div class="mb-3">
    <label for="pengalaman" class="form-label">Pengalaman Kerja / Proyek</label>
    <textarea name="pengalaman" id="pengalaman" class="form-control" rows="4">{{ old('pengalaman', $user->pengalaman ?? '') }}</textarea>
    <small id="error-pengalaman" class="text-danger"></small>
  </div>
  <!-- IPK -->
  <div class="mb-3">
    <label for="ipk" class="form-label">IPK (opsional)</label>
    <input type="number" step="0.01" name="ipk" id="ipk" class="form-control"
      value="{{ old('ipk', $user->ipk ?? '') }}">
    <small id="error-ipk" class="text-danger"></small>
  </div>
<hr>
  <h5>Unggah Dokumen (PDF)</h5>
  <!-- CV -->
  <div class="mb-3">
    <label for="cv" class="form-label">CV <span class="text-muted">(PDF max 2MB)</span></label>
    <input type="file" name="cv" id="cv" class="form-control" accept="application/pdf">
    <small id="error-cv" class="text-danger"></small>
    @if(!empty($user->cv_path))
      <a href="{{ Storage::url($user->cv_path) }}" target="_blank">Lihat CV saat ini</a>
    @endif
  </div>
<!-- Surat Pengantar -->
  <div class="mb-3">
    <label for="cover_letter" class="form-label">Surat Pengantar <span class="text-muted">(PDF max 2MB)</span></label>
    <input type="file" name="cover_letter" id="cover_letter" class="form-control" accept="application/pdf">
    <small id="error-cover_letter" class="text-danger"></small>
    @if(!empty($user->cover_letter_path))
      <a href="{{ Storage::url($user->cover_letter_path) }}" target="_blank">Lihat Surat Pengantar</a>
    @endif
  </div>
  <!-- Sertifikat -->
  <div class="mb-3">
    <label for="certificates" class="form-label">Sertifikat <span class="text-muted">(PDF max 2MB tiap file)</span></label>
    <input type="file" name="certificates[]" id="certificates" class="form-control" accept="application/pdf" multiple>
    <small id="error-certificates" class="text-danger"></small>
    @if(!empty($user->certificate_paths))
      <ul class="mt-2">
        @foreach(json_decode($user->certificate_paths) as $path)
          <li><a href="{{ Storage::url($path) }}" target="_blank">Lihat {{ basename($path) }}</a></li>
        @endforeach
      </ul>
    @endif
  </div>
<div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-primary">Simpan Semua</button>
      <button type="button" class="btn btn-secondary" id="btn-close-academic">Tutup</button>
  </div>
</form>
<script>
$(function(){
  $('#btn-close-academic').on('click', function() {
    console.log('Tombol Tutup Form Akademik diklik');
    $('#form-academic').hide(); 
  });
});
</script>

<div id="alertMessage" class="alert mt-3" style="display:none;"></div>