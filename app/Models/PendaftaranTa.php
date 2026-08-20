<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendaftaranTa extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'file_krs',
        'file_transkrip',
        'file_pembayaran',
        'status',
        'keterangan'
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
}
