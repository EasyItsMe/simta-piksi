@extends('layouts.app')
@section('content')
<h2>Edit Pengajuan Judul</h2>
<form method="POST" action="{{ route('pengajuan.update', $pengajuan) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3"><label>Judul 1</label><input type="text" name="judul" value="{{ $pengajuan->judul }}" class="form-control" required></div>
    <div class="mb-3"><label>Deskripsi 1</label><textarea name="deskripsi" class="form-control" required>{{ $pengajuan->deskripsi }}</textarea></div>
    <div class="mb-3"><label>Judul 2</label><input type="text" name="judul_2" value="{{ $pengajuan->judul_2 }}" class="form-control" required></div>
    <div class="mb-3"><label>Deskripsi 2</label><textarea name="deskripsi_2" class="form-control" required>{{ $pengajuan->deskripsi_2 }}</textarea></div>
    <div class="mb-3">
        <label>File Proposal Baru (Opsional, PDF/DOC/DOCX)</label>
        <input type="file" name="file_proposal" class="form-control" accept=".pdf,.doc,.docx">
        @if($pengajuan->file_proposal)
            <small class="text-muted">File saat ini: <a href="{{ Storage::url($pengajuan->file_proposal) }}" target="_blank">Download</a></small>
        @endif
    </div>
    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
</form>
@endsection