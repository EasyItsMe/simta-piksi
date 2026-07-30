@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Pengajuan Judul</h2>
    @if(auth()->user()->isMahasiswa())
        <a href="{{ route('pengajuan.create') }}" class="btn btn-primary">Ajukan Judul</a>
    @endif
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable" style="width:100%">
                <thead class="table-light">
                    <tr><th>Judul</th><th>Mahasiswa</th><th>Status</th><th>Pembimbing</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($pengajuan as $p)
                    @php
                        $labels = ['diajukan' => 'Pending', 'diterima' => 'Disetujui', 'ditolak' => 'Ditolak', 'direvisi' => 'Revisi'];
                        $statusLabel = $labels[$p->status] ?? ucfirst($p->status);
                        $badgeClass = ['diajukan' => 'warning', 'diterima' => 'success', 'ditolak' => 'danger', 'direvisi' => 'info'][$p->status] ?? 'secondary';
                    @endphp
                    <tr>
                        <td>
                            @if($p->status == 'diterima' && $p->judul_terpilih == 1)
                                <strong>{{ $p->judul }}</strong> <span class="badge bg-success">Terpilih</span>
                            @elseif($p->status == 'diterima' && $p->judul_terpilih == 2)
                                <strong>{{ $p->judul_2 }}</strong> <span class="badge bg-success">Terpilih</span>
                            @else
                                1. {{ $p->judul }}<br>
                                2. {{ $p->judul_2 }}
                            @endif
                        </td>
                        <td>{{ $p->mahasiswa->nama_lengkap }}</td>
                        <td><span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span></td>
                        <td>
                            @foreach($p->pembimbings as $pem)
                                {{ ucfirst(str_replace('_', ' ', $pem->tipe_pembimbing)) }}: {{ $pem->dosen->nama_lengkap }}<br>
                            @endforeach
                        </td>
                        <td>
                            <a href="{{ route('pengajuan.show', $p) }}" class="btn btn-sm btn-info text-white"><i class="bi bi-eye"></i> Detail</a>
                            @if(auth()->user()->isMahasiswa() && in_array($p->status, ['diajukan', 'direvisi']))
                                <a href="{{ route('pengajuan.edit', $p) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Edit</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $pengajuan->links() }}
        </div>
    </div>
</div>
@endsection