<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class KomentarProgress extends Model {
    protected $table = 'komentar_progress';
    protected $fillable = ['progress_bimbingan_id', 'dosen_id', 'komentar', 'file_revisi'];
    public function progressBimbingan() { return $this->belongsTo(ProgressBimbingan::class, 'progress_bimbingan_id'); }
    public function dosen() { return $this->belongsTo(Dosen::class, 'dosen_id'); }
}