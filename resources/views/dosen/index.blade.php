@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Daftar Dosen</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Lengkap</th>
                    <th scope="col">Email</th>
                    <th scope="col">NIP</th>
                    <th scope="col">Program Studi</th>
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
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
