@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>{{ auth()->user()->isDosen() ? 'Monitoring Progress Mahasiswa' : 'Riwayat Bimbingan (Timeline)' }}</h2>
    @if(auth()->user()->isMahasiswa())
        <div>
            <a href="{{ route('bimbingan.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Upload Progress Baru</a>
        </div>
    @endif
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable" style="width:100%">
                <thead class="table-light">
                    @if(auth()->user()->isDosen())
                    <tr>
                        <th>No</th>
                        <th>Mahasiswa</th>
                        <th>Judul Tugas Akhir</th>
                        <th>Progress</th>
                        <th>Aksi</th>
                    </tr>
                    @else
                    <tr>
                        <th>Tanggal</th>
                        <th>Tahapan</th>
                        <th>Judul Topik</th>
                        <th>Mahasiswa</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                    @endif
                </thead>
                <tbody>
                    @if(auth()->user()->isDosen() && isset($pengajuans))
                        @foreach($pengajuans as $i => $p)
                        @php
                            $progress = $p->getProgressPercentage();
                        @endphp
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                <div class="fw-bold">{{ $p->mahasiswa->nama_lengkap }}</div>
                                <small class="text-muted">{{ $p->mahasiswa->nim }}</small>
                            </td>
                            <td>{{ Str::limit($p->judul_final, 50) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1" style="height: 10px;">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: {{ $progress }}%;" aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <span class="ms-2 fw-bold text-success">{{ $progress }}%</span>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info text-white" data-bs-toggle="modal" data-bs-target="#logbookModal{{ $p->id }}">
                                    <i class="bi bi-journal-text"></i> Lihat Logbook
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    @elseif(isset($bimbingans))
                        @foreach($bimbingans as $b)
                        @php
                            $badgeClass = ['pending' => 'warning', 'disetujui' => 'success', 'perlu_revisi' => 'danger'][$b->status] ?? 'secondary';
                            $statusLabel = ucwords(str_replace('_', ' ', $b->status));
                        @endphp
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($b->tanggal_bimbingan)->format('d M Y') }}</td>
                            <td><span class="badge bg-primary">{{ $b->tahapan }}</span></td>
                            <td>{{ $b->judul_progress }}<br><small class="text-muted">{{ Str::limit($b->pengajuanJudul->judul_final, 30) }}</small></td>
                            <td>{{ $b->mahasiswa->nama_lengkap }}</td>
                            <td><span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span></td>
                            <td>
                                <a href="{{ route('bimbingan.show', $b) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i> Buka Detail</a>
                            </td>
                        </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@if(auth()->user()->isDosen() && isset($pengajuans))
    @foreach($pengajuans as $p)
    <!-- Modal Logbook untuk Dosen -->
    <div class="modal fade" id="logbookModal{{ $p->id }}" tabindex="-1" aria-labelledby="logbookModalLabel{{ $p->id }}" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header bg-light">
                    <div>
                        <h5 class="modal-title fw-bold" id="logbookModalLabel{{ $p->id }}">Logbook: {{ $p->mahasiswa->nama_lengkap }}</h5>
                        <small class="text-muted">{{ $p->judul_final }}</small>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light sticky-top">
                            <tr>
                                <th>Tanggal</th>
                                <th>Tahapan</th>
                                <th>Topik / Catatan</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($p->progressBimbingans as $log)
                                @php
                                    $logBadge = ['pending' => 'warning', 'disetujui' => 'success', 'perlu_revisi' => 'danger'][$log->status] ?? 'secondary';
                                    $logLabel = ucwords(str_replace('_', ' ', $log->status));
                                @endphp
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($log->tanggal_bimbingan)->format('d M Y') }}</td>
                                    <td><span class="badge bg-primary">{{ $log->tahapan }}</span></td>
                                    <td>
                                        <div class="fw-bold">{{ $log->judul_progress }}</div>
                                        <small class="text-muted d-block text-truncate" style="max-width: 300px;">{{ $log->catatan_mahasiswa }}</small>
                                    </td>
                                    <td><span class="badge bg-{{ $logBadge }}">{{ $logLabel }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('bimbingan.show', $log->id) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                            Buka Detail <i class="bi bi-box-arrow-up-right"></i>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada riwayat bimbingan untuk dosen ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection