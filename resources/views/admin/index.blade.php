@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Daftar Admin</h2>
    <div class="d-flex justify-content-end mb-3">
        <a href="{{ route('admin.create') }}" class="btn btn-primary">Tambah Admin</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Nama Lengkap</th>
                    <th>Email</th>
                    <th>Level</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admin as $index => $adm)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $adm->nama_lengkap }}</td>
                    <td>{{ $adm->email }}</td>
                    <td>{{ $adm->level->level_nama ?? '-' }}</td>
                    <td class="text-center">
                        <a href="{{ route('admin.show', $adm->user_id) }}" class="btn btn-sm btn-warning">Detail</a>
                        <a href="{{ route('admin.edit', $adm->user_id) }}" class="btn btn-sm btn-warning">Edit</a>
                        <form action="{{ route('admin.destroy', $adm->user_id) }}" method="POST" style="display:inline;">
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
