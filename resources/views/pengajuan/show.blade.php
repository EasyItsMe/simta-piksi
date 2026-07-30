@extends('layouts.app')
@section('content')
<h2>Detail Pengajuan Judul</h2>
<div class="card mb-4">
    <div class="card-body">
        @php
            $labels = ['diajukan' => 'Pending', 'diterima' => 'Disetujui', 'ditolak' => 'Ditolak', 'direvisi' => 'Revisi'];
            $statusLabel = $labels[$pengajuan->status] ?? ucfirst($pengajuan->status);
            $badgeClass = ['diajukan' => 'warning', 'diterima' => 'success', 'ditolak' => 'danger', 'direvisi' => 'info'][$pengajuan->status] ?? 'secondary';
        @endphp
        <p><strong>Status:</strong> <span class="badge bg-{{ $badgeClass }}">{{ $statusLabel }}</span></p>
        <p><strong>Mahasiswa:</strong> {{ $pengajuan->mahasiswa->nama_lengkap }} ({{ $pengajuan->mahasiswa->nim }})</p>
        <h4>
            @if($pengajuan->status == 'diterima' && $pengajuan->judul_terpilih == 1)
                {{ $pengajuan->judul }} <span class="badge bg-success">Terpilih</span>
            @else
                Judul 1: {{ $pengajuan->judul }}
            @endif
        </h4>
        <p><strong>Deskripsi 1:</strong> {{ $pengajuan->deskripsi }}</p>

        <hr>

        <h4>
            @if($pengajuan->status == 'diterima' && $pengajuan->judul_terpilih == 2)
                {{ $pengajuan->judul_2 }} <span class="badge bg-success">Terpilih</span>
            @else
                Judul 2: {{ $pengajuan->judul_2 }}
            @endif
        </h4>
        <p><strong>Deskripsi 2:</strong> {{ $pengajuan->deskripsi_2 }}</p>
        
        @if($pengajuan->pesan)
        <hr>
        <div class="alert alert-info">
            <strong>Catatan / Pesan Revisi:</strong><br>
            {{ $pengajuan->pesan }}
        </div>
        @endif
        @if($pengajuan->file_proposal)
            <p><strong>File Proposal:</strong> <a href="{{ Storage::url($pengajuan->file_proposal) }}" target="_blank">Download PDF</a></p>
        @endif
    </div>
</div>

@if(auth()->user()->isAdmin())
    <div class="card">
        <div class="card-header">Update Status & Pembimbing</div>
        <div class="card-body">
            <form method="POST" action="{{ route('pengajuan.setPembimbing', $pengajuan) }}">
                @csrf
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" id="statusSelect" class="form-control">
                        <option value="diajukan" {{ $pengajuan->status == 'diajukan' ? 'selected' : '' }}>Pending</option>
                        <option value="direvisi" {{ $pengajuan->status == 'direvisi' ? 'selected' : '' }}>Revisi</option>
                        <option value="diterima" {{ $pengajuan->status == 'diterima' ? 'selected' : '' }}>Disetujui</option>
                        <option value="ditolak" {{ $pengajuan->status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                <div class="mb-3" id="judulTerpilihDiv">
                    <label>Judul yang Disetujui</label>
                    <select name="judul_terpilih" class="form-control">
                        <option value="">-- Pilih Judul --</option>
                        <option value="1" {{ $pengajuan->judul_terpilih == 1 ? 'selected' : '' }}>Judul 1</option>
                        <option value="2" {{ $pengajuan->judul_terpilih == 2 ? 'selected' : '' }}>Judul 2</option>
                    </select>
                </div>

                <div class="mb-3" id="pesanDiv">
                    <label>Catatan / Pesan Revisi</label>
                    <textarea name="pesan" class="form-control">{{ $pengajuan->pesan }}</textarea>
                </div>

                <div class="mb-3" id="pembimbing1Div">
                    <label>Pembimbing 1</label>
                    <select name="pembimbing_1" class="form-control">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosens as $d)
                            <option value="{{ $d->id }}" {{ $pengajuan->pembimbings->where('tipe_pembimbing','pembimbing_1')->first()?->dosen_id == $d->id ? 'selected' : '' }}>{{ $d->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3" id="pembimbing2Div">
                    <label>Pembimbing 2</label>
                    <select name="pembimbing_2" class="form-control">
                        <option value="">-- Pilih Dosen --</option>
                        @foreach($dosens as $d)
                            <option value="{{ $d->id }}" {{ $pengajuan->pembimbings->where('tipe_pembimbing','pembimbing_2')->first()?->dosen_id == $d->id ? 'selected' : '' }}>{{ $d->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusSelect = document.getElementById('statusSelect');
        const judulTerpilihDiv = document.getElementById('judulTerpilihDiv');
        const pesanDiv = document.getElementById('pesanDiv');
        const pembimbing1Div = document.getElementById('pembimbing1Div');
        const pembimbing2Div = document.getElementById('pembimbing2Div');
        
        function toggleFields() {
            if (statusSelect.value === 'diterima') {
                judulTerpilihDiv.style.display = 'block';
                pesanDiv.style.display = 'none'; 
                pembimbing1Div.style.display = 'block';
                pembimbing2Div.style.display = 'block';
            } else if (statusSelect.value === 'ditolak' || statusSelect.value === 'direvisi') {
                judulTerpilihDiv.style.display = 'none';
                pesanDiv.style.display = 'block';
                pembimbing1Div.style.display = 'none';
                pembimbing2Div.style.display = 'none';
            } else {
                judulTerpilihDiv.style.display = 'none';
                pesanDiv.style.display = 'none';
                pembimbing1Div.style.display = 'none';
                pembimbing2Div.style.display = 'none';
            }
        }
        
        statusSelect.addEventListener('change', toggleFields);
        toggleFields();
    });
</script>
@endpush