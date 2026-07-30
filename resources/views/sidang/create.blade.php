@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Ajukan Sidang Tugas Akhir</h2>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                @if($pengajuans->isEmpty())
                    <div class="alert alert-warning border-0 shadow-sm">
                        <h5 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Pendaftaran Sidang Terkunci</h5>
                        <p class="mb-0">Anda belum memenuhi syarat pendaftaran sidang. Pastikan Anda telah:</p>
                        <ul class="mb-0 mt-2">
                            <li>Memiliki Pengajuan Judul yang berstatus <strong>Diterima</strong>.</li>
                            <li>Mencatat Progress Bimbingan dengan tahapan <strong>Final</strong>.</li>
                            <li>Mendapatkan status <strong>Disetujui</strong> pada tahap Final dari <strong>Kedua Dosen Pembimbing</strong>.</li>
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('sidang.index') }}" class="btn btn-outline-secondary me-2">Kembali</a>
                            <a href="{{ route('bimbingan.index') }}" class="btn btn-warning">Cek Progress Bimbingan</a>
                        </div>
                    </div>
                @else
                    <form method="POST" action="{{ route('sidang.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="fw-bold form-label">Judul Tugas Akhir <span class="text-danger">*</span></label>
                            <select name="pengajuan_judul_id" class="form-select" required>
                                <option value="">-- Pilih Judul --</option>
                                @foreach($pengajuans as $p)
                                    <option value="{{ $p->id }}">{{ $p->judul }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label class="fw-bold form-label">Naskah Final (PDF/DOC/DOCX) <span class="text-danger">*</span></label>
                            <input type="file" name="naskah_final" class="form-control" accept=".pdf,.doc,.docx" required>
                            <div class="form-text text-muted"><i class="bi bi-info-circle"></i> Maksimal ukuran file 5MB</div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="fw-bold form-label">Surat Persetujuan Sidang (PDF/DOC/DOCX) <span class="text-danger">*</span></label>
                            <input type="file" name="surat_persetujuan" class="form-control" accept=".pdf,.doc,.docx" required>
                            <div class="form-text text-muted"><i class="bi bi-info-circle"></i> Maksimal ukuran file 2MB</div>
                        </div>
                        
                        <div class="d-flex justify-content-end">
                            <a href="{{ route('sidang.index') }}" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Ajukan Sidang
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection