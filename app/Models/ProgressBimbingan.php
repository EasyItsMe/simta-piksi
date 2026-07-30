<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProgressBimbingan extends Model {
    protected $table = 'progress_bimbingan';
    protected $fillable = ['pengajuan_judul_id', 'mahasiswa_id', 'dosen_id', 'tahapan', 'judul_progress', 'tanggal_bimbingan', 'catatan_mahasiswa', 'file_progress', 'status'];
    public function pengajuanJudul() { return $this->belongsTo(PengajuanJudul::class, 'pengajuan_judul_id'); }
    public function mahasiswa() { return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id'); }
    public function dosen() { return $this->belongsTo(Dosen::class, 'dosen_id'); }
    public function komentars() { return $this->hasMany(KomentarProgress::class, 'progress_bimbingan_id'); }
}