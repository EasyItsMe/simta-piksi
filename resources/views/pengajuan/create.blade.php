@extends('layouts.app')
@section('content')
<h2>Ajukan Judul</h2>
<form method="POST" action="{{ route('pengajuan.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="mb-3"><label>Judul 1</label><input type="text" name="judul" class="form-control" required></div>
    <div class="mb-3"><label>Deskripsi 1</label><textarea name="deskripsi" class="form-control" required></textarea></div>
    <div class="mb-3"><label>Judul 2</label><input type="text" name="judul_2" class="form-control" required></div>
    <div class="mb-3"><label>Deskripsi 2</label><textarea name="deskripsi_2" class="form-control" required></textarea></div>
    <div class="mb-3"><label>File Proposal (PDF/DOC/DOCX)</label><input type="file" name="file_proposal" class="form-control" accept=".pdf,.doc,.docx" required></div>
    <button type="submit" class="btn btn-primary">Submit</button>
</form>
@endsection