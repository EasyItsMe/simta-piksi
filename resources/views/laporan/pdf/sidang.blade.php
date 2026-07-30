<!DOCTYPE html><html><head><title>Laporan Data Sidang</title><style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    th { background-color: #f2f2f2; }
    .text-center { text-align: center; }
    h2, h4 { margin: 0; padding: 0 0 10px 0; }
</style></head><body>
<h2 class="text-center">Data Penjadwalan Sidang</h2>
<h4 class="text-center">Politeknik Piksi Input Serang</h4>
<table>
    <thead><tr><th>No</th><th>Jadwal & Waktu</th><th>Ruangan</th><th>Mahasiswa (NIM)</th><th>Dosen Penguji</th><th>Status</th></tr></thead>
    <tbody>
        @foreach($data as $i => $s)
        <tr>
            <td class="text-center">{{ $i+1 }}</td>
            <td>{{ $s->tanggal_sidang ? date('d-m-Y H:i', strtotime($s->tanggal_sidang)) : '-' }}</td>
            <td>{{ $s->ruangan ?? '-' }}</td>
            <td>{{ $s->pengajuanJudul->mahasiswa->nama_lengkap }} ({{ $s->pengajuanJudul->mahasiswa->nim }})</td>
            <td>{{ $s->penguji ? $s->penguji->nama_lengkap : '-' }}</td>
            <td>{{ ucfirst($s->status_lulus) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</body></html>