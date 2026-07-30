@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="mb-0 text-primary fw-bold"><i class="bi bi-people-fill me-2"></i>Data Mahasiswa</h3>
    <div>
        <button type="button" class="btn btn-outline-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">
            <i class="bi bi-file-earmark-excel"></i> Import Excel
        </button>
        <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Tambah Data</a>
    </div>
</div>

<div class="alert alert-info mb-4 border-0 shadow-sm">
    <i class="bi bi-info-circle-fill me-2"></i><strong>Info Login:</strong> Email otomatis dibuat berdasarkan NIM (NIM@piksi.ac.id) dan password default-nya adalah <code>password</code>.
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table datatable table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>NIM</th>
                        <th>Nama Lengkap</th>
                        <th>Email Login</th>
                        <th>Program Studi</th>
                        <th>Angkatan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($mahasiswas as $i => $mhs)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td class="fw-bold">{{ $mhs->nim }}</td>
                        <td>{{ $mhs->nama_lengkap }}</td>
                        <td>{{ $mhs->user->email ?? '-' }}</td>
                        <td><span class="badge bg-info text-dark">{{ $mhs->program_studi }}</span></td>
                        <td>{{ $mhs->angkatan }}</td>
                        <td class="text-center">
                            <a href="{{ route('mahasiswa.edit', $mhs->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-square"></i></a>
                            <form action="{{ route('mahasiswa.destroy', $mhs->id) }}" method="POST" class="d-inline no-loading" onsubmit="return confirm('Yakin ingin menghapus data ini? Akun login juga akan terhapus.');">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger"><i class="bi bi-trash-fill"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Import -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-secondary small">
                        <strong>Format Excel yang diizinkan (.xlsx, .csv):</strong><br>
                        Pastikan baris pertama (header) berisi:
                        <ul>
                            <li><code>nim</code> (wajib)</li>
                            <li><code>nama_lengkap</code> (wajib)</li>
                            <li><code>program_studi</code> (opsional)</li>
                            <li><code>angkatan</code> (opsional)</li>
                        </ul>
                    </div>
                    <div class="mb-3">
                        <label for="file" class="form-label">Pilih File Excel</label>
                        <input class="form-control" type="file" id="file" name="file" accept=".xlsx,.xls,.csv" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-upload"></i> Import Data</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection