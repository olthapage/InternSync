@extends('layouts.template')
@section('content')
    <div class="card card-outline card-primary">
        <div class="card-body text-sm">
            <h2 class="mb-4">Daftar Lowongan Magang</h2>
            <form method="GET" class="row mb-4">
                <div class="col-md-4">
                    <label for="lokasi">Lokasi (Kota)</label>
                    <select name="lokasi" id="lokasi" class="form-control">
                        <option value="">-- Semua Lokasi --</option>
                        @foreach ($listKota as $kota)
                            <option value="{{ $kota->kota_id }}"
                                {{ request('lokasi') == $kota->kota_id ? 'selected' : '' }}>
                                {{ $kota->kota_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="jenis">Jenis (Kategori Skill)</label>
                    <select name="jenis" id="jenis" class="form-control">
                        <option value="">-- Semua Jenis --</option>
                        @foreach ($listKategori as $kategori)
                            <option value="{{ $kategori->kategori_skill_id }}"
                                {{ request('jenis') == $kategori->kategori_skill_id ? 'selected' : '' }}>
                                {{ $kategori->kategori_nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                </div>
            </form>
            @if ($lowongan->isEmpty())
                <p>Belum ada data lowongan.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-items-center mb-0 text-center">
                        <thead>
                            <tr>
                                <th class="text-start">Industri</th>
                                <th>Jenis</th>
                                <th>Lowongan</th>
                                <th>Slot Tersedia</th>
                                <th>Periode</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($lowongan as $row)
                                <tr>
                                    <td class="text-start">
                                        <div class="d-flex px-2 py-1">
                                            <div>
                                                <img src="{{ asset('storage/foto/default-profile.png') }}"
                                                    class="avatar avatar-sm me-3" alt="logo industri">
                                            </div>
                                            <div class="d-flex flex-column justify-content-center">
                                                <h6 class="mb-0 text-sm">{{ $row->industri->industri_nama }}</h6>
                                                <p class="text-xs text-secondary mb-0">{{ $row->industri->kota->kota_nama }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $row->kategoriSkill->kategori_nama }}</td>
                                    <td>{{ $row->judul_lowongan }}</td>
                                    <td>{{ $row->slotTersedia() }}</td>
                                    <td>
                                        {{ \Carbon\Carbon::parse($row->tanggal_mulai)->format('d/m/Y') }} -
                                        {{ \Carbon\Carbon::parse($row->tanggal_selesai)->format('d/m/Y') }}
                                    </td>
                                    <td class="text-end">
                                        <button
                                            onclick="modalAction('{{ route('mahasiswa.lowongan.show', $row->lowongan_id) }}')"
                                            class="btn btn-warning btn-sm">Detail</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
    <div id="myModal" class="modal fade animate shake" tabindex="-1" role="dialog" data-backdrop="static"
        data-keyboard="false" data-width="75%" aria-hidden="true"></div>
@endsection

@push('js')
    <script>
        function modalAction(url = '') {
            $('#myModal').load(url, function() {
                $('#myModal').modal('show');
            });
        }
    </script>
@endpush
