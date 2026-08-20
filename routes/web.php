<?php
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'reset'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/password/update', [\App\Http\Controllers\AuthController::class, 'updatePassword'])->name('password.update');
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');

    Route::get('pendaftaran-ta', [\App\Http\Controllers\PendaftaranTaController::class, 'create'])->name('pendaftaran-ta.create');
    Route::post('pendaftaran-ta', [\App\Http\Controllers\PendaftaranTaController::class, 'store'])->name('pendaftaran-ta.store');
    
    Route::get('admin/pendaftaran-ta', [\App\Http\Controllers\PendaftaranTaController::class, 'index'])->name('admin.pendaftaran-ta.index');
    Route::patch('admin/pendaftaran-ta/{pendaftaran}/status', [\App\Http\Controllers\PendaftaranTaController::class, 'updateStatus'])->name('admin.pendaftaran-ta.status');

    Route::resource('pengajuan', \App\Http\Controllers\PengajuanJudulController::class);
    Route::post('pengajuan/{pengajuan}/set-pembimbing', [\App\Http\Controllers\PengajuanJudulController::class, 'setPembimbing'])->name('pengajuan.setPembimbing');

    Route::resource('bimbingan', \App\Http\Controllers\ProgressBimbinganController::class);
    Route::post('bimbingan/{bimbingan}/komentar', [\App\Http\Controllers\ProgressBimbinganController::class, 'storeKomentar'])->name('bimbingan.komentar');

    Route::resource('sidang', \App\Http\Controllers\SidangController::class);

    Route::post('mahasiswa/import', [\App\Http\Controllers\MahasiswaController::class, 'import'])->name('mahasiswa.import');
    Route::resource('mahasiswa', \App\Http\Controllers\MahasiswaController::class);
    
    Route::post('dosen/import', [\App\Http\Controllers\DosenController::class, 'import'])->name('dosen.import');
    Route::resource('dosen', \App\Http\Controllers\DosenController::class);
    Route::get('/admin/relasi', [\App\Http\Controllers\AdminController::class, 'relasiDosenMahasiswa'])->name('admin.relasi');
    Route::get('/admin/progress', [\App\Http\Controllers\AdminController::class, 'pantauProgress'])->name('admin.progress');

    Route::get('/laporan', [\App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::post('/laporan/export', [\App\Http\Controllers\LaporanController::class, 'export'])->name('laporan.export');
});