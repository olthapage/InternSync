@extends('layouts.authTemplate') {{-- Ganti dengan layout yang kamu pakai (misalnya 'layouts.auth' atau 'layouts.master') --}}

@section('title', 'Pendaftaran Berhasil')

@section('overlay-title', 'Terima Kasih')
@section('overlay-description', 'Data Anda sedang diproses. Silakan tunggu validasi dari admin.')

@section('form-title', 'Pendaftaran Berhasil')

@section('form-content')
    <div class="text-center">
        <div class="mb-4">
            <i class="fas fa-check-circle text-success" style="font-size: 60px;"></i>
        </div>
        <p class="mb-3">
            Terima kasih telah mendaftar ke InternSync.<br>
            Data Anda telah berhasil dikirim dan sedang dalam proses validasi oleh admin.
        </p>
        <a href="{{ route('login') }}" class="btn btn-dark rounded-pill">Kembali ke Halaman Login</a>
    </div>
@endsection

@section('bottom-link')
    <small class="text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="hover-blue">Masuk di sini</a></small>
@endsection
