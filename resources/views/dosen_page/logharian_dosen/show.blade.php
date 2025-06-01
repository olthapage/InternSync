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
            <table class="table table-bordered table-sm mb-3">
                <tr><th>ID Log</th>         <td>{{ $logharian->logHarian_id }}</td></tr>
                <tr><th>Mahasiswa</th>     <td>{{ $logharian->mahasiswa->nama_lengkap ?? '-' }}</td></tr>
                <tr><th>Tanggal Log</th>   <td>{{ $logharian->tanggal }}</td></tr>
                <tr><th>Lokasi</th>        <td>{{ $logharian->lokasi_kegiatan->kota_nama ?? '-' }}</td></tr>
                <tr><th>Dibuat pada</th>   <td>{{ $logharian->created_at }}</td></tr>
                <tr><th>Diupdate pada</th> <td>{{ $logharian->updated_at }}</td></tr>
            </table>

            <h6 class="fw-bold">Daftar Aktivitas</h6>
            <table class="table table-bordered table-striped table-hover table-sm">
                <thead>
                    <tr>
                        <th style="width:20%;">Isi</th>
                        <th style="width:15%;">Tanggal Aktivitas</th> 
                        <th style="width:15%;">Lokasi</th>
                        <th style="width:15%;">Approval Dosen</th>
                        <th style="width:15%;">Approval Industri</th>
                        <th style="width:10%;">Catatan Dosen</th>
                        <th style="width:10%;">Catatan Industri</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logharian->detail as $detail)
                        <tr>
                           <td>{{ $detail->isi }}</td>
                            <td>{{ $detail->tanggal_kegiatan }}</td> 
                            <td>{{ $detail->lokasi ?? '-' }}</td>
                            <td>
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
                            <td class="status-approval-industri" data-status="{{ $detail->status_approval_industri }}">
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
        <div class="modal-footer">
            <button id="btnApprovalDosen" type="button" class="btn btn-warning">Isi Form Approval Dosen</button>
            
            <button type="button" class="btn btn-secondary" onclick="$('#myModal').modal('hide')">Tutup</button>
        </div>
    </div>
</div>
<script>
document.getElementById('btnApprovalDosen').addEventListener('click', function () {
    let statusIndustri = null;
    const statusCells = document.querySelectorAll('.status-approval-industri');
    if (statusCells.length > 0) {
        statusIndustri = statusCells[0].innerText.trim().toLowerCase();
    }

    if (statusIndustri === 'disetujui') {
        Swal.fire({
            title: 'Form Approval Dosen',
            html:
                `<select id="swal-status" class="form-control mb-3">
                    <option value="">-- Pilih Status --</option>
                    <option value="disetujui">Disetujui</option>
                    <option value="ditolak">Ditolak</option>
                    <option value="pending">Pending</option>
                </select>
                <textarea id="swal-catatan" class="form-control" rows="4" placeholder="Masukkan catatan atau feedback..."></textarea>`,
            showCancelButton: true,
            confirmButtonText: 'Kirim',
            cancelButtonText: 'Batal',
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
                // Kirim via AJAX
                const data = {
                    _token: '{{ csrf_token() }}',
                    status: result.value.status,
                    catatan: result.value.catatan,
                    logHarianId: '{{ $logharian->logHarian_id }}'
                };

                fetch('{{ route("logharian_dosen.approval") }}', {
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
                            $('#myModal').modal('hide');
                            $('#datatable').DataTable().ajax.reload();
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
     } else if (statusIndustri === 'pending') {
        Swal.fire('Menunggu Persetujuan', 'Mohon maaf masih menunggu status disetujui oleh industri.', 'info');
    } else if (statusIndustri === 'ditolak') {
        Swal.fire('Tidak Disetujui', 'Log tidak disetujui oleh industri. Anda tidak dapat mengisi form approval.', 'error');
    } else {
        Swal.fire('Tidak Diketahui', 'Status approval industri tidak diketahui.', 'warning');
    }
});
</script>

@endempty