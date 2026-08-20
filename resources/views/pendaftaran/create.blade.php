@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h3 class="fw-bold mb-4">Pendaftaran Syarat Tugas Akhir</h3>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    @if($pendaftaran && $pendaftaran->status == 'disetujui')
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="fw-bold text-success">Pendaftaran Disetujui!</h4>
                            <p class="text-muted">Dokumen prasyarat Anda telah divalidasi oleh Admin.</p>
                            <a href="{{ route('pengajuan.index') }}" class="btn btn-primary mt-3 px-4 rounded-pill">
                                Lanjutkan ke Pengajuan Judul <i class="fas fa-arrow-right ms-2"></i>
                            </a>
                        </div>
                    @elseif($pendaftaran && $pendaftaran->status == 'menunggu')
                        <div class="text-center py-4">
                            <div class="mb-3">
                                <i class="fas fa-clock text-warning" style="font-size: 4rem;"></i>
                            </div>
                            <h4 class="fw-bold text-warning">Menunggu Verifikasi Admin</h4>
                            <p class="text-muted">Dokumen prasyarat Anda sedang diperiksa. Harap tunggu beberapa saat.</p>
                        </div>
                    @else
                        @if($pendaftaran && $pendaftaran->status == 'ditolak')
                            <div class="alert alert-danger mb-4">
                                <strong><i class="fas fa-times-circle me-2"></i>Pendaftaran Ditolak:</strong><br>
                                {{ $pendaftaran->keterangan ?? 'Dokumen tidak valid. Silakan unggah ulang dokumen yang benar.' }}
                            </div>
                        @endif

                        <div class="alert alert-info mb-4">
                            <i class="fas fa-info-circle me-2"></i>
                            Sebelum mengajukan judul Tugas Akhir, Anda wajib mengunggah dokumen prasyarat akademik di bawah ini. Semua file harus berformat <strong>PDF</strong>.
                        </div>

                        <form action="{{ route('pendaftaran-ta.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label fw-bold">1. Upload KRS Terakhir (Aktif)</label>
                                <input type="file" name="file_krs" class="form-control @error('file_krs') is-invalid @enderror" accept="application/pdf" required>
                                <small class="text-muted">Kartu Rencana Studi semester berjalan yang mencantumkan mata kuliah Tugas Akhir/Skripsi.</small>
                                @error('file_krs') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">2. Upload Transkrip Nilai Sementara</label>
                                <input type="file" name="file_transkrip" class="form-control @error('file_transkrip') is-invalid @enderror" accept="application/pdf" required>
                                <small class="text-muted">Transkrip nilai terakhir untuk memverifikasi jumlah minimal SKS telah terpenuhi.</small>
                                @error('file_transkrip') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="mb-4">
                                <label class="form-label fw-bold">3. Upload Bukti Pembayaran / Bebas Keuangan</label>
                                <input type="file" name="file_pembayaran" class="form-control @error('file_pembayaran') is-invalid @enderror" accept="application/pdf" required>
                                <small class="text-muted">Bukti lunas administrasi keuangan semester berjalan atau surat bebas keuangan dari BAAK.</small>
                                @error('file_pembayaran') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>

                            <div class="d-grid mt-5">
                                <button type="submit" class="btn btn-primary py-2 fw-bold rounded-pill">
                                    <i class="fas fa-paper-plane me-2"></i> Kirim Dokumen Pendaftaran
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
