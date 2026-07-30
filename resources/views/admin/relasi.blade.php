@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-3">
    <h2>Relasi Dosen & Mahasiswa</h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0 datatable" style="width:100%">
                <thead class="table-light">
                    <tr>
                        <th width="5%">No</th>
                        <th width="30%">Nama Dosen</th>
                        <th width="15%">NIDN</th>
                        <th width="50%">Mahasiswa Bimbingan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dosens as $index => $dosen)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><strong>{{ $dosen->nama_lengkap }}</strong></td>
                        <td>{{ $dosen->nidn }}</td>
                        <td>
                            @if($dosen->bimbingans->count() > 0)
                                <ul class="list-unstyled mb-0">
                                    @foreach($dosen->bimbingans as $bimbingan)
                                        @if($bimbingan->pengajuanJudul && $bimbingan->pengajuanJudul->mahasiswa)
                                            <li class="mb-1">
                                                <i class="bi bi-person-fill text-primary"></i> 
                                                {{ $bimbingan->pengajuanJudul->mahasiswa->nama_lengkap }}
                                                <span class="badge bg-secondary ms-1" style="font-size: 0.7em;">{{ str_replace('_', ' ', strtoupper($bimbingan->tipe_pembimbing)) }}</span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted fst-italic">Belum ada mahasiswa bimbingan</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
