@extends('layouts.template')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Daftar Admin</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-striped table-hover align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Lengkap</th>
                    <th scope="col">Email</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admin as $index => $dsn)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $dsn->nama_lengkap }}</td>
                    <td>{{ $dsn->email }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
