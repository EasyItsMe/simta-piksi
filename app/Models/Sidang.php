<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Sidang extends Model {
    protected $table = 'sidang';
    protected $fillable = ['pengajuan_judul_id', 'naskah_final', 'surat_persetujuan', 'nama_penguji_1', 'nama_penguji_2', 'tanggal_sidang', 'ruangan', 'nilai_akhir', 'status_lulus', 'nilai_kerapihan', 'nilai_penguasaan_materi', 'nilai_presentasi', 'catatan_revisi'];
    public function pengajuanJudul() { return $this->belongsTo(PengajuanJudul::class, 'pengajuan_judul_id'); }
}