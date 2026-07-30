@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Jadwal & Pengajuan Sidang</h2>
    @if(auth()->user()->isMahasiswa())
        <div>
            <a href="{{ route('sidang.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajukan Sidang</a>
        </div>
    @endif
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th>Mahasiswa</th>
                        <th>Naskah & Berkas</th>
                        <th>Jadwal & Ruangan</th>
                        <th>Penguji</th>
                        <th>Status Sidang</th>
                        @if(auth()->user()->isAdmin())
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach($sidangs as $s)
                    @php
                        $badgeClass = [
                            'menunggu' => 'warning', 
                            'terjadwal' => 'info', 
                            'selesai' => 'primary', 
                            'revisi' => 'danger', 
                            'lulus' => 'success'
                        ][$s->status_lulus] ?? 'secondary';
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $s->pengajuanJudul->mahasiswa->nama_lengkap }}</strong><br>
                            <small class="text-muted">{{ Str::limit($s->pengajuanJudul->judul, 40) }}</small>
                        </td>
                        <td>
                            <a href="{{ Storage::url($s->naskah_final) }}" target="_blank" class="badge bg-secondary text-decoration-none"><i class="bi bi-file-pdf"></i> Naskah</a>
                            <a href="{{ Storage::url($s->surat_persetujuan) }}" target="_blank" class="badge bg-secondary text-decoration-none"><i class="bi bi-file-pdf"></i> Persetujuan</a>
                        </td>
                        <td>
                            @if($s->tanggal_sidang)
                                <strong>{{ \Carbon\Carbon::parse($s->tanggal_sidang)->format('d M Y H:i') }}</strong><br>
                                <span class="text-muted">Ruang: {{ $s->ruangan }}</span>
                            @else
                                <span class="text-muted fst-italic">Belum dijadwalkan</span>
                            @endif
                        </td>
                        <td>
                            1. {{ $s->nama_penguji_1 ?: '-' }}<br>
                            2. {{ $s->nama_penguji_2 ?: '-' }}<br>
                            @if($s->nilai_akhir)
                                <small class="text-primary fw-bold">Nilai: {{ $s->nilai_akhir }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-{{ $badgeClass }}">{{ ucfirst($s->status_lulus) }}</span>
                        </td>
                        @if(auth()->user()->isAdmin())
                            <td>
                                <a href="{{ route('sidang.edit', $s) }}" class="btn btn-sm btn-outline-primary">Jadwalkan / Update</a>
                            </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection