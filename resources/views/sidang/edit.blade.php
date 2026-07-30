@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Kelola Jadwal & Status Sidang</h2>
    <div>
        <a href="{{ route('sidang.index') }}" class="btn btn-secondary">Kembali</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h6>Informasi Pengajuan</h6>
                <table class="table table-sm table-borderless">
                    <tr><td width="120">Mahasiswa</td><td>: {{ $sidang->pengajuanJudul->mahasiswa->nama_lengkap }}</td></tr>
                    <tr><td>NIM</td><td>: {{ $sidang->pengajuanJudul->mahasiswa->nim }}</td></tr>
                    <tr><td>Judul</td><td>: {{ $sidang->pengajuanJudul->judul }}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6>Berkas Persyaratan</h6>
                <a href="{{ Storage::url($sidang->naskah_final) }}" target="_blank" class="btn btn-outline-secondary btn-sm mb-2"><i class="bi bi-file-pdf"></i> Naskah Final</a><br>
                <a href="{{ Storage::url($sidang->surat_persetujuan) }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bi bi-file-pdf"></i> Surat Persetujuan</a>
            </div>
        </div>

        <form method="POST" action="{{ route('sidang.update', $sidang) }}">
            @csrf @method('PUT')
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="fw-bold">Dosen Penguji 1</label>
                        <input type="text" name="nama_penguji_1" class="form-control" value="{{ $sidang->nama_penguji_1 }}" placeholder="Masukkan nama penguji 1" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Dosen Penguji 2</label>
                        <input type="text" name="nama_penguji_2" class="form-control" value="{{ $sidang->nama_penguji_2 }}" placeholder="Masukkan nama penguji 2 (opsional)">
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Tanggal & Waktu Sidang</label>
                        <input type="datetime-local" name="tanggal_sidang" class="form-control" value="{{ $sidang->tanggal_sidang ? \Carbon\Carbon::parse($sidang->tanggal_sidang)->format('Y-m-d\TH:i') : '' }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="fw-bold">Ruangan</label>
                        <input type="text" name="ruangan" class="form-control" value="{{ $sidang->ruangan }}" placeholder="Misal: Ruang Sidang 1" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label class="fw-bold">Status Sidang</label>
                        <select name="status_lulus" class="form-control" required>
                            <option value="terjadwal" {{ $sidang->status_lulus == 'terjadwal' ? 'selected' : '' }}>Terjadwal</option>
                            <option value="selesai" {{ $sidang->status_lulus == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="revisi" {{ $sidang->status_lulus == 'revisi' ? 'selected' : '' }}>Revisi</option>
                        </select>
                    </div>
                    <div id="penilaianContainer" style="display: {{ $sidang->status_lulus == 'selesai' ? 'block' : 'none' }};">
                        <div class="mb-3">
                            <label class="fw-bold">Nilai Kerapihan (0-100)</label>
                            <input type="number" step="0.01" name="nilai_kerapihan" class="form-control" value="{{ $sidang->nilai_kerapihan }}" placeholder="0 - 100">
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Nilai Penguasaan Materi (0-100)</label>
                            <input type="number" step="0.01" name="nilai_penguasaan_materi" class="form-control" value="{{ $sidang->nilai_penguasaan_materi }}" placeholder="0 - 100">
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Nilai Presentasi (0-100)</label>
                            <input type="number" step="0.01" name="nilai_presentasi" class="form-control" value="{{ $sidang->nilai_presentasi }}" placeholder="0 - 100">
                        </div>
                        <div class="mb-3">
                            <label class="fw-bold">Nilai Akhir (Otomatis)</label>
                            <input type="text" class="form-control bg-light" value="{{ $sidang->nilai_akhir }}" readonly>
                        </div>
                    </div>
                    
                    <div class="mb-3" id="catatanRevisiContainer" style="display: {{ $sidang->status_lulus == 'revisi' ? 'block' : 'none' }};">
                        <label class="fw-bold">Catatan Revisi</label>
                        <textarea name="catatan_revisi" class="form-control" rows="4" placeholder="Tuliskan poin-poin revisi di sini...">{{ $sidang->catatan_revisi }}</textarea>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary w-100">Simpan Perubahan & Kirim Notifikasi</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var statusSelect = document.querySelector('select[name="status_lulus"]');
        var catatanContainer = document.getElementById('catatanRevisiContainer');
        var penilaianContainer = document.getElementById('penilaianContainer');

        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                // Logika Catatan Revisi
                if (catatanContainer) {
                    if (this.value === 'revisi') {
                        catatanContainer.style.display = 'block';
                    } else {
                        catatanContainer.style.display = 'none';
                    }
                }
                
                // Logika Kolom Penilaian
                if (penilaianContainer) {
                    if (this.value === 'selesai') {
                        penilaianContainer.style.display = 'block';
                    } else {
                        penilaianContainer.style.display = 'none';
                    }
                }
            });
        }
    });
</script>
@endsection