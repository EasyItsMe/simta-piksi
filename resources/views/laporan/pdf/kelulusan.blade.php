<!DOCTYPE html><html><head><title>Rekap Kelulusan</title><style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    th { background-color: #f2f2f2; }
    .text-center { text-align: center; }
    h2, h4 { margin: 0; padding: 0 0 10px 0; }
</style></head><body>
<h2 class="text-center">Rekapitulasi Kelulusan Sidang Tugas Akhir</h2>
<h4 class="text-center">Politeknik Piksi Input Serang</h4>
<table>
    <thead><tr><th>No</th><th>Mahasiswa</th><th>Program Studi</th><th>Judul Tugas Akhir</th><th>Status Kelulusan</th><th>Nilai Akhir</th></tr></thead>
    <tbody>
        @foreach($data as $i => $s)
        <tr>
            <td class="text-center">{{ $i+1 }}</td>
            <td>{{ $s->pengajuanJudul->mahasiswa->nama_lengkap }}<br>{{ $s->pengajuanJudul->mahasiswa->nim }}</td>
            <td>{{ $s->pengajuanJudul->mahasiswa->program_studi }}</td>
            <td>{{ $s->pengajuanJudul->judul }}</td>
            <td><strong>{{ strtoupper($s->status_lulus) }}</strong></td>
            <td class="text-center">{{ $s->nilai_akhir ?? '-' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</body></html>