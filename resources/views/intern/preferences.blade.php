<div id="form-preferences-container" style="display: block;">
  <form action="{{ url('/intern/preferences') }}" method="POST" id="form-preferences">
    @csrf

    <div class="mb-3">
      <label for="province" class="form-label">Preferensi Provinsi Magang</label>
      <select name="province" id="province" class="form-control" required>
        <option value="">-- Pilih Provinsi --</option>
        <option value="DKI Jakarta" {{ old('province', $user->province ?? '') == 'DKI Jakarta' ? 'selected' : '' }}>DKI Jakarta</option>
        <option value="Jawa Barat" {{ old('province', $user->province ?? '') == 'Jawa Barat' ? 'selected' : '' }}>Jawa Barat</option>
        <option value="Jawa Timur" {{ old('province', $user->province ?? '') == 'Jawa Timur' ? 'selected' : '' }}>Jawa Timur</option>
        <option value="Jawa Tengah" {{ old('province', $user->province ?? '') == 'Jawa Tengah' ? 'selected' : '' }}>Jawa Tengah</option>
      </select>
      <small id="error-province" class="text-danger"></small>
    </div>

    <div class="mb-3">
      <label for="city" class="form-label">Preferensi Kota Magang</label>
      <select name="city" id="city" class="form-control" required>
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
  $(function () {

    function fillCities(prov, selectedCity = null) {
      const $c = $('#city').empty().append('<option value="">-- Pilih Kota --</option>');
      let arr = [];

      if (prov === 'DKI Jakarta') {
        arr = ['Jakarta Pusat', 'Jakarta Selatan', 'Jakarta Barat', 'Jakarta Timur', 'Jakarta Utara'];
      } else if (prov === 'Jawa Barat') {
        arr = ['Bandung', 'Bekasi', 'Bogor', 'Depok'];
      } else if (prov === 'Jawa Timur') {
        arr = ['Surabaya', 'Malang', 'Sidoarjo', 'Kediri'];
      } else if (prov === 'Jawa Tengah') {
        arr = ['Semarang', 'Surakarta', 'Magelang', 'Pekalongan'];
      }

      arr.forEach(k => {
        const sel = (k === selectedCity) ? ' selected' : '';
        $c.append(`<option value="${k}"${sel}>${k}</option>`);
      });
    }

    $('#province').on('change', function () {
      fillCities($(this).val());
      $('#error-province, #error-city').text('');
    });

    const initProv = $('#province').val();
    const oldCity = @json(old('city', $user->city ?? null));
    if (initProv) fillCities(initProv, oldCity);

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
            if (errs.province) $('#error-province').text(errs.province[0]);
            if (errs.city) $('#error-city').text(errs.city[0]);
          } else {
            $('#alertPreferences')
              .removeClass('alert-success')
              .addClass('alert-danger')
              .text('Terjadi kesalahan pada server.')
              .show();
          }
        });
    });

  });
</script>
@endpush
