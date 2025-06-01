<form id="formSpkKriteria-{{ $lowongan->lowongan_id }}"
    action="{{ route('industri.lowongan.spk.calculate', $lowongan->lowongan_id) }}" method="POST">
    @csrf
    <p class="text-muted small">
        Tentukan bobot (tingkat kepentingan relatif) untuk setiap kriteria yang dibutuhkan oleh lowongan ini.
        Bobot adalah persentase dari 0 hingga 100. **Total akumulasi bobot idealnya adalah 100%.
    </p>

    {{-- Penampung untuk pesan error total bobot --}}
    <div id="totalBobotError-{{ $lowongan->lowongan_id }}" class="alert alert-danger py-2"
        style="display: none; font-size: 0.875rem;"></div>
    <div class="mb-2 text-end">
        <span class="fw-bold">Total Bobot Saat Ini: <span
                id="currentTotalBobot-{{ $lowongan->lowongan_id }}">0</span>%</span>
    </div>


    <h6 class="text-dark-blue fw-bold border-bottom pb-2 mb-3">Kriteria Keahlian (Skill)</h6>
    @if ($lowongan->lowonganSkill->isEmpty())
        <div class="alert alert-secondary">Lowongan ini tidak memiliki kriteria skill spesifik. Rekomendasi hanya akan
            didasarkan pada IPK jika diaktifkan.</div>
    @else
        <div class="table-responsive mb-3">
            <table class="table table-sm table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th style="width: 40%;">Keahlian yang Dibutuhkan</th>
                        <th class="text-center" style="width: 25%;">Target Level Kompetensi</th>
                        <th class="text-center" style="width: 35%;">Bobot Kriteria (%) <span
                                class="text-danger">*</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lowongan->lowonganSkill as $index => $reqSkill)
                        <tr>
                            <td>{{ optional($reqSkill->skill)->skill_nama ?? 'Skill tidak ada' }}</td>
                            <td class="text-center"><span class="badge bg-info">{{ $reqSkill->level_kompetensi }}</span>
                            </td>
                            <td>
                                <input type="number"
                                    class="form-control form-control-sm spk-bobot-input @error('bobot_skill.' . $reqSkill->skill_id) is-invalid @enderror"
                                    name="bobot_skill[{{ $reqSkill->skill_id }}]"
                                    value="{{ old('bobot_skill.' . $reqSkill->skill_id, $reqSkill->bobot ?? 30) }}"
                                    min="0" max="100" step="1" required
                                    aria-label="Bobot untuk {{ optional($reqSkill->skill)->skill_nama }}">
                                @error('bobot_skill.' . $reqSkill->skill_id)
                                    <div class="invalid-feedback text-xs">{{ $message }}</div>
                                @enderror
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <h6 class="text-dark-blue fw-bold border-bottom pb-2 mb-3 mt-4">Kriteria Tambahan</h6>
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input spk-bobot-toggle" id="gunakan_ipk-{{ $lowongan->lowongan_id }}"
            name="gunakan_ipk" value="1" {{ old('gunakan_ipk', true) ? 'checked' : '' }}
            onchange="toggleIpkWeightDynamic(this, '{{ $lowongan->lowongan_id }}')">
        <label class="form-check-label" for="gunakan_ipk-{{ $lowongan->lowongan_id }}">Sertakan Nilai Akademik (IPK)
            sebagai Kriteria</label>
    </div>

    <div class="mb-3" id="bobot_ipk_div-{{ $lowongan->lowongan_id }}"
        style="{{ old('gunakan_ipk', true) ? '' : 'display: none;' }}">
        <label for="bobot_ipk-{{ $lowongan->lowongan_id }}" class="form-label">Bobot untuk IPK (%): <span
                class="text-danger" id="ipk_required_star-{{ $lowongan->lowongan_id }}"
                style="{{ old('gunakan_ipk', true) ? 'display:inline;' : 'display:none;' }}">*</span></label>
        <input type="number"
            class="form-control form-control-sm spk-bobot-input @error('bobot_ipk') is-invalid @enderror"
            id="bobot_ipk-{{ $lowongan->lowongan_id }}" name="bobot_ipk" value="{{ old('bobot_ipk', 20) }}"
            min="0" max="100" step="1" aria-label="Bobot untuk IPK"
            {{ old('gunakan_ipk', true) ? 'required' : '' }}>
        @error('bobot_ipk')
            <div class="invalid-feedback text-xs">{{ $message }}</div>
        @enderror
    </div>

    {{-- Script untuk toggle IPK dan perhitungan total bobot --}}
    <script>
        (function() { // IIFE untuk scope lokal
            const lowonganId = '{{ $lowongan->lowongan_id }}';
            const formSpk = document.getElementById('formSpkKriteria-' + lowonganId);
            if (!formSpk) return;

            const bobotInputs = formSpk.querySelectorAll('.spk-bobot-input');
            const checkboxIpk = document.getElementById('gunakan_ipk-' + lowonganId);
            const bobotIpkInput = document.getElementById('bobot_ipk-' + lowonganId);
            const bobotIpkDiv = document.getElementById('bobot_ipk_div-' + lowonganId);
            const requiredStarIpk = document.getElementById('ipk_required_star-' + lowonganId);
            const currentTotalBobotSpan = document.getElementById('currentTotalBobot-' + lowonganId);
            const totalBobotErrorDiv = document.getElementById('totalBobotError-' + lowonganId);
            const submitButton = formSpk.querySelector('button[type="submit"]');

            function calculateAndDisplayTotalBobot() {
                if (!currentTotalBobotSpan || !totalBobotErrorDiv) return;

                let totalBobot = 0;
                bobotInputs.forEach(input => {
                    // Hanya hitung input yang visible dan bagian dari form yang aktif
                    if (input.offsetParent !== null) { // Cek apakah elemen visible
                        // Khusus untuk bobot IPK, hanya hitung jika checkbox 'gunakan_ipk' tercentang
                        if (input.name === 'bobot_ipk' && checkboxIpk && !checkboxIpk.checked) {
                            // jangan lakukan apa-apa, bobot ipk tidak dihitung
                        } else {
                            totalBobot += parseFloat(input.value) || 0;
                        }
                    }
                });

                currentTotalBobotSpan.textContent = totalBobot;

                if (totalBobot > 100) {
                    totalBobotErrorDiv.textContent = 'Peringatan: Total bobot melebihi 100% (' + totalBobot +
                        '%). Ini akan dinormalisasi oleh sistem, namun disarankan total bobot adalah 100%.';
                    totalBobotErrorDiv.style.display = 'block';
                    totalBobotErrorDiv.classList.remove('alert-success');
                    totalBobotErrorDiv.classList.add('alert-danger');
                    if (submitButton) submitButton.classList.add('btn-warning'); // Beri tanda di tombol
                } else if (totalBobot < 100 && totalBobot > 0) {
                    totalBobotErrorDiv.textContent = 'Info: Total bobot saat ini adalah ' + totalBobot +
                        '%. Idealnya adalah 100% untuk pembagian yang presisi, namun sistem akan menormalisasi bobot ini.';
                    totalBobotErrorDiv.style.display = 'block';
                    totalBobotErrorDiv.classList.remove('alert-danger');
                    totalBobotErrorDiv.classList.add('alert-info'); // Ganti jadi alert-info
                    if (submitButton) submitButton.classList.remove('btn-warning');
                } else if (totalBobot === 100) {
                    totalBobotErrorDiv.textContent = 'Total bobot sudah 100%. Sempurna!';
                    totalBobotErrorDiv.style.display = 'block';
                    totalBobotErrorDiv.classList.remove('alert-danger', 'alert-info');
                    totalBobotErrorDiv.classList.add('alert-success');
                    if (submitButton) submitButton.classList.remove('btn-warning');
                } else {
                    totalBobotErrorDiv.style.display = 'none';
                    if (submitButton) submitButton.classList.remove('btn-warning');
                }
            }

            // Fungsi toggleIpkWeightDynamic yang global (sebaiknya di JS utama show.blade.php)
            // Namun karena ini partial, kita definisikan di sini agar bisa dipanggil `onchange`
            // Jika sudah ada global, pastikan tidak ada konflik nama.
            window['toggleIpkWeightDynamic'] = function(cb,
            lwId) { // Pastikan unik jika ada beberapa modal di satu halaman
                if (lwId !== lowonganId) return; // Hanya untuk modal ini

                const bobotDiv = document.getElementById('bobot_ipk_div-' + lwId);
                const bobotInputEl = document.getElementById('bobot_ipk-' + lwId);
                const starEl = document.getElementById('ipk_required_star-' + lwId);

                if (bobotDiv && bobotInputEl && starEl) {
                    if (cb.checked) {
                        bobotDiv.style.display = 'block';
                        bobotInputEl.required = true;
                        starEl.style.display = 'inline';
                    } else {
                        bobotDiv.style.display = 'none';
                        bobotInputEl.value = ''; // Kosongkan nilai jika tidak dicentang
                        bobotInputEl.required = false;
                        starEl.style.display = 'none';
                    }
                    calculateAndDisplayTotalBobot(); // Hitung ulang total bobot
                }
            };

            bobotInputs.forEach(input => {
                input.addEventListener('input', calculateAndDisplayTotalBobot);
                input.addEventListener('change',
                calculateAndDisplayTotalBobot); // Untuk input type number dengan panah
            });

            if (checkboxIpk) {
                checkboxIpk.addEventListener('change', function() {
                    // Fungsi global sudah dipanggil oleh onchange HTML, panggil calculate saja
                    calculateAndDisplayTotalBobot();
                });
                // Panggil sekali saat load untuk inisialisasi
                toggleIpkWeightDynamic(checkboxIpk, lowonganId);
            }

            // Panggil kalkulasi awal saat form dimuat
            calculateAndDisplayTotalBobot();

        })(); // Akhir IIFE
    </script>

    <div class="d-flex justify-content-end mt-4">
        <button type="submit" class="btn btn-success">
            <i class="fas fa-calculator me-1"></i> Hitung Rekomendasi
        </button>
    </div>

    <div id="spkResultArea-{{ $lowongan->lowongan_id }}" class="mt-4">
        {{-- Hasil perhitungan akan ditampilkan di sini --}}
    </div>

    <hr class="my-4">
    <div class="alert alert-secondary small" role="alert" style="background-color: #f8f9fa; border-color: #e9ecef;">
        <p class="mb-1">Sistem Pendukung Keputusan (SPK) dengan metode EDAS ini bertujuan untuk memberikan rekomendasi
            peringkat pendaftar berdasarkan kriteria dan bobot yang Anda tentukan. Rekomendasi ini bersifat sebagai alat
            bantu.</p>

        <p class="mb-0">Keputusan akhir penerimaan mahasiswa tetap sepenuhnya menjadi tanggung jawab dan
            kewenangan pihak industri berdasarkan pertimbangan menyeluruh.</p>
    </div>
</form>
