@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold mb-0">Verifikasi Syarat Pendaftaran TA</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="px-4 py-3">Mahasiswa</th>
                            <th class="py-3">KRS</th>
                            <th class="py-3">Transkrip</th>
                            <th class="py-3">Pembayaran</th>
                            <th class="py-3">Status</th>
                            <th class="px-4 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendaftarans as $pendaftaran)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-bold">{{ $pendaftaran->mahasiswa->nama_lengkap }}</div>
                                <div class="text-muted small">{{ $pendaftaran->mahasiswa->nim }}</div>
                            </td>
                            <td class="py-3">
                                <a href="{{ asset('storage/' . $pendaftaran->file_krs) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf"></i> Lihat KRS
                                </a>
                            </td>
                            <td class="py-3">
                                <a href="{{ asset('storage/' . $pendaftaran->file_transkrip) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf"></i> Lihat Transkrip
                                </a>
                            </td>
                            <td class="py-3">
                                <a href="{{ asset('storage/' . $pendaftaran->file_pembayaran) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-pdf"></i> Lihat Bukti
                                </a>
                            </td>
                            <td class="py-3">
                                @if($pendaftaran->status == 'menunggu')
                                    <span class="badge bg-warning text-dark"><i class="fas fa-clock me-1"></i> Menunggu</span>
                                @elseif($pendaftaran->status == 'disetujui')
                                    <span class="badge bg-success"><i class="fas fa-check-circle me-1"></i> Disetujui</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times-circle me-1"></i> Ditolak</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-end">
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#verifikasiModal{{ $pendaftaran->id }}">
                                    Verifikasi
                                </button>

                                <!-- Modal Verifikasi -->
                                <div class="modal fade text-start" id="verifikasiModal{{ $pendaftaran->id }}" tabindex="-1">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Verifikasi Berkas: {{ $pendaftaran->mahasiswa->nama_lengkap }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <form action="{{ route('admin.pendaftaran-ta.status', $pendaftaran->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label fw-bold">Keputusan Verifikasi</label>
                                                        <select name="status" class="form-select" id="statusSelect{{ $pendaftaran->id }}" onchange="toggleKeterangan({{ $pendaftaran->id }})" required>
                                                            <option value="">-- Pilih Keputusan --</option>
                                                            <option value="disetujui" {{ $pendaftaran->status == 'disetujui' ? 'selected' : '' }}>ACC (Syarat Terpenuhi)</option>
                                                            <option value="ditolak" {{ $pendaftaran->status == 'ditolak' ? 'selected' : '' }}>Tolak (Ada Syarat Kurang/Salah)</option>
                                                        </select>
                                                    </div>
                                                    <div class="mb-3" id="keteranganDiv{{ $pendaftaran->id }}" style="display: {{ $pendaftaran->status == 'ditolak' ? 'block' : 'none' }};">
                                                        <label class="form-label fw-bold">Alasan Penolakan</label>
                                                        <textarea name="keterangan" class="form-control" rows="3" placeholder="Tuliskan dokumen apa yang salah/kurang...">{{ $pendaftaran->keterangan }}</textarea>
                                                        <small class="text-danger">Wajib diisi jika status Ditolak.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Simpan Keputusan</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox mb-3" style="font-size: 3rem;"></i>
                                <p>Belum ada mahasiswa yang mendaftar syarat TA.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function toggleKeterangan(id) {
    var select = document.getElementById('statusSelect' + id);
    var div = document.getElementById('keteranganDiv' + id);
    if(select.value === 'ditolak') {
        div.style.display = 'block';
        div.querySelector('textarea').required = true;
    } else {
        div.style.display = 'none';
        div.querySelector('textarea').required = false;
        div.querySelector('textarea').value = '';
    }
}
</script>
@endsection
