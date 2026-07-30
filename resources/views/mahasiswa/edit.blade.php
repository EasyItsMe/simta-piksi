@extends('layouts.app')
@section('content')
<h3 class="mb-4 text-primary fw-bold">Edit Mahasiswa</h3>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">NIM</label>
                    <input type="text" name="nim" class="form-control" value="{{ $mahasiswa->nim }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="{{ $mahasiswa->nama_lengkap }}" required>
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-bold">Program Studi</label>
                    <input type="text" name="program_studi" class="form-control" value="{{ $mahasiswa->program_studi }}" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Tahun Angkatan</label>
                    <input type="number" name="angkatan" class="form-control" value="{{ $mahasiswa->angkatan }}" required>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('mahasiswa.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update Data</button>
            </div>
        </form>
    </div>
</div>
@endsection