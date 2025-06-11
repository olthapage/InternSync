<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Log Harian Magang - {{ $mahasiswa->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
        }
        .container {
            width: 95%;
            margin: 0 auto;
        }
        .text-bold {
            font-weight: bold;
        }
        /* Style untuk Kop Surat */
        .kop-surat {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
        }
        .kop-surat h3, .kop-surat h4 {
            margin: 0;
        }
        .kop-surat p {
            font-size: 10pt;
            margin: 5px 0 0 0;
        }
        /* Style untuk Judul Dokumen */
        .document-title {
            text-align: center;
            margin-bottom: 25px;
        }
        .document-title h4 {
            text-decoration: underline;
            font-size: 14pt;
            margin: 0;
        }
        /* Style untuk Tabel Informasi Mahasiswa (tanpa border) */
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 2px 0;
            vertical-align: top;
        }
        .info-table td:first-child {
            width: 220px; /* Disesuaikan agar rapi */
        }
        /* Style untuk Tabel Log Harian (dengan border) */
        .log-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .log-table th, .log-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            vertical-align: middle; /* Sesuai kode asli */
        }
        .log-table td {
            height: 50px; /* Sesuai kode asli */
        }
        .log-table .isi-aktivitas {
            text-align: left; /* Sesuai style inline pada kode asli */
        }
    </style>
</head>
<body>
<div class="container">
    <div class="kop-surat">
        {{-- Ganti dengan path logo Anda --}}
        <img src="{{ public_path('assets/logo_polinema.png') }}" alt="logo" style="width: 70px; float: left; margin-top: 5px;">
        <h3 style="font-size: 16pt;">POLITEKNIK NEGERI MALANG</h3>
        <h4 style="font-size: 14pt;">JURUSAN TEKNOLOGI INFORMASI</h4>
        <p>Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141</p>
    </div>

    <div class="document-title">
        <h4>LOG HARIAN MAGANG MAHASISWA</h4>
    </div>

    {{-- STRUKTUR DAN VARIABEL ASLI DIMULAI DARI SINI --}}

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
                    <td class="isi-aktivitas">{!! nl2br(e($detail->isi)) !!}</td>
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
</div>
</body>
</html>
