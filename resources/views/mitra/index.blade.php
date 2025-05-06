@extends('layouts.template')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Daftar Mitra</h2>
        <a href="{{ route('mitra.create') }}" class="btn btn-warning">Tambah Mitra</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Nama Mitra</th>
                <th>Alamat</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mitras as $mitra)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $mitra->nama }}</td>
                <td>{{ $mitra->alamat }}</td>
                <td>{{ $mitra->status == 1 ? 'Aktif' : 'Nonaktif' }}</td>
                <td>
                    <a href="{{ route('mitra.show', $mitra->id) }}" class="btn btn-info btn-sm">Lihat</a>
                    <a href="{{ route('mitra.edit', $mitra->id) }}" class="btn btn-warning btn-sm">Edit</a>
                    <form action="{{ route('mitra.destroy', $mitra->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
