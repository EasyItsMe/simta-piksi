@extends('layouts.app')
@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        min-height: 100vh;
        background: linear-gradient(135deg, #f8fafc 0%, #eef4ff 100%);
    }
    .register-container {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
        padding: 2rem 1rem;
    }
    .register-card {
        background: white;
        border-radius: 24px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        width: 100%;
        max-width: 800px;
        overflow: hidden;
    }
    .register-header {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        padding: 2rem;
        color: white;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .register-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        animation: pulse 10s infinite alternate ease-in-out;
    }
    .register-body {
        padding: 2.5rem;
    }
    .form-control, .form-select {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.8rem 1rem;
        transition: all 0.3s ease;
    }
    .form-control:focus, .form-select:focus {
        background: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.9rem;
    }
    .section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 1.5rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid #e2e8f0;
    }
    .btn-register {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.8rem;
        font-weight: 600;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
    }
    .btn-register:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(37, 99, 235, 0.4);
    }
</style>

<div class="register-container">
    <div class="register-card">
        <div class="register-header">
            <h2 class="fw-bold mb-1">Registrasi Akun Mahasiswa</h2>
            <p class="mb-0 opacity-75">Sistem Informasi Manajemen Tugas Akhir</p>
        </div>
        <div class="register-body">
            @if($errors->any())
                <div class="alert alert-danger rounded-3 mb-4">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.post') }}" enctype="multipart/form-data">
                @csrf
                
                <h4 class="section-title"><i class="bi bi-person-badge text-primary me-2"></i>Data Mahasiswa</h4>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="form-control" required placeholder="Masukkan nama lengkap">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Nomor Induk Mahasiswa (NIM)</label>
                        <input type="text" name="nim" value="{{ old('nim') }}" class="form-control" required placeholder="Masukkan NIM">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Program Studi</label>
                        <select name="program_studi" class="form-select" required>
                            <option value="">-- Pilih Program Studi --</option>
                            <option value="Teknik Informatika" {{ old('program_studi') == 'Teknik Informatika' ? 'selected' : '' }}>Teknik Informatika</option>
                            <option value="Manajemen Informatika" {{ old('program_studi') == 'Manajemen Informatika' ? 'selected' : '' }}>Manajemen Informatika</option>
                            <option value="Sistem Informasi" {{ old('program_studi') == 'Sistem Informasi' ? 'selected' : '' }}>Sistem Informasi</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email') }}" class="form-control" required placeholder="nama@email.com">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required placeholder="Minimal 6 karakter">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Konfirmasi Password</label>
                        <input type="password" name="password_confirmation" class="form-control" required placeholder="Ketik ulang password">
                    </div>
                </div>

                <h4 class="section-title"><i class="bi bi-file-earmark-pdf text-danger me-2"></i>Syarat Pendaftaran TA (Wajib PDF)</h4>
                <div class="alert alert-info small mb-4">
                    <i class="bi bi-info-circle me-1"></i> Akun Anda memerlukan verifikasi Admin. Silakan unggah dokumen persyaratan di bawah ini untuk mengaktifkan fitur Pengajuan Judul.
                </div>
                
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <label class="form-label">1. KRS Aktif</label>
                        <input type="file" name="file_krs" class="form-control" accept="application/pdf" required>
                        <small class="text-muted d-block mt-1">Berisi mata kuliah Tugas Akhir.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">2. Transkrip Nilai</label>
                        <input type="file" name="file_transkrip" class="form-control" accept="application/pdf" required>
                        <small class="text-muted d-block mt-1">Transkrip nilai sementara terbaru.</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">3. Bukti Pembayaran</label>
                        <input type="file" name="file_pembayaran" class="form-control" accept="application/pdf" required>
                        <small class="text-muted d-block mt-1">Kwitansi/bebas keuangan.</small>
                    </div>
                </div>

                <button type="submit" class="btn btn-register w-100 d-flex justify-content-center align-items-center gap-2">
                    Daftar dan Unggah Berkas <i class="bi bi-cloud-upload"></i>
                </button>

                <div class="mt-4 text-center">
                    <p class="text-muted small">Sudah punya akun? <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">Masuk di sini</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
