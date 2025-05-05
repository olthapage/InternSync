@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2>Daftar Mahasiswa</h2>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Lengkap</th>
                <th>Email</th>
                <th>NIM</th>
                <th>Program Studi</th>
                <th>Status Magang</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mahasiswa as $index => $mhs)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $mhs->nama_lengkap }}</td>
                <td>{{ $mhs->email }}</td>
                <td>{{ $mhs->nim }}</td>
                <td>{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                <td>
                    @if($mhs->status == 1)
                        <span class="badge bg-success">Sudah Magang</span>
                    @else
                        <span class="badge bg-secondary">Belum Magang</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
