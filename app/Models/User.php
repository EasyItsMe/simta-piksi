<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use SoftDeletes, Notifiable;
    protected $fillable = ['role_id', 'name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }
    public function role() { return $this->belongsTo(Role::class, 'role_id'); }
    public function mahasiswa() { return $this->hasOne(Mahasiswa::class, 'user_id'); }
    public function dosen() { return $this->hasOne(Dosen::class, 'user_id'); }
    
    // Helpers
    public function isAdmin() { return $this->role && $this->role->nama_role === 'Admin'; }
    public function isDosen() { return $this->role && $this->role->nama_role === 'Dosen'; }
    public function isMahasiswa() { return $this->role && $this->role->nama_role === 'Mahasiswa'; }
}