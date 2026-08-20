@extends('layouts.app')
@section('content')
<style>
    body {
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
    .login-container {
        display: flex;
        min-height: 100vh;
        width: 100vw;
    }
    .login-left {
        flex: 1;
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 4rem;
        color: white;
        overflow: hidden;
    }
    .login-left::before {
        content: '';
        position: absolute;
        top: -10%;
        left: -10%;
        width: 50vw;
        height: 50vw;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        animation: pulse 8s infinite alternate ease-in-out;
    }
    .login-left::after {
        content: '';
        position: absolute;
        bottom: -20%;
        right: -10%;
        width: 40vw;
        height: 40vw;
        background: radial-gradient(circle, rgba(255,255,255,0.08) 0%, rgba(255,255,255,0) 70%);
        border-radius: 50%;
        animation: pulse 12s infinite alternate-reverse ease-in-out;
    }
    @keyframes pulse {
        0% { transform: scale(1) translate(0, 0); }
        100% { transform: scale(1.1) translate(20px, 20px); }
    }
    .login-left-content {
        position: relative;
        z-index: 10;
        max-width: 600px;
    }
    .login-logo-circle {
        width: 80px;
        height: 80px;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        margin-bottom: 2rem;
        border: 1px solid rgba(255,255,255,0.3);
        box-shadow: 0 8px 32px rgba(0,0,0,0.1);
    }
    .login-title {
        font-size: clamp(2.5rem, 4vw, 3.5rem);
        font-weight: 800;
        line-height: 1.1;
        margin-bottom: 1.5rem;
        letter-spacing: -0.03em;
    }
    .login-subtitle {
        font-size: 1.2rem;
        font-weight: 300;
        opacity: 0.9;
        line-height: 1.6;
    }
    .login-right {
        width: 100%;
        max-width: 500px;
        background: white;
        display: flex;
        flex-direction: column;
        justify-content: center;
        padding: 4rem;
        box-shadow: -20px 0 50px rgba(0,0,0,0.05);
        position: relative;
        z-index: 20;
    }
    .auth-form-wrapper {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }
    .auth-heading {
        font-size: 1.8rem;
        font-weight: 700;
        color: #0f172a;
        margin-bottom: 0.5rem;
    }
    .auth-subheading {
        color: #64748b;
        margin-bottom: 2.5rem;
        font-size: 0.95rem;
    }
    .form-control {
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 0.8rem 1rem;
        font-size: 0.95rem;
        transition: all 0.3s ease;
    }
    .form-control:focus {
        background: #ffffff;
        border-color: #3b82f6;
        box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
    }
    .form-label {
        font-weight: 600;
        color: #334155;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }
    .btn-login {
        background: linear-gradient(135deg, #2563eb, #1d4ed8);
        color: white;
        border: none;
        border-radius: 12px;
        padding: 0.8rem;
        font-weight: 600;
        font-size: 1rem;
        letter-spacing: 0.02em;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
    }
    .btn-login:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 35px rgba(37, 99, 235, 0.4);
    }
    @media (max-width: 992px) {
        .login-left {
            display: none;
        }
        .login-right {
            max-width: 100%;
            padding: 2rem;
            background: linear-gradient(135deg, #f8fafc 0%, #eef4ff 100%);
        }
        .auth-form-wrapper {
            background: white;
            padding: 2.5rem;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        }
    }
</style>

<div class="login-container">
    <div class="login-left">
        <div class="login-left-content">
            <div class="login-logo-circle">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <h1 class="login-title">Sistem Informasi<br>Manajemen Tugas Akhir</h1>
            <p class="login-subtitle">Platform terpadu untuk memantau progress bimbingan, pendaftaran sidang, dan pengelolaan laporan tugas akhir Politeknik Piksi Input Serang.</p>
        </div>
    </div>
    <div class="login-right">
        <div class="auth-form-wrapper">
            <div class="mb-4 d-lg-none text-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary text-white rounded-circle mb-3" style="width: 64px; height: 64px; font-size: 2rem;">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
                <h2 class="fw-bold text-dark mb-1">SIMTA</h2>
            </div>

            <h3 class="auth-heading">Selamat Datang Kembali 👋</h3>
            <p class="auth-subheading">Silakan masukkan email dan password Anda untuk mengakses sistem.</p>

            @if($errors->any())
                <div class="alert alert-danger rounded-3 border-0 shadow-sm mb-4" role="alert">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $error)
                            <li class="small">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" novalidate>
                @csrf
                <div class="mb-4">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted ps-3">
                            <i class="bi bi-envelope"></i>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" class="form-control border-start-0 ps-1" placeholder="nama@email.com" required autocomplete="email" autofocus>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent border-end-0 text-muted ps-3">
                            <i class="bi bi-lock"></i>
                        </span>
                        <input id="password" type="password" name="password" class="form-control border-start-0 ps-1" placeholder="••••••••" required>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input shadow-none" type="checkbox" name="remember" id="remember">
                        <label class="form-check-label small text-muted" for="remember" style="user-select: none; cursor: pointer;">
                            Ingat sesi saya
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="small text-primary text-decoration-none fw-semibold">Lupa Password?</a>
                    @endif
                </div>

                <button type="submit" class="btn btn-login w-100 d-flex justify-content-center align-items-center gap-2">
                    Masuk ke Sistem <i class="bi bi-arrow-right"></i>
                </button>

                <div class="mt-4 text-center">
                    <p class="text-muted small">Belum punya akun Mahasiswa? <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">Daftar Sekarang</a></p>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection