@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between mb-4">
    <h2>Upload Progress Bimbingan</h2>
</div>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('bimbingan.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="fw-bold form-label">Judul Tugas Akhir <span class="text-danger">*</span></label>
                        <select name="pengajuan_judul_id" id="pengajuan_judul_id" class="form-select" required>
                            <option value="">-- Pilih Judul --</option>
                            @foreach($pengajuan as $p)
                                <option value="{{ $p->id }}" data-pembimbings="{{ json_encode($p->pembimbings->map(function($pb) { return ['id' => $pb->dosen->id, 'nama' => $pb->dosen->nama_lengkap]; })) }}">{{ $p->judul_final }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold form-label">Dosen Pembimbing <span class="text-danger">*</span></label>
                        <select name="dosen_id" id="dosen_id" class="form-select" required>
                            <option value="">-- Pilih Dosen Pembimbing --</option>
                        </select>
                        <div class="form-text text-muted">Pilih dosen pembimbing yang akan dilaporkan progress-nya.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold form-label">Tahapan Progress <span class="text-danger">*</span></label>
                            <select name="tahapan" class="form-select" required>
                                <option value="">-- Pilih Tahapan --</option>
                                <option value="Proposal">Proposal</option>
                                <option value="Bab 1">Bab 1</option>
                                <option value="Bab 2">Bab 2</option>
                                <option value="Bab 3">Bab 3</option>
                                <option value="Bab 4">Bab 4</option>
                                <option value="Bab 5">Bab 5</option>
                                <option value="Final">Final (Siap Sidang)</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="fw-bold form-label">Tanggal Bimbingan <span class="text-danger">*</span></label>
                            <input type="date" name="tanggal_bimbingan" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="fw-bold form-label">Judul Topik / Aktivitas <span class="text-danger">*</span></label>
                        <input type="text" name="judul_progress" class="form-control" placeholder="Contoh: Revisi Bab 1 dan Pembuatan Kuesioner" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="fw-bold form-label">Uraian / Catatan Progress <span class="text-danger">*</span></label>
                        <textarea name="catatan_mahasiswa" class="form-control" rows="4" placeholder="Jelaskan progress yang telah dicapai..." required></textarea>
                    </div>
                    
                    <div class="mb-4">
                        <label class="fw-bold form-label">File Pendukung (Opsional)</label>
                        <input type="file" name="file_progress" class="form-control" accept=".pdf,.doc,.docx">
                        <div class="form-text text-muted"><i class="bi bi-info-circle"></i> Format PDF atau Word. Maksimal 5MB.</div>
                    </div>
                    
                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('bimbingan.index') }}" class="btn btn-secondary me-2">Batal</a>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-cloud-arrow-up"></i> Submit Progress</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('pengajuan_judul_id').addEventListener('change', function() {
            var selectedOption = this.options[this.selectedIndex];
            var pembimbings = selectedOption ? selectedOption.getAttribute('data-pembimbings') : null;
            var dosenSelect = document.getElementById('dosen_id');
            
            dosenSelect.innerHTML = '<option value="">-- Pilih Dosen Pembimbing --</option>';
            
            if(pembimbings) {
                JSON.parse(pembimbings).forEach(function(dosen) {
                    var option = document.createElement('option');
                    option.value = dosen.id;
                    option.text = dosen.nama;
                    dosenSelect.add(option);
                });
            }
        });
    });
</script>
@endsection