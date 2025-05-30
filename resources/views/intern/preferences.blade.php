<div id="form-preferences-container" style="display: block;">
  <form action="{{ url('/intern/preferences') }}" method="POST" id="form-preferences">
    @csrf
    <div class="mb-3">
      <label for="province" class="form-label">Preferensi Provinsi Magang</label>
      <select name="province_id" id="province_id" class="form-control" required>
        <option value="">-- Pilih Provinsi --</option>
        @foreach ($province as $provinsi)
          <option value="{{ $provinsi->id }}" {{ old('province_id', $user->province_id ?? '') == $provinsi->id ? 'selected' : '' }}>
            {{ $provinsi->provinsi_nama }}
          </option>
        @endforeach
      </select>
      <small id="error-province" class="text-danger"></small>
    </div>

    <div class="mb-3">
      <label for="city" class="form-label">Preferensi Kota Magang</label>
      <select name="city_id" id="city_id" class="form-control" required>
        <option value="">-- Pilih Kota --</option>
      </select>
      <small id="error-city" class="text-danger"></small>
    </div>

    <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-success">Simpan Preferensi</button>
      <button type="button" class="btn btn-secondary" id="btn-close-preferences">Tutup</button>
      <script>
        $('#btn-close-preferences').on('click', function () {
          $('#form-preferences-container').slideUp();
        });
      </script>
    </div>

    <div id="alertPreferences" class="alert mt-3" style="display: none;"></div>
  </form>
</div>

@push('js')
<script>
  function loadKota(provinsiId, selectedCityId = null) {
  const $kotaSelect = $('#city_id'); // sesuai id select kota di HTML
  $kotaSelect.empty().append('<option value="">Memuat kota...</option>');

  if (!provinsiId) {
    $kotaSelect.html('<option value="">-- Pilih Kota --</option>');
    return;
  }

  $.get('intern/ajax/kota', { provinsi_id: provinsiId })
    .done(function (data) {
      $kotaSelect.empty().append('<option value="">-- Pilih Kota --</option>');
      data.forEach(function (kota) {
        const selected = kota_id == selectedCityId ? 'selected' : '';
        $kotaSelect.append(`<option value="${kota_id}" ${selected}>${kota_nama}</option>`);
      });
    })
    .fail(function () {
      $kotaSelect.empty().append('<option value="">Gagal memuat kota</option>');
    });
}

$('#province_id').on('change', function () {
  loadKota($(this).val());
});

// Load on page load (misalnya untuk edit data)
const initialProvinceId = $('#province_id').val();
const initialCityId = @json(old('city_id', $user->city_id ?? null));
if (initialProvinceId) {
  loadKota(initialProvinceId, initialCityId);
}

$('#form-preferences').on('submit', function (e) {
  e.preventDefault();
  $('#error-province, #error-city').text('');
  $('#alertPreferences').hide();

  $.post($(this).attr('action'), $(this).serialize())
    .done(res => {
      $('#alertPreferences')
        .removeClass('alert-danger')
        .addClass('alert-success')
        .text(res.message)
        .show();
    })
    .fail(xhr => {
      if (xhr.status === 422) {
        const errs = xhr.responseJSON.errors;
        if (errs.province_id) $('#error-province').text(errs.province_id[0]);
        if (errs.city_id) $('#error-city').text(errs.city_id[0]);
      } else {
        $('#alertPreferences')
          .removeClass('alert-success')
          .addClass('alert-danger')
          .text('Terjadi kesalahan pada server.')
          .show();
      }
    });
});
</script>
@endpush
