@extends('layouts.app')
@section('content')
<div class="page-shell">
    <div class="page-hero">
        <div>
            <div class="page-chip"><i class="bi bi-speedometer2"></i> Dashboard</div>
            <h2 class="page-title mb-1">Dashboard {{ auth()->user()->role->nama_role }}</h2>
            <p class="page-intro">Selamat datang kembali, {{ auth()->user()->name }}! Berikut ringkasan aktivitas sistem hari ini.</p>
        </div>
        <div class="text-muted small fw-semibold">Update {{ now()->translatedFormat('d F Y') }}</div>
    </div>

    @if(auth()->user()->isAdmin())
    <!-- ADMIN -->
    <div class="row g-4 mb-4">
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold text-white-50">Total Mahasiswa</h6>
                    <div class="stat-value">{{ $data['total_mahasiswa'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-success text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold text-white-50">Total Dosen</h6>
                    <div class="stat-value">{{ $data['total_dosen'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-warning text-dark h-100">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold text-dark-50">Pengajuan Baru</h6>
                    <div class="stat-value">{{ $data['pengajuan_baru'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card stat-card bg-info text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold text-white-50">Sidang Minggu Ini</h6>
                    <div class="stat-value">{{ $data['sidang_minggu_ini'] }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white pt-4 pb-0">
                    <h5 class="fw-bold">Statistik Pengajuan TA</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 250px; width: 100%;">
                        <canvas id="pengajuanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white pt-4 pb-0">
                    <h5 class="fw-bold">Statistik Kelulusan Sidang</h5>
                </div>
                <div class="card-body">
                    <div style="position: relative; height: 250px; width: 100%;">
                        <canvas id="kelulusanChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white pt-4 pb-0">
            <h5 class="fw-bold">Aktivitas Terbaru</h5>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-flush">
                @forelse($data['aktivitas'] as $log)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            <span class="fw-bold">{{ $log->user->name }}</span>
                            <span class="text-muted ms-2">{{ $log->aktivitas }}</span>
                        </div>
                        <span class="badge bg-light text-dark">{{ $log->created_at->diffForHumans() }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">Belum ada aktivitas.</li>
                @endforelse
            </ul>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx1 = document.getElementById('pengajuanChart')?.getContext('2d');
            if (ctx1) {
                new Chart(ctx1, {
                    type: 'doughnut',
                    data: {
                        labels: ['Diajukan', 'Diterima', 'Ditolak', 'Direvisi'],
                        datasets: [{
                            data: [{{ $data['stats_ta']['diajukan'] }}, {{ $data['stats_ta']['diterima'] }}, {{ $data['stats_ta']['ditolak'] }}, {{ $data['stats_ta']['direvisi'] }}],
                            backgroundColor: ['#ffc107', '#198754', '#dc3545', '#0dcaf0']
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }

            const ctx2 = document.getElementById('kelulusanChart')?.getContext('2d');
            if (ctx2) {
                new Chart(ctx2, {
                    type: 'bar',
                    data: {
                        labels: ['Lulus', 'Revisi', 'Selesai', 'Menunggu'],
                        datasets: [{
                            label: 'Jumlah Mahasiswa',
                            data: [{{ $data['stats_kelulusan']['lulus'] }}, {{ $data['stats_kelulusan']['revisi'] }}, {{ $data['stats_kelulusan']['selesai'] }}, {{ $data['stats_kelulusan']['menunggu'] }}],
                            backgroundColor: ['#198754', '#ffc107', '#dc3545', '#6c757d']
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }
        });
    </script>
    @endpush

    @elseif(auth()->user()->isDosen())
    <!-- DOSEN -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6">
            <div class="card stat-card bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold text-white-50">Mahasiswa Bimbingan</h6>
                    <div class="stat-value">{{ $data['jml_mahasiswa_bimbingan'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="card stat-card bg-warning text-dark h-100">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold text-dark-50">Revisi Menunggu ACC</h6>
                    <div class="stat-value">{{ count($data['daftar_revisi']) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card mb-4 h-100">
                <div class="card-header bg-white pt-4 pb-0"><h5 class="fw-bold">Progress Bimbingan Terbaru</h5></div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Mahasiswa</th>
                                    <th>Tanggal</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data['progress_mahasiswa'] as $p)
                                    <tr>
                                        <td>{{ $p->mahasiswa->nama_lengkap }}</td>
                                        <td>{{ \Carbon\Carbon::parse($p->tanggal_bimbingan)->format('d M Y') }}</td>
                                        <td><span class="badge bg-{{ $p->status == 'direview' ? 'success' : 'warning' }}">{{ ucfirst(str_replace('_', ' ', $p->status)) }}</span></td>
                                        <td><a href="{{ route('bimbingan.show', $p->id) }}" class="btn btn-sm btn-outline-primary">Review</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">Belum ada progress bimbingan.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header bg-white pt-4 pb-0"><h5 class="fw-bold">Jadwal Sidang Terdekat</h5></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($data['jadwal_sidang'] as $sidang)
                            <li class="list-group-item px-0">
                                <div class="fw-bold">{{ $sidang->pengajuanJudul->mahasiswa->nama_lengkap }}</div>
                                <div class="text-muted small"><i class="bi bi-calendar"></i> {{ \Carbon\Carbon::parse($sidang->tanggal_sidang)->format('d M Y H:i') }}<br><i class="bi bi-geo-alt"></i> Ruang: {{ $sidang->ruangan }}</div>
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted">Belum ada jadwal sidang.</li>
                        @endforelse
                    </ul>
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white pt-4 pb-0"><h5 class="fw-bold">Aktivitas Terbaru</h5></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($data['aktivitas'] as $log)
                            <li class="list-group-item px-0"><span class="text-muted">{{ $log->aktivitas }}</span><br><small class="text-primary">{{ $log->created_at->diffForHumans() }}</small></li>
                        @empty
                            <li class="list-group-item px-0 text-muted">Belum ada aktivitas.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @elseif(auth()->user()->isMahasiswa())
    <!-- MAHASISWA -->
    @php
        $stageWeights = [
            'Proposal' => 1,
            'Bab 1' => 2,
            'Bab 2' => 3,
            'Bab 3' => 4,
            'Bab 4' => 5,
            'Bab 5' => 6,
            'Final' => 7
        ];
        
        $approvedStagesList = collect($data['progress_list'])->where('status', 'disetujui')->pluck('tahapan')->toArray();
        $highestStageIndex = 0;
        foreach($stageWeights as $stage => $weight) {
            if(in_array($stage, $approvedStagesList)) {
                $highestStageIndex = $weight;
            } else {
                break; // Hentikan perhitungan di tahapan pertama yang belum di-ACC
            }
        }
        
        $totalStages = 7;
        $progressPercentage = min(100, round(($highestStageIndex / $totalStages) * 100));
    @endphp

    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body py-4">
                    <h5 class="fw-bold mb-4">Status Tahapan Tugas Akhir Anda</h5>
                    
                    <div class="d-flex justify-content-between position-relative px-2">
                        <!-- Garis penghubung -->
                        <div class="position-absolute top-50 start-0 w-100 bg-light" style="height: 4px; transform: translateY(-150%); z-index: 1;"></div>
                        
                        @foreach($stageWeights as $stage => $weight)
                        @php
                            $isDone = in_array($stage, $approvedStagesList);
                        @endphp
                        <div class="position-relative text-center" style="z-index: 2; width: 14%;">
                            <div class="rounded-circle d-flex align-items-center justify-content-center mx-auto mb-2 {{ $isDone ? 'bg-success text-white shadow-sm' : 'bg-white text-muted border border-2' }}" style="width: 35px; height: 35px; font-weight: bold; transition: all 0.3s ease;">
                                @if($isDone) 
                                    <i class="bi bi-check-lg"></i> 
                                @else 
                                    {{ $weight }} 
                                @endif
                            </div>
                            <div class="fw-bold {{ $isDone ? 'text-success' : 'text-muted' }}" style="font-size: 0.75rem; letter-spacing: -0.2px;">{{ $stage }}</div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-md-6">
            <div class="card stat-card bg-primary text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold text-white-50">Status Tugas Akhir</h6>
                    <div class="stat-value">{{ ucfirst($data['status_ta']) }}</div>
                    @if($data['pengajuan'])
                        <p class="mt-2 mb-0 text-truncate" title="{{ $data['pengajuan']->judul }}">{{ $data['pengajuan']->judul }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-success text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold text-white-50">Total Bimbingan</h6>
                    <div class="stat-value">{{ $data['jml_progress'] }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card stat-card bg-info text-white h-100">
                <div class="card-body">
                    <h6 class="text-uppercase fw-bold text-white-50">Jadwal Sidang</h6>
                    <div class="stat-value">{{ $data['sidang'] ? \Carbon\Carbon::parse($data['sidang']->tanggal_sidang)->format('d M Y') : 'Belum Ditentukan' }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card mb-4 h-100">
                <div class="card-header bg-white pt-4 pb-0 d-flex justify-content-between"><h5 class="fw-bold">Grafik Progress Bimbingan</h5></div>
                <div class="card-body">
                    <div style="position: relative; height: 250px; width: 100%;">
                        <canvas id="progressChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-white pt-4 pb-0"><h5 class="fw-bold">Dosen Pembimbing</h5></div>
                <div class="card-body">
                    @if(count($data['pembimbings']) > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($data['pembimbings'] as $pem)
                                <li class="list-group-item px-0 d-flex align-items-center">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">{{ $pem->dosen->nama_lengkap }}</div>
                                        <span class="badge bg-secondary rounded-pill">{{ ucfirst(str_replace('_', ' ', $pem->tipe_pembimbing)) }}</span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted mb-0">Belum ada dosen pembimbing yang ditetapkan.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header bg-white pt-4 pb-0"><h5 class="fw-bold">Notifikasi</h5></div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @forelse($data['notifikasi'] as $notif)
                            <li class="list-group-item px-0">
                                <div class="fw-bold">{{ $notif->judul }}</div>
                                <p class="mb-0 text-muted small">{{ $notif->pesan }}</p>
                                <small class="text-primary">{{ $notif->created_at->diffForHumans() }}</small>
                            </li>
                        @empty
                            <li class="list-group-item px-0 text-muted">Belum ada notifikasi baru.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @if(!auth()->user()->isAdmin())
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @endif
    @if($data['jml_progress'] > 0)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('progressChart')?.getContext('2d');
            if (!ctx) return;
            const labels = {!! json_encode($data['progress_list']->pluck('tanggal_bimbingan')->map(function($d) { return \Carbon\Carbon::parse($d)->format('d M'); })) !!};
            const dataArr = [];
            for(let i=1; i<=labels.length; i++) dataArr.push(i);

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Akumulasi Bimbingan',
                        data: dataArr,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } } }
            });
        });
    </script>
    @else
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('progressChart')?.getContext('2d');
            if (!ctx) return;
            ctx.font = "14px Arial";
            ctx.fillStyle = "#6c757d";
            ctx.textAlign = "center";
            ctx.fillText("Data bimbingan belum tersedia", ctx.canvas.width/2, ctx.canvas.height/2);
        });
    </script>
    @endif
    @endpush

    @endif
</div>
@endsection
