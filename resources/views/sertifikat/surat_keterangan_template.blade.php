<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Surat Keterangan Penyelesaian Magang - {{ $mahasiswa->nama_lengkap }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
        }
        .container {
            width: 90%;
            margin: 0 auto;
        }
        .text-center {
            text-align: center;
        }
        .text-bold {
            font-weight: bold;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid black;
            padding-bottom: 10px;
        }
        .header h3, .header h4 {
            margin: 0;
        }
        .content {
            margin-top: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table.identitas td {
            padding: 2px 0;
            vertical-align: top;
        }
        table.identitas td:first-child {
            width: 150px;
        }
        .signature-section {
            margin-top: 60px;
            width: 100%;
            /* Pastikan tidak ada float yang mengganggu */
            overflow: hidden;
        }
        .signature {
            width: 45%;
            float: right;
            text-align: left;
        }
        .signature .signature-space {
            height: 80px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- Ganti dengan path logo universitas Anda --}}
            <img src="{{ public_path('assets/logo_polinema.png') }}" alt="logo" style="width: 60px; float: left;">
            <h3>POLITEKNIK NEGERI MALANG</h3>
            <h4>JURUSAN TEKNOLOGI INFORMASI</h4>
            <p style="font-size: 10pt; margin: 0;">Jl. Soekarno Hatta No.9, Jatimulyo, Kec. Lowokwaru, Kota Malang, Jawa Timur 65141</p>
        </div>

        <div class="text-center">
            <h4 style="text-decoration: underline; margin-bottom: 5px;">SURAT KETERANGAN PENYELESAIAN MAGANG</h4>
            <span>Nomor: {{ $nomor_surat }}</span>
        </div>

        <div class="content">
            <p>Yang bertanda tangan di bawah ini, Dosen Pembimbing Lapangan Program Magang dari Politeknik Negeri Malang, menerangkan bahwa:</p>

            <table class="identitas">
                <tr>
                    <td>Nama Lengkap</td>
                    <td>: {{ $mahasiswa->nama_lengkap }}</td>
                </tr>
                <tr>
                    <td>NIM</td>
                    <td>: {{ $mahasiswa->nim }}</td>
                </tr>
                <tr>
                    <td>Program Studi</td>
                    <td>: {{ $mahasiswa->prodi->nama_prodi ?? 'Data tidak tersedia' }}</td>
                </tr>
            </table>

            <p>
                telah melaksanakan dan menyelesaikan dengan baik program magang kerja pada:
            </p>

            <table class="identitas">
                <tr>
                    <td>Perusahaan/Industri</td>
                    <td>: {{ $industri->industri_nama }}</td>
                </tr>
                 <tr>
                    <td>Posisi</td>
                    <td>: {{ $lowongan->judul_lowongan }}</td>
                </tr>
                <tr>
                    <td>Periode Pelaksanaan</td>
                    <td>: {{ \Carbon\Carbon::parse($pengajuan->tanggal_mulai)->isoFormat('D MMMM YYYY') }} s.d. {{ \Carbon\Carbon::parse($pengajuan->tanggal_selesai)->isoFormat('D MMMM YYYY') }}</td>
                </tr>
            </table>

            <p>
                Surat keterangan ini dibuat sebagai bukti bahwa mahasiswa yang bersangkutan telah memenuhi salah satu syarat kelulusan mata kuliah magang dan dapat dipergunakan sebagaimana mestinya.
            </p>
        </div>

        <table style="width: 100%; margin-top: 60px; border: none !important;">
            <tr>
                {{-- Kolom kosong di kiri, untuk mendorong tanda tangan ke kanan --}}
                <td style="width: 55%; border: none !important;"></td>

                {{-- Kolom tanda tangan di kanan --}}
                <td style="width: 45%; text-align: left; border: none !important;">
                    {{-- Ganti Surabaya dengan kota yang relevan jika perlu --}}
                    <p>Malang, {{ $tanggal_terbit }}</p>
                    <p>Dosen Pembimbing Magang,</p>
                    <div style="height: 80px;">
                        {{-- Spasi untuk tanda tangan --}}
                    </div>
                    <p class="text-bold" style="text-decoration: underline; margin-bottom: 0;">{{ $dosen_pembimbing->nama_lengkap ?? 'Data Dosen Tidak Ada' }}</p>
                    <p style="margin-top: 0;">NIP. {{ $dosen_pembimbing->nip ?? 'NIP tidak tersedia' }}</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
