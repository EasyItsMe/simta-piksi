@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Detail Progress Bimbingan</h2>
    <div>
        <a href="{{ route('bimbingan.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                @php
                    $badgeClass = ['pending' => 'warning', 'disetujui' => 'success', 'perlu_revisi' => 'danger'][$bimbingan->status] ?? 'secondary';
                    $statusLabel = ucwords(str_replace('_', ' ', $bimbingan->status));
                @endphp
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">{{ $bimbingan->judul_progress }}</h4>
                    <span class="badge bg-{{ $badgeClass }} fs-6">{{ $statusLabel }}</span>
                </div>
                <hr>
                <p><strong>Tahapan:</strong> <span class="badge bg-primary">{{ $bimbingan->tahapan }}</span></p>
                <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($bimbingan->tanggal_bimbingan)->format('d F Y') }}</p>
                <p><strong>Mahasiswa:</strong> {{ $bimbingan->mahasiswa->nama_lengkap }} ({{ $bimbingan->mahasiswa->nim }})</p>
                <p><strong>Catatan Mahasiswa:</strong><br>{!! nl2br(e($bimbingan->catatan_mahasiswa)) !!}</p>
                @if($bimbingan->file_progress)
                    <div class="mt-3">
                        <a href="{{ Storage::url($bimbingan->file_progress) }}" class="btn btn-outline-primary" target="_blank"><i class="bi bi-file-earmark-arrow-down"></i> Download File Progress</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-0">
                <h5 class="fw-bold">Diskusi / Komentar Dosen</h5>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush mb-4">
                    @forelse($bimbingan->komentars as $k)
                        <div class="list-group-item px-0">
                            <strong>{{ $k->dosen->nama_lengkap }}</strong> 
                            <small class="text-muted ms-2">{{ $k->created_at->diffForHumans() }}</small>
                            <p class="mb-0 mt-1">{{ $k->komentar }}</p>
                            @if($k->file_revisi)
                                <div class="mt-2">
                                    <a href="{{ Storage::url($k->file_revisi) }}" class="btn btn-sm btn-outline-primary" target="_blank"><i class="bi bi-paperclip"></i> Unduh File Revisi Dosen</a>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">Belum ada komentar atau tanggapan dari dosen.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        @if(auth()->user()->isDosen())
            <div class="card border-0 shadow-sm sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Berikan Penilaian</h5>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('bimbingan.komentar', $bimbingan) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="fw-bold form-label">Status Progress</label>
                            <select name="status" class="form-select" required>
                                <option value="disetujui" {{ $bimbingan->status == 'disetujui' ? 'selected' : '' }}>Disetujui (ACC)</option>
                                <option value="perlu_revisi" {{ $bimbingan->status == 'perlu_revisi' ? 'selected' : '' }}>Perlu Revisi</option>
                            </select>
                            <div class="form-text text-muted">Status ini akan memperbarui progress bar mahasiswa.</div>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold form-label">Komentar / Revisi</label>
                            <textarea name="komentar" class="form-control" rows="4" placeholder="Tuliskan catatan revisi atau ACC..." required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold form-label">Upload File Revisi (Opsional)</label>
                            <input type="file" name="file_revisi" class="form-control" accept=".pdf,.doc,.docx,.xls,.xlsx">
                            <div class="form-text text-muted">Bisa lampirkan PDF/Word berisikan coretan revisi (Maks 10MB).</div>
                        </div>
                        <button type="submit" class="btn btn-success w-100"><i class="bi bi-save"></i> Simpan Penilaian</button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection