<div class="modal-content">
    <div class="modal-header">
        <h5 class="modal-title">Preferensi Rekomendasi Magang</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div id="recommendation-result" class="mb-3"></div>

        <form id="form-preferensi">
            @csrf
            {{-- Bagian 1: Preferensi Dinamis --}}
            <h6 class="text-primary">Langkah 1: Tentukan Preferensi Anda</h6>
            <div class="border p-3 rounded mb-4">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="pref_provinsi_id" class="form-label">Pilih Provinsi Lokasi Magang</label>
                        <select name="pref_provinsi_id" id="pref_provinsi_id" class="form-select" required>
                            <option value="">-- Pilih Provinsi --</option>
                            @foreach ($provinsiList as $provinsi)
                                <option value="{{ $provinsi->provinsi_id }}">{{ $provinsi->provinsi_nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="pref_kota_id" class="form-label">Pilih Kota Lokasi Magang</label>
                        <select name="pref_kota_id" id="pref_kota_id" class="form-select" required disabled>
                            <option value="">-- Pilih Provinsi Dahulu --</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="form-label">Pilih Tipe Kerja yang Diinginkan</label>
                    @foreach ($tipeKerjaList as $tipe)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="pref_tipe_kerja[]" value="{{ $tipe->tipe_kerja_id }}" id="pref_tipe_{{ $tipe->tipe_kerja_id }}">
                            <label class="form-check-label" for="pref_tipe_{{ $tipe->tipe_kerja_id }}">{{ $tipe->nama_tipe_kerja }}</label>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Bagian 2: Pembobotan Kriteria (asumsikan sudah ada dari kode sebelumnya) --}}
            <h6 class="text-primary">Langkah 2: Tentukan Tingkat Kepentingan Kriteria</h6>
            <p class="text-muted small">Silakan tentukan tingkat kepentingan untuk setiap kriteria berikut.</p>
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th style="width: 50%;">Kriteria</th>
                        <th>Tingkat Kepentingan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kriteria as $key => $pertanyaan)
                    <tr>
                        <td>
                            <label for="bobot_{{ $key }}" class="form-label fw-normal">{{ $pertanyaan }}</label>
                        </td>
                        <td>
                             <select name="bobot[{{ $key }}]" id="bobot_{{ $key }}" class="form-select" required>
                                <option value="1">Tidak Penting</option>
                                <option value="2">Kurang Penting</option>
                                <option value="3" selected>Netral</option>
                                <option value="4">Penting</option>
                                <option value="5">Sangat Penting</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="text-end">
                <button type="submit" class="btn btn-primary">
                    <span id="submit-text">Dapatkan Rekomendasi</span>
                    <div id="loading-spinner" class="spinner-border spinner-border-sm" role="status" style="display: none;"></div>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // AJAX untuk dropdown kota dinamis
    $('#pref_provinsi_id').on('change', function() {
        var provinsiId = $(this).val();
        var kotaSelect = $('#pref_kota_id');
        kotaSelect.html('<option value="">Memuat...</option>').prop('disabled', true);

        if (provinsiId) {
            $.ajax({
                // Menggunakan metode .replace() yang lebih aman dan sesuai dengan nama route
                url: "{{ route('api.kota_by_provinsi', ['provinsi_id' => 'PLACEHOLDER']) }}".replace('PLACEHOLDER', provinsiId),
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    kotaSelect.html('<option value="">-- Pilih Kota --</option>');
                    // Pastikan 'data' adalah array
                    if(Array.isArray(data)) {
                        $.each(data, function(key, value) {
                            kotaSelect.append(`<option value="${value.kota_id}">${value.kota_nama}</option>`);
                        });
                    }
                    kotaSelect.prop('disabled', false);
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", status, error);
                    kotaSelect.html('<option value="">Gagal memuat data</option>').prop('disabled', false);
                }
            });
        } else {
            kotaSelect.html('<option value="">-- Pilih Provinsi Dahulu --</option>').prop('disabled', true);
        }
    });

    // Submit form preferensi
    $('#form-preferensi').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var url = "{{ route('mahasiswa.rekomendasi.calculate') }}";
        var data = form.serialize();
        var submitButton = form.find('button[type="submit"]');

        $('#submit-text').hide();
        $('#loading-spinner').show();
        submitButton.prop('disabled', true);

        $.ajax({
            type: 'POST',
            url: url,
            data: data,
            success: function(response) {
                $('#recommendation-result').html(response);
                form.hide();
            },
            error: function(xhr) {
                console.error("Calculation Error:", xhr.responseText);
                $('#recommendation-result').html('<div class="alert alert-danger">Terjadi kesalahan saat menghitung rekomendasi. Silakan periksa input Anda dan coba lagi.</div>');
            },
            complete: function() {
                $('#submit-text').show();
                $('#loading-spinner').hide();
                submitButton.prop('disabled', false);
            }
        });
    });
});
</script>
