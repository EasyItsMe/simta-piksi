@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Pantau Progress Bimbingan (Logbook)</h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="25%">Nama Mahasiswa</th>
                        <th width="25%">Judul Tugas Akhir</th>
                        <th width="20%">Dosen Pembimbing</th>
                        <th width="25%">Progress Bimbingan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pengajuans as $index => $p)
                    @php
                        $percentage = $p->getProgressPercentage();
                        $progressColor = $percentage == 100 ? 'success' : ($percentage > 0 ? 'primary' : 'secondary');
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $p->mahasiswa->nama_lengkap }}</strong><br>
                            <span class="badge bg-light text-dark border">{{ $p->mahasiswa->nim }}</span>
                        </td>
                        <td>
                            <strong>{{ Str::limit($p->judul, 60) }}</strong><br>
                            <small class="text-muted">Status: <span class="badge bg-{{ $p->status == 'diterima' ? 'success' : 'warning' }}">{{ ucfirst($p->status) }}</span></small>
                        </td>
                        <td>
                            @if($p->pembimbings->count() > 0)
                                <ul class="list-unstyled mb-0 small">
                                @foreach($p->pembimbings as $pembimbing)
                                    <li><i class="bi bi-person-fill text-muted"></i> {{ $pembimbing->dosen->nama_lengkap }}</li>
                                @endforeach
                                </ul>
                            @else
                                <span class="text-muted fst-italic">Belum ada pembimbing</span>
                            @endif
                        </td>
                        <td>
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="small fw-bold">{{ $percentage }}% Selesai</span>
                                @if($percentage == 100)
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Siap Sidang</span>
                                @endif
                            </div>
                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar bg-{{ $progressColor }} progress-bar-striped {{ $percentage < 100 && $percentage > 0 ? 'progress-bar-animated' : '' }}" 
                                     role="progressbar" 
                                     style="width: {{ $percentage }}%" 
                                     aria-valuenow="{{ $percentage }}" 
                                     aria-valuemin="0" 
                                     aria-valuemax="100">
                                </div>
                            </div>
                            <div class="mt-2 text-end">
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalLogbook{{ $p->id }}">Lihat Logbook</button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modals -->
@foreach($pengajuans as $p)
<div class="modal fade" id="modalLogbook{{ $p->id }}" tabindex="-1" aria-labelledby="modalLogbookLabel{{ $p->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLogbookLabel{{ $p->id }}">Logbook: {{ $p->mahasiswa->nama_lengkap }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Dosen Pembimbing</th>
                                <th>Tahapan & Uraian</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($p->progressBimbingans as $b)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->format('d M Y') }}</td>
                                    <td>{{ $b->dosen ? $b->dosen->nama_lengkap : '-' }}</td>
                                    <td>
                                        <span class="badge bg-secondary mb-1">{{ $b->tahapan }}</span><br>
                                        <strong>{{ $b->judul_progress }}</strong><br>
                                        <small class="text-muted">{{ Str::limit($b->catatan_mahasiswa, 50) }}</small>
                                    </td>
                                    <td>
                                        @php
                                            $statusBadge = [
                                                'pending' => 'warning',
                                                'disetujui' => 'success',
                                                'perlu_revisi' => 'danger'
                                            ][$b->status] ?? 'secondary';
                                        @endphp
                                        <span class="badge bg-{{ $statusBadge }}">{{ ucfirst(str_replace('_', ' ', $b->status)) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('bimbingan.show', $b) }}" class="btn btn-sm btn-primary" target="_blank"><i class="bi bi-search"></i> Detail</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted fst-italic">Belum ada riwayat bimbingan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
