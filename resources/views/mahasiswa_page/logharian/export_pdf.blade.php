<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Log Harian Magang</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        font-size: 12px;
        margin: 20px;
    }
    .header {
        text-align: center;
        margin-bottom: 20px;
    }
    .header h3 {
        margin: 0;
        font-size: 18px;
    }
    .info-table, .log-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .info-table td:first-child {
        width: 180px;
        padding-right: 5px;
        white-space: nowrap;
    }
    .info-table td:last-child {
        padding-left: 5px;
    }
    .info-table td {
        padding-top: 4px;
        padding-bottom: 4px;
    }
    .log-table th, .log-table td {
        border: 1px solid #000;
        padding: 5px;
        text-align: center;
        vertical-align: middle;
        height: 50px;
    }
    .log-table th {
        background-color: #f2f2f2;
    }
</style>
</head>
<body>
    <div class="header">
        <h3>LOG HARIAN MAGANG MAHASISWA</h3>
    </div>

    <table class="info-table">
        <tr>
            <td><strong>Nama Mahasiswa</strong></td>
            <td>: {{ $mahasiswa->nama_lengkap }}</td>
        </tr>
        <tr>
            <td><strong>NIM</strong></td>
            <td>: {{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td><strong>Program Studi</strong></td>
            <td>: {{ $mahasiswa->prodi->nama_prodi }}</td>
        </tr>
        <tr>
            <td><strong>Nama Dosen Pembimbing</strong></td>
            <td>: {{ $mahasiswa->dosen->nama_lengkap ?? '-' }}</td>
        </tr>
    </table>

    <table class="log-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 30%;">Isi Aktivitas</th>
                <th style="width: 15%;">Tanggal Aktivitas</th>
                <th style="width: 20%;">Lokasi</th>
                <th style="width: 20%;">TTD DPL</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($logharian as $log)
                @foreach($log->detail as $detail)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td style="text-align:left; vertical-align: middle;">{!! nl2br(e($detail->isi)) !!}</td>
                        <td>{{ \Carbon\Carbon::parse($detail->tanggal_kegiatan)->format('d-m-Y') }}</td>
                        <td>{{ $detail->lokasi ?? '-' }}</td>
                        <td></td>
                    </tr>
                @endforeach
            @endforeach
            @if($logharian->isEmpty())
                <tr>
                    <td colspan="5" style="text-align:center;">Belum ada aktivitas tercatat.</td>
                </tr>
            @endif
        </tbody>
    </table>
</body>
</html>
