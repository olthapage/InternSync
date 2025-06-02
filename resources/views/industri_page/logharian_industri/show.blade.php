show.blade industry 

@empty($logharian)
<div class="modal-dialog modal-xl" style="max-width:60%;">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">Kesalahan</h5>
        </div>
        <div class="modal-body">
            <div class="alert alert-danger">
                <h5><i class="icon fas fa-ban"></i> Data Log Harian tidak ditemukan!</h5>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
        </div>
    </div>
</div>
@else
<div class="modal-dialog modal-xl" style="max-width:90%;">
    <div class="modal-content">
        <div class="modal-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="modal-title">Detail Log Harian</h5>
            <button type="button" class="btn-close" onclick="$('#myModal').modal('hide')" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <table class="table table-bordered mb-4">
                <tbody>
                    <tr>
                        <th style="width: 20%;">ID Log</th>
                        <td>{{ $logharian->logHarian_id }}</td>
                    </tr>
                    <tr>
                        <th>Mahasiswa</th>
                        <td>{{ $logharian->mahasiswa->nama_lengkap ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Tanggal Log</th>
                        <td>{{ $logharian->tanggal }}</td>
                    </tr>
                    <tr>
                        <th>Lokasi</th>
                        <td>{{ $logharian->lokasi_kegiatan->kota_nama ?? '-' }}</td>
                    </tr>
                    <tr>
                        <th>Dibuat pada</th>
                        <td>{{ $logharian->created_at }}</td>
                    </tr>
                    <tr>
                        <th>Diupdate pada</th>
                        <td>{{ $logharian->updated_at }}</td>
                    </tr>
                </tbody>
            </table>

            <h6 class="fw-bold mb-3">Daftar Aktivitas</h6>
            <div class="table-responsive">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr class="text-center">
                            <th>Isi</th>
                            <th>Tanggal Aktivitas</th>
                            <th>Lokasi</th>
                            <th>Approval Dosen</th>
                            <th>Approval Industri</th>
                            <th>Catatan Dosen</th>
                            <th>Catatan Industri</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logharian->detail as $detail)
                            <tr>
                                <td>{{ $detail->isi }}</td>
                                <td class="text-center">{{ $detail->tanggal_kegiatan }}</td>
                                <td class="text-center">{{ $detail->lokasi ?? '-' }}</td>
                                <td class="text-center">
                                    @switch($detail->status_approval_dosen)
                                        @case('disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                            @break
                                        @case('ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                            @break
                                        @default
                                            <span class="badge bg-warning text-dark">Pending</span>
                                    @endswitch
                                </td>
                                <td class="text-center">
                                    @switch($detail->status_approval_industri)
                                        @case('disetujui')
                                            <span class="badge bg-success">Disetujui</span>
                                            @break
                                        @case('ditolak')
                                            <span class="badge bg-danger">Ditolak</span>
                                            @break
                                        @default
                                            <span class="badge bg-warning text-dark">Pending</span>
                                    @endswitch
                                </td>
                                <td>{{ $detail->catatan_dosen ?? '-' }}</td>
                                <td>{{ $detail->catatan_industri ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted">Belum ada aktivitas tercatat.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button id="btnApprovalIndustri" type="button" class="btn btn-primary">Isi Form Approval Industri</button>
            <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
        </div>
    </div>
</div>

<script>
document.getElementById('btnApprovalIndustri').addEventListener('click', function () {
    $('#myModal').modal('hide');

    Swal.fire({
        title: 'Form Approval Industri',
        html: `
            <div class="mb-2">
                <select id="swal-status" class="form-select">
                    <option value="">-- Pilih Status --</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="pending">Pending</option>
                </select>
            </div>
            <textarea id="swal-catatan" class="form-control" rows="4" placeholder="Masukkan catatan atau feedback..."></textarea>
        `,
        showCancelButton: true,
        confirmButtonText: 'Kirim',
        cancelButtonText: 'Batal',
        focusConfirm: false,
        didOpen: () => {
            document.getElementById('swal-status').focus();
        },
        preConfirm: () => {
            const status = document.getElementById('swal-status').value;
            const catatan = document.getElementById('swal-catatan').value;

            if (!status) {
                Swal.showValidationMessage('Status harus dipilih');
                return false;
            }

            return { status, catatan };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const data = {
                _token: '{{ csrf_token() }}',
                status: result.value.status,
                catatan: result.value.catatan,
                logHarianId: '{{ $logharian->logHarian_id }}'
            };

            fetch('{{ route("logharian_industri.approval") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': data._token
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Berhasil!', res.message, 'success').then(() => {
                        location.reload();
                    });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Terjadi kesalahan!', 'Coba lagi nanti.', 'error');
            });
        }
    });
});
</script>
@endempty