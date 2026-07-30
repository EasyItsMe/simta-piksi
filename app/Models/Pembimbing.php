<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pembimbing extends Model {
    protected $table = 'pembimbing';
    protected $fillable = ['pengajuan_judul_id', 'dosen_id', 'tipe_pembimbing'];
    public function pengajuanJudul() { return $this->belongsTo(PengajuanJudul::class, 'pengajuan_judul_id'); }
    public function dosen() { return $this->belongsTo(Dosen::class, 'dosen_id'); }
}