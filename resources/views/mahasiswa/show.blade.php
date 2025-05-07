@extends('layouts.template')

@section('content')
    <div class="container mt-4">
        <h2>Detail Mahasiswa</h2>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{ $mahasiswa->nama_lengkap }}</h5>
                <p class="card-text"><strong>Email:</strong> {{ $mahasiswa->email }}</p>
                <p class="card-text"><strong>NIM:</strong> {{ $mahasiswa->nim }}</p>
                <p class="card-text"><strong>IPK:</strong> {{ $mahasiswa->ipk ?? '-' }}</p>
                <p class="card-text"><strong>Status Magang:</strong>
                    @if ($mahasiswa->status == 1)
                        <span class="badge bg-success">Sudah Magang</span>
                    @else
                        <span class="badge bg-secondary">Belum Magang</span>
                    @endif
                </p>
                <p class="card-text"><strong>Program Studi:</strong> {{ $mahasiswa->prodi->nama_prodi ?? '-' }}</p>
                <p class="card-text"><strong>Dosen Pembimbing:</strong> {{ $mahasiswa->dosen->nama_lengkap ?? '-' }}</p>
                <p class="card-text"><strong>Level:</strong> {{ $mahasiswa->level->level_nama ?? '-' }}</p>
                <p class="card-text"><strong>Preferensi Lokasi:</strong></p>
                <ul>
                    @forelse($mahasiswa->preferensiLokasi as $lokasi)
                        <li>{{ $lokasi->nama_lokasi ?? '-' }}</li>
                    @empty
                        <li>Tidak ada data preferensi lokasi.</li>
                    @endforelse
                </ul>
                <p class="card-text"><strong>Skills:</strong></p>
                <ul>
                    @forelse($mahasiswa->skills as $skill)
                        <li>{{ $skill->nama_skill ?? '-' }}</li>
                    @empty
                        <li>Belum memiliki skill.</li>
                    @endforelse
                </ul>
                <p class="card-text"><strong>Kompetensi:</strong></p>
                <ul>
                    @forelse($mahasiswa->kompetensi as $kompetensi)
                        <li>{{ $kompetensi->nama_kompetensi ?? '-' }} (Nilai: {{ $kompetensi->pivot->nilai }})</li>
                    @empty
                        <li>Belum memiliki kompetensi.</li>
                    @endforelse
                </ul>
            </div>
            <div class="card-footer">
                <a href="{{ route('mahasiswa.edit', $mahasiswa->mahasiswa_id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('mahasiswa.destroy', $mahasiswa->mahasiswa_id) }}" method="POST" class="d-inline"
                    onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger" type="submit">Hapus</button>
                </form>
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
@endsection
