@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Daftar Dosen</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('dosen.create') }}" class="btn btn-primary">+ Tambah Dosen</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Lengkap</th>
                    <th scope="col">Email</th>
                    <th scope="col">NIP</th>
                    <th scope="col">Program Studi</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dosen as $index => $dsn)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $dsn->nama_lengkap }}</td>
                    <td>{{ $dsn->email }}</td>
                    <td class="text-center">{{ $dsn->nip }}</td>
                    <td>{{ $dsn->prodi->nama_prodi ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('dosen.show', $dsn->dosen_id) }}" class="btn btn-sm btn-warning">Detail</a>
                        <a href="{{ route('dosen.edit', $dsn->dosen_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('dosen.destroy', $dsn->dosen_id) }}" method="POST" style="display:inline;">
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
