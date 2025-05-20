@extends('layouts.template')
@section('content')
<div class="card card-outline card-primary">
    <div class="card-body text-sm">
        <h2 class="mb-4">Daftar Lowongan Magang</h2>

        <div class="d-flex justify-content-end mb-3">
            <button onclick="modalAction('{{ route('mahasiswa.lowongan.create') }}')" class="btn btn-sm btn-primary">
                + Tambah Lowongan
            </button>
        </div>

        @if ($lowongan->isEmpty())
            <p>Belum ada data lowongan.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-items-center mb-0">
                    <thead>
                        <tr>
                            <th>Lowongan</th>
                            <th>Slot</th>
                            <th>Periode</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($lowongan as $row)
                            <tr>
                                <td>{{ $row->judul_lowongan }}</td>
                                <td>{{ $row->slot }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d/m/Y') }}
                                </td>
                                <td class="text-end">
                                    <button onclick="modalAction('{{ route('mahasiswa.lowongan.edit', $row->lowongan_id) }}')" class="btn btn-warning btn-sm">Edit</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
