<div id="form-preferences-container"> <!-- Tambahkan pembungkus -->
  <form action="{{ url('/intern/preferences') }}" method="POST" id="form-preferences">
    @csrf

    <div class="mb-3">
      <label for="region" class="form-label">Preferensi Lokasi Magang</label>
      <select name="region" id="region" class="form-control" required>
        <option value="">-- Pilih Lokasi --</option>
        <option value="Jakarta"    {{ old('region', $user->region ?? '') == 'Jakarta' ? 'selected' : '' }}>Jakarta</option>
        <option value="Bandung"    {{ old('region', $user->region ?? '') == 'Bandung' ? 'selected' : '' }}>Bandung</option>
        <option value="Surabaya"   {{ old('region', $user->region ?? '') == 'Surabaya' ? 'selected' : '' }}>Surabaya</option>
        <option value="Yogyakarta" {{ old('region', $user->region ?? '') == 'Yogyakarta' ? 'selected' : '' }}>Yogyakarta</option>
      </select>
      <small id="error-region" class="text-danger"></small>
    </div>
<div class="mb-3">
      <label for="intern_type" class="form-label">Jenis Magang</label>
      <select name="intern_type" id="intern_type" class="form-control" required>
        <option value="">-- Pilih Jenis --</option>
        <option value="Onsite"  {{ old('intern_type', $user->intern_type ?? '') == 'Onsite'  ? 'selected' : '' }}>On‐Site</option>
        <option value="Remote"  {{ old('intern_type', $user->intern_type ?? '') == 'Remote'  ? 'selected' : '' }}>Remote</option>
        <option value="Hybrid"  {{ old('intern_type', $user->intern_type ?? '') == 'Hybrid'  ? 'selected' : '' }}>Hybrid</option>
      </select>
      <small id="error-intern_type" class="text-danger"></small>
    </div>
<div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-success">Simpan Preferensi</button>
      <button type="button" class="btn btn-secondary" id="btn-close-preferences">Tutup</button>
    </div>

    <div id="alertPreferences" class="alert mt-3" style="display:none;"></div>
  </form>
<script>
$(function(){
  $('#btn-close-preferences').on('click', function() {
    console.log('Tombol Tutup diklik');
    $('#form-preferences-container').hide();
  });
});
</script>
</div>
@push('js')
<script>
$(function(){
  $('#form-preferences').on('submit', function(e){
    e.preventDefault();
    let $form = $(this);

    $('#error-region, #error-intern_type').text('');
    $('#alertPreferences').hide();

    $.ajax({
      url:   $form.attr('action'),
      type:  'POST',
      data:  $form.serialize(),
      success: function(res){
        $('#alertPreferences')
          .removeClass('alert-danger')
          .addClass('alert-success')
          .text(res.message)
          .show();
      },
error: function(xhr){
        if (xhr.status === 422) {
          let errs = xhr.responseJSON.errors;
          if (errs.region)      $('#error-region').text(errs.region[0]);
          if (errs.intern_type) $('#error-intern_type').text(errs.intern_type[0]);
        } else {
          $('#alertPreferences')
            .removeClass('alert-success')
            .addClass('alert-danger')
            .text('Terjadi kesalahan pada server.')
            .show();
        }
      }
    });
  });
});
</script>
@endpush