<div id="form-preferences-container" style="display: block;">
  <form action="{{ url('/intern/preferences') }}" method="POST" id="form-preferences">
    @csrf
    <div class="mb-3">
      <label for="province_id" class="form-label">Preferensi Provinsi Magang</label>
      <select name="province_id" id="province_id" class="form-control" required>
        <option value="">-- Pilih Provinsi --</option>
        @foreach ($province as $provinsi)
          <option value="{{ $provinsi->provinsi_id }}" {{ old('province_id', $user->province_id ?? '') == $provinsi->provinsi_id ? 'selected' : '' }}>
            {{ $provinsi->provinsi_nama }}
          </option>
        @endforeach
      </select>
      <small id="error-province" class="text-danger"></small>
    </div>

    <div class="mb-3">
      <label for="city_id" class="form-label">Preferensi Kota Magang</label>
      <select name="city_id" id="city_id" class="form-control" required>
        <option value="">-- Pilih Kota --</option>
      </select>
      <small id="error-city" class="text-danger"></small>
    </div>

    <div class="d-flex justify-content-between">
      <button type="submit" class="btn btn-success">Simpan Preferensi</button>
      <button type="button" class="btn btn-secondary" id="btn-close-preferences">Tutup</button>
      <script>
        // Pastikan jQuery sudah dimuat sebelum script ini
        $(document).ready(function() { // Tambahkan document ready untuk memastikan DOM siap
            $('#btn-close-preferences').on('click', function () {
                $('#form-preferences-container').slideUp();
            });
        });
      </script>
    </div>

    <div id="alertPreferences" class="alert mt-3" style="display: none;"></div>
  </form>
</div>

@push('js')
<script>
$(document).ready(function() { // Pastikan DOM siap
  function loadKota(provinsiId, selectedCityId = null) {
    const $kotaSelect = $('#city_id');
    $kotaSelect.empty().append('<option value="">Memuat kota...</option>');

    if (!provinsiId) {
      $kotaSelect.html('<option value="">-- Pilih Provinsi Terlebih Dahulu --</option>'); // Pesan lebih jelas
      $kotaSelect.prop('disabled', true); // Disable jika tidak ada provinsi
      return;
    }
    $kotaSelect.prop('disabled', false); // Enable jika ada provinsi

    // Pastikan URL AJAX benar. Jika route Anda bernama, gunakan route('nama.route')
    $.get("{{ url('intern/ajax/kota') }}", { provinsi_id: provinsiId }) // Gunakan url() atau route() untuk URL
      .done(function (data) {
        $kotaSelect.empty().append('<option value="">-- Pilih Kota --</option>');
        if (data.length === 0) {
            $kotaSelect.append('<option value="">Tidak ada kota di provinsi ini</option>');
            $kotaSelect.prop('disabled', true);
        } else {
            data.forEach(function (kota) {
              // PERBAIKAN UTAMA DI SINI:
              // Akses properti dari objek 'kota'
              const selected = kota.kota_id == selectedCityId ? 'selected' : '';
              $kotaSelect.append(`<option value="${kota.kota_id}" ${selected}>${kota.kota_nama}</option>`);
            });
        }
      })
      .fail(function (jqXHR, textStatus, errorThrown) {
        console.error("Gagal memuat kota:", textStatus, errorThrown);
        $kotaSelect.empty().append('<option value="">Gagal memuat kota</option>');
      });
  }

  $('#province_id').on('change', function () {
    loadKota($(this).val());
  });

  // Load on page load (misalnya untuk edit data)
  const initialProvinceId = $('#province_id').val();
  // Pastikan $user->city_id dilewatkan dengan benar dari controller dan ada
  const initialCityId = @json(old('city_id', $user->city_id ?? null));

  if (initialProvinceId) {
    loadKota(initialProvinceId, initialCityId);
  } else {
    $('#city_id').html('<option value="">-- Pilih Provinsi Terlebih Dahulu --</option>');
    $('#city_id').prop('disabled', true);
  }

  $('#form-preferences').on('submit', function (e) {
    e.preventDefault();
    $('#error-province, #error-city').text('');
    $('#alertPreferences').hide();
    const $submitButton = $(this).find('button[type="submit"]');
    const originalButtonText = $submitButton.text();
    $submitButton.prop('disabled', true).text('Menyimpan...');


    $.post($(this).attr('action'), $(this).serialize())
      .done(res => {
        $('#alertPreferences')
          .removeClass('alert-danger')
          .addClass('alert-success')
          .text(res.message)
          .show();
        // Mungkin Anda ingin menutup form atau melakukan redirect setelah sukses
        // setTimeout(() => {
        //   $('#form-preferences-container').slideUp();
        // }, 2000);
      })
      .fail(xhr => {
        if (xhr.status === 422) {
          const errs = xhr.responseJSON.errors;
          if (errs.province_id) $('#error-province').text(errs.province_id[0]);
          if (errs.city_id) $('#error-city').text(errs.city_id[0]);
          // Tambahkan penanganan untuk error lainnya jika ada
        } else {
          $('#alertPreferences')
            .removeClass('alert-success')
            .addClass('alert-danger')
            .text(xhr.responseJSON.message || 'Terjadi kesalahan pada server.') // Gunakan pesan dari server jika ada
            .show();
        }
      })
      .always(() => {
          $submitButton.prop('disabled', false).text(originalButtonText);
      });
  });
});
</script>
@endpush
