@extends('layouts.app')
@section('content')
<h3 class="mb-4 text-success fw-bold">Edit Dosen</h3>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('dosen.update', $dosen->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">NIDN</label>
                    <input type="text" name="nidn" class="form-control" value="{{ $dosen->nidn }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="{{ $dosen->nama_lengkap }}" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Bidang Keahlian</label>
                <input type="text" name="bidang_keahlian" class="form-control" value="{{ $dosen->bidang_keahlian }}" required>
            </div>
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Update Data</button>
            </div>
        </form>
    </div>
</div>
@endsection