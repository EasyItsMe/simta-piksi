<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Mahasiswa extends Model {
    protected $table = 'mahasiswa';
    protected $fillable = ['user_id', 'nim', 'nama_lengkap', 'program_studi', 'angkatan'];
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function pengajuanJudul() { return $this->hasMany(PengajuanJudul::class, 'mahasiswa_id'); }
}