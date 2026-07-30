<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Dosen extends Model {
    protected $table = 'dosen';
    protected $fillable = ['user_id', 'nidn', 'nama_lengkap', 'bidang_keahlian'];
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function bimbingans() { return $this->hasMany(Pembimbing::class, 'dosen_id'); }
}