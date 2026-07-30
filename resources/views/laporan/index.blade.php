@extends('layouts.app')
@section('content')
<h2 class="mb-4">Modul Cetak Laporan</h2>

<div class="row g-4">
    <!-- Laporan Mahasiswa -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Data Mahasiswa Tugas Akhir</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jenis_laporan" value="mahasiswa">
                    <div class="mb-2">
                        <label>Program Studi</label>
                        <select name="program_studi" class="form-control form-control-sm">
                            <option value="">Semua Program Studi</option>
                            @foreach($program_studi as $ps)
                                <option value="{{ $ps }}">{{ $ps }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="row mb-3">
                        <div class="col-6">
                            <label>Tahun</label>
                            <select name="tahun" class="form-control form-control-sm">
                                <option value="">Semua Tahun</option>
                                @foreach($tahun_sidang as $t)
                                    <option value="{{ $t }}">{{ $t }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label>Semester</label>
                            <select name="semester" class="form-control form-control-sm">
                                <option value="">Semua</option>
                                <option value="ganjil">Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="format" value="pdf" class="btn btn-danger btn-sm flex-fill"><i class="bi bi-file-pdf"></i> Cetak PDF</button>
                        <button type="submit" name="format" value="excel" class="btn btn-success btn-sm flex-fill"><i class="bi bi-file-excel"></i> Cetak Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan Dosen -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Data Dosen & Kuota Bimbingan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jenis_laporan" value="dosen">
                    <p class="text-muted mb-4">Laporan ini akan mencetak rekap seluruh dosen beserta jumlah mahasiswa bimbingan yang ditangani saat ini.</p>
                    <div class="d-flex gap-2 mt-auto">
                        <button type="submit" name="format" value="pdf" class="btn btn-danger btn-sm flex-fill"><i class="bi bi-file-pdf"></i> Cetak PDF</button>
                        <button type="submit" name="format" value="excel" class="btn btn-success btn-sm flex-fill"><i class="bi bi-file-excel"></i> Cetak Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Laporan Sidang -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Data Jadwal Sidang</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jenis_laporan" value="sidang">
                    <div class="mb-2">
                        <label>Tahun Sidang</label>
                        <select name="tahun" class="form-control form-control-sm">
                            <option value="">Semua Tahun</option>
                            @foreach($tahun_sidang as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Status Sidang</label>
                        <select name="status" class="form-control form-control-sm">
                            <option value="">Semua Status</option>
                            <option value="menunggu">Menunggu</option>
                            <option value="terjadwal">Terjadwal</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="format" value="pdf" class="btn btn-danger btn-sm flex-fill"><i class="bi bi-file-pdf"></i> Cetak PDF</button>
                        <button type="submit" name="format" value="excel" class="btn btn-success btn-sm flex-fill"><i class="bi bi-file-excel"></i> Cetak Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rekap Kelulusan -->
    <div class="col-md-6">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Rekapitulasi Kelulusan</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('laporan.export') }}" method="POST">
                    @csrf
                    <input type="hidden" name="jenis_laporan" value="kelulusan">
                    <div class="mb-2">
                        <label>Program Studi</label>
                        <select name="program_studi" class="form-control form-control-sm">
                            <option value="">Semua Program Studi</option>
                            @foreach($program_studi as $ps)
                                <option value="{{ $ps }}">{{ $ps }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Tahun Lulus</label>
                        <select name="tahun" class="form-control form-control-sm">
                            <option value="">Semua Tahun</option>
                            @foreach($tahun_sidang as $t)
                                <option value="{{ $t }}">{{ $t }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" name="format" value="pdf" class="btn btn-danger btn-sm flex-fill"><i class="bi bi-file-pdf"></i> Cetak PDF</button>
                        <button type="submit" name="format" value="excel" class="btn btn-success btn-sm flex-fill"><i class="bi bi-file-excel"></i> Cetak Excel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection