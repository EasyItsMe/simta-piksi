<!DOCTYPE html><html><head><title>Laporan Data Mahasiswa</title><style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    th { background-color: #f2f2f2; }
    .text-center { text-align: center; }
    h2, h4 { margin: 0; padding: 0 0 10px 0; }
</style></head><body>
<h2 class="text-center">Data Mahasiswa Tugas Akhir</h2>
<h4 class="text-center">Politeknik Piksi Input Serang</h4>
<table>
    <thead><tr><th>No</th><th>NIM</th><th>Nama Lengkap</th><th>Program Studi</th><th>Judul TA Terdaftar</th></tr></thead>
    <tbody>
        @foreach($data as $i => $m)
        <tr>
            <td class="text-center">{{ $i+1 }}</td>
            <td>{{ $m->nim }}</td>
            <td>{{ $m->nama_lengkap }}</td>
            <td>{{ $m->program_studi }}</td>
            <td>{{ $m->pengajuanJudul ? $m->pengajuanJudul->judul : 'Belum Ada' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
</body></html>