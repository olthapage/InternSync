@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Daftar Mahasiswa</h2>

    <!-- Container tombol di bawah judul -->
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary">+ Tambah Mahasiswa</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Lengkap</th>
                    <th scope="col">Email</th>
                    <th scope="col">NIM</th>
                    <th scope="col">Program Studi</th>
                    <th scope="col">Status Magang</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($mahasiswa as $index => $mhs)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $mhs->nama_lengkap }}</td>
                    <td>{{ $mhs->email }}</td>
                    <td class="text-center">{{ $mhs->nim }}</td>
                    <td>{{ $mhs->prodi->nama_prodi ?? '-' }}</td>
                    <td class="text-center">
                        @if($mhs->status == 1)
                            <span class="badge bg-success">Sudah Magang</span>
                        @else
                            <span class="badge bg-secondary">Belum Magang</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a href="{{ route('mahasiswa.show', $mhs->mahasiswa_id) }}" class="btn btn-sm btn-warning">Detail</a>
                        <a href="{{ route('mahasiswa.edit', $mhs->mahasiswa_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('mahasiswa.destroy', $mhs->mahasiswa_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Yakin ingin hapus?')" class="btn btn-sm btn-danger">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
