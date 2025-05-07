@extends('layouts.template')

@section('content')
    <div class="container mt-4">
        <h2>Edit Mahasiswa</h2>
        <form action="{{ route('mahasiswa.update', $mahasiswa->mahasiswa_id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" class="form-control" required
                            value="{{ old('nama_lengkap', $mahasiswa->nama_lengkap ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required
                            value="{{ old('email', $mahasiswa->email ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="nim" class="form-label">NIM</label>
                        <input type="text" name="nim" class="form-control" required
                            value="{{ old('nim', $mahasiswa->nim ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="ipk" class="form-label">IPK</label>
                        <input type="number" step="0.01" name="ipk" class="form-control"
                            value="{{ old('ipk', $mahasiswa->ipk ?? '') }}">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            Password @if (isset($mahasiswa))
                                <small class="text-muted">(Kosongkan jika tidak ingin diubah)</small>
                            @endif
                        </label>
                        <input type="password" name="password" class="form-control"
                            @if (!isset($mahasiswa)) required @endif>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status Magang</label>
                        <select name="status" class="form-select" required>
                            <option value="1" {{ old('status', $mahasiswa->status ?? '') == 1 ? 'selected' : '' }}>
                                Sudah Magang</option>
                            <option value="0" {{ old('status', $mahasiswa->status ?? '') == 0 ? 'selected' : '' }}>
                                Belum Magang</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="prodi_id" class="form-label">Program Studi</label>
                        <select name="prodi_id" class="form-select" required>
                            <option value="">-- Pilih Prodi --</option>
                            @foreach ($prodi as $p)
                                <option value="{{ $p->prodi_id }}"
                                    {{ old('prodi_id', $mahasiswa->prodi_id ?? '') == $p->prodi_id ? 'selected' : '' }}>
                                    {{ $p->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="level_id" class="form-label">Level</label>
                        <select name="level_id" class="form-select" required>
                            <option value="">-- Pilih Level --</option>
                            @foreach ($level as $l)
                                <option value="{{ $l->level_id }}"
                                    {{ old('level_id', $mahasiswa->level_id ?? '') == $l->level_id ? 'selected' : '' }}>
                                    {{ $l->level_nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="dosen_id" class="form-label">Dosen Pembimbing</label>
                        <select name="dosen_id" class="form-select">
                            <option value="">-- Tidak Ada --</option>
                            @foreach ($dosen as $d)
                                <option value="{{ $d->dosen_id }}"
                                    {{ old('dosen_id', $mahasiswa->dosen_id ?? '') == $d->dosen_id ? 'selected' : '' }}>
                                    {{ $d->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>


            <button type="submit" class="btn btn-success mt-3">Update</button>
            <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary mt-3">Kembali</a>
        </form>
    </div>
@endsection
