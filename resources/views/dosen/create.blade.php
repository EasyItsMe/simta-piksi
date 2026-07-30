@extends('layouts.app')
@section('content')
<h3 class="mb-4 text-success fw-bold">Tambah Dosen</h3>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form action="{{ route('dosen.store') }}" method="POST">
            @csrf
            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">NIDN</label>
                    <input type="text" name="nidn" class="form-control" required>
                    <small class="text-muted">NIDN digunakan sebagai Username login.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" required>
                </div>
            </div>
            <div class="mb-4">
                <label class="form-label fw-bold">Bidang Keahlian</label>
                <input type="text" name="bidang_keahlian" class="form-control" required>
            </div>
            <hr>
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('dosen.index') }}" class="btn btn-secondary">Batal</a>
                <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Simpan Data</button>
            </div>
        </form>
    </div>
</div>
@endsection